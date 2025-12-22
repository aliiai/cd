<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/**
 * Generate PDFs via headless Chrome printing.
 *
 * Why:
 * - DomPDF does NOT support Arabic shaping (glyph joining) correctly.
 * - Chrome printing renders Arabic/RTL exactly like the browser.
 */
class ChromePdfService
{
    public function htmlToPdfBytes(string $html, array $options = []): string
    {
        $chromePath = $this->findChromeExecutable();
        if (!$chromePath) {
            throw new \RuntimeException('Google Chrome غير موجود على السيرفر/الجهاز. لا يمكن توليد PDF.');
        }

        $tmpDir = storage_path('app/tmp/pdf');
        File::ensureDirectoryExists($tmpDir);

        $id = (string) Str::uuid();
        $htmlPath = $tmpDir . DIRECTORY_SEPARATOR . "report_{$id}.html";
        $pdfPath = $tmpDir . DIRECTORY_SEPARATOR . "report_{$id}.pdf";

        // Ensure UTF-8 (Arabic) is preserved - save without BOM
        File::put($htmlPath, $html);

        // Use HTTP server instead of file:// URL to avoid encoding issues
        // Start a temporary PHP built-in server
        $port = $this->findAvailablePort();
        $serverUrl = "http://127.0.0.1:{$port}/report_{$id}.html";
        
        // Start PHP built-in server in background
        $serverProcess = $this->startPhpServer($tmpDir, $port);
        
        // Wait a moment for server to start
        usleep(500000); // 0.5 seconds
        
        // Normalize PDF path
        $pdfPathNormalized = str_replace('\\', '/', $pdfPath);

        // Build Chrome command - use HTTP URL instead of file://
        if (PHP_OS_FAMILY === 'Windows') {
            $chromePathEscaped = escapeshellarg($chromePath);
            $pdfPathEscaped = escapeshellarg($pdfPathNormalized);
            $serverUrlEscaped = escapeshellarg($serverUrl);
            
            $command = sprintf(
                '%s --headless --disable-gpu --no-sandbox --disable-dev-shm-usage --disable-software-rasterizer --disable-extensions --lang=ar --print-to-pdf=%s %s',
                $chromePathEscaped,
                $pdfPathEscaped,
                $serverUrlEscaped
            );
            
            $process = Process::fromShellCommandline($command);
        } else {
            $args = [
                $chromePath,
                '--headless',
                '--disable-gpu',
                '--no-sandbox',
                '--disable-dev-shm-usage',
                '--disable-software-rasterizer',
                '--disable-extensions',
                '--lang=ar',
                "--print-to-pdf={$pdfPathNormalized}",
                $serverUrl,
            ];
            $process = new Process($args);
        }
        
        $process->setTimeout($options['timeout'] ?? 60);
        $process->setWorkingDirectory(dirname($chromePath));
        
        try {
            $process->run();
        } catch (\Exception $e) {
            $this->stopPhpServer($serverProcess);
            Log::error('Chrome PDF process exception', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException('خطأ في تشغيل Chrome: ' . $e->getMessage());
        }

        // Stop PHP server
        $this->stopPhpServer($serverProcess);

        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput();
            $output = $process->getOutput();
            
            $commandStr = PHP_OS_FAMILY === 'Windows' 
                ? (isset($command) ? $command : 'N/A')
                : (isset($args) ? implode(' ', $args) : 'N/A');
            
            Log::error('Chrome PDF generation failed', [
                'exit_code' => $process->getExitCode(),
                'error_output' => $errorOutput,
                'output' => $output,
                'command' => $commandStr,
                'html_path' => $htmlPath,
                'pdf_path' => $pdfPath,
            ]);
            
            $errorMsg = 'فشل توليد ملف PDF عبر Chrome.';
            if ($errorOutput) {
                $errorMsg .= ' الخطأ: ' . $errorOutput;
            }
            throw new \RuntimeException($errorMsg);
        }

        if (!File::exists($pdfPath) || File::size($pdfPath) === 0) {
            throw new \RuntimeException('تم تشغيل Chrome لكن لم يتم إنشاء ملف PDF.');
        }

        $bytes = File::get($pdfPath);

        // Cleanup
        File::delete($htmlPath);
        File::delete($pdfPath);

        return $bytes;
    }

    private function findChromeExecutable(): ?string
    {
        // Common Windows locations
        $candidates = [
            env('CHROME_PATH'),
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
        ];

        foreach ($candidates as $path) {
            if (is_string($path) && $path !== '' && File::exists($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Find an available port for the temporary HTTP server
     */
    private function findAvailablePort(): int
    {
        $port = 8000;
        $maxAttempts = 100;
        
        for ($i = 0; $i < $maxAttempts; $i++) {
            $connection = @fsockopen('127.0.0.1', $port, $errno, $errstr, 0.1);
            if (!$connection) {
                return $port;
            }
            fclose($connection);
            $port++;
        }
        
        throw new \RuntimeException('لا يمكن العثور على منفذ متاح للخادم المؤقت.');
    }

    /**
     * Start PHP built-in server
     */
    private function startPhpServer(string $documentRoot, int $port): ?Process
    {
        $phpPath = PHP_BINARY;
        $routerScript = __DIR__ . '/../../routes/pdf-server-router.php';
        
        // Create a simple router script if it doesn't exist
        if (!File::exists($routerScript)) {
            File::ensureDirectoryExists(dirname($routerScript));
            File::put($routerScript, "<?php\n// Simple router for PDF generation\n\$file = __DIR__ . '/../../storage/app/tmp/pdf' . \$_SERVER['REQUEST_URI'];\nif (file_exists(\$file)) {\n    readfile(\$file);\n} else {\n    http_response_code(404);\n}\n");
        }
        
        $command = sprintf(
            '%s -S 127.0.0.1:%d -t %s %s',
            escapeshellarg($phpPath),
            $port,
            escapeshellarg($documentRoot),
            escapeshellarg($routerScript)
        );
        
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(null);
        $process->start();
        
        return $process;
    }

    /**
     * Stop PHP built-in server
     */
    private function stopPhpServer(?Process $process): void
    {
        if ($process && $process->isRunning()) {
            $process->stop(0, SIGTERM);
        }
    }
}


