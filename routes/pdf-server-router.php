<?php
// Simple router for PDF generation
$file = __DIR__ . '/../../storage/app/tmp/pdf' . $_SERVER['REQUEST_URI'];
if (file_exists($file)) {
    readfile($file);
} else {
    http_response_code(404);
}
