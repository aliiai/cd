<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\Log;

/**
 * Generate PDFs using Browsershot (Chrome headless).
 *
 * Why Browsershot:
 * - Perfect Arabic/RTL support using Chrome's rendering engine
 * - Native browser rendering = perfect text display
 * - No encoding issues
 * - Supports all modern CSS and HTML features
 */
class BrowsershotPdfService
{
    public function htmlToPdfBytes(string $html, array $options = []): string
    {
        try {
            // Create a temporary HTML file
            $tempHtmlFile = tempnam(sys_get_temp_dir(), 'pdf_') . '.html';
            file_put_contents($tempHtmlFile, $html);
            
            // Determine paper size
            $format = $options['format'] ?? 'A4';
            $orientation = $options['orientation'] ?? 'portrait';
            
            // Configure Browsershot
            // Try to use system Chrome if available
            $chromePath = $this->findChromeExecutable();
            
            $browsershot = Browsershot::html(file_get_contents($tempHtmlFile))
                ->setOption('args', [
                    '--no-sandbox',
                    '--disable-setuid-sandbox',
                    '--disable-dev-shm-usage',
                    '--disable-gpu',
                    '--lang=ar',
                ])
                ->margins(
                    $options['margin_top'] ?? 10,
                    $options['margin_right'] ?? 10,
                    $options['margin_bottom'] ?? 10,
                    $options['margin_left'] ?? 10,
                    'mm'
                )
                ->format($format)
                ->landscape($orientation === 'landscape')
                ->waitUntilNetworkIdle()
                ->timeout(120);
            
            // Set Chrome path if found
            if ($chromePath) {
                $browsershot->setChromePath($chromePath);
            }
            
            // Generate PDF
            $pdfBytes = $browsershot->pdf();
            
            // Clean up temporary file
            @unlink($tempHtmlFile);
            
            return $pdfBytes;
            
        } catch (\Exception $e) {
            // Clean up on error
            if (isset($tempHtmlFile)) {
                @unlink($tempHtmlFile);
            }
            
            Log::error('Browsershot PDF generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            throw new \RuntimeException('فشل توليد ملف PDF عبر Browsershot: ' . $e->getMessage());
        }
    }
    
    /**
     * Find Chrome/Chromium executable on Windows
     */
    private function findChromeExecutable(): ?string
    {
        $paths = [
            'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
            'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
            'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe',
            getenv('LOCALAPPDATA') . '\\Google\\Chrome\\Application\\chrome.exe',
            getenv('PROGRAMFILES') . '\\Google\\Chrome\\Application\\chrome.exe',
            getenv('PROGRAMFILES(X86)') . '\\Google\\Chrome\\Application\\chrome.exe',
        ];
        
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
}

