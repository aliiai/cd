<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

/**
 * Generate PDFs using mPDF library.
 *
 * Why mPDF:
 * - Excellent Arabic/RTL support out of the box
 * - No external dependencies (Chrome, etc.)
 * - Better encoding handling
 * - Native support for Arabic fonts and shaping
 */
class MpdfService
{
    public function htmlToPdfBytes(string $html, array $options = []): string
    {
        try {
            // Default configuration for Arabic/RTL
            $defaultConfig = (new ConfigVariables())->getDefaults();
            $fontDirs = $defaultConfig['fontDir'];
            
            $defaultFontConfig = (new FontVariables())->getDefaults();
            $fontData = $defaultFontConfig['fontdata'];
            
            // Configure mPDF for Arabic support with optimized settings
            $mpdfConfig = [
                'mode' => 'utf-8',
                'format' => $options['format'] ?? 'A4',
                'orientation' => $options['orientation'] ?? 'P',
                'margin_left' => $options['margin_left'] ?? 10,
                'margin_right' => $options['margin_right'] ?? 10,
                'margin_top' => $options['margin_top'] ?? 10,
                'margin_bottom' => $options['margin_bottom'] ?? 10,
                'margin_header' => $options['margin_header'] ?? 5,
                'margin_footer' => $options['margin_footer'] ?? 5,
                'fontDir' => array_merge($fontDirs, [
                    storage_path('fonts'),
                ]),
                'fontdata' => $fontData + [
                    'dejavusans' => [
                        'R' => 'DejaVuSans.ttf',
                        'B' => 'DejaVuSans-Bold.ttf',
                        'I' => 'DejaVuSans-Oblique.ttf',
                        'BI' => 'DejaVuSans-BoldOblique.ttf',
                    ],
                ],
                'default_font' => 'dejavusans',
                'default_font_size' => 9,
                // Don't set direction globally - let mPDF detect it from HTML
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
                'biDirectional' => true, // Enable bidirectional text support
                // Table settings - allow rows to split across pages
                'shrink_tables_to_fit' => 0, // Don't shrink tables
                'use_kwt' => false, // Don't keep rows together
                'table_error_report' => false,
                'table_error_report_param' => '',
                'keep_table_proportions' => false, // Allow flexible table sizing
                'simple_tables' => true, // Use simple table rendering for better page breaks
            ];
            
            $mpdf = new Mpdf($mpdfConfig);
            
            // Set document properties
            $mpdf->SetTitle($options['title'] ?? 'تقرير');
            $mpdf->SetAuthor($options['author'] ?? 'نظام إدارة الديون');
            $mpdf->SetSubject($options['subject'] ?? 'تقرير PDF');
            
            // Set table settings using mPDF properties
            // These settings allow table rows to split across pages naturally
            if (property_exists($mpdf, 'use_kwt')) {
                $mpdf->use_kwt = false; // Allow rows to break across pages
            }
            if (property_exists($mpdf, 'shrink_tables_to_fit')) {
                $mpdf->shrink_tables_to_fit = 0; // Don't shrink tables
            }
            
            // Add custom CSS for better formatting
            $customCss = '
                @page {
                    margin: 10mm;
                }
                body {
                    font-size: 9pt;
                }
                table {
                    page-break-inside: auto !important;
                    border-collapse: collapse;
                }
                tr {
                    page-break-inside: auto !important;
                    page-break-after: auto !important;
                }
                td, th {
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                    padding: 3px;
                }
                thead {
                    display: table-header-group;
                }
                tfoot {
                    display: table-footer-group;
                }
            ';
            $mpdf->WriteHTML($customCss, \Mpdf\HTMLParserMode::HEADER_CSS);
            
            // Write HTML content - use default parsing to properly handle <head> and <style> tags
            $mpdf->WriteHTML($html);
            
            // Output PDF as string
            return $mpdf->Output('', 'S');
            
        } catch (\Exception $e) {
            \Log::error('mPDF generation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw new \RuntimeException('فشل توليد ملف PDF: ' . $e->getMessage());
        }
    }
}

