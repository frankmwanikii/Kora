<?php

declare(strict_types=1);

/**
 * Securely stream a stored quote inspiration file for viewing or download.
 * URLs are unguessable tokens emailed to the studio inbox.
 */

$token = (string) ($_GET['t'] ?? '');
$forceDownload = isset($_GET['dl']) && (string) $_GET['dl'] !== '0' && (string) $_GET['dl'] !== '';

if (!preg_match('/^[a-f0-9]{32,64}$/', $token)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'File not found.';
    exit;
}

$baseDir = realpath(__DIR__ . '/data/quote-files');
if ($baseDir === false || !is_dir($baseDir)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'File not found.';
    exit;
}

$dir = realpath($baseDir . '/' . $token);
if ($dir === false || !str_starts_with($dir, $baseDir . DIRECTORY_SEPARATOR) || !is_dir($dir)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'File not found.';
    exit;
}

$metaPath = $dir . '/meta.json';
$meta = [];
if (is_file($metaPath)) {
    $decoded = json_decode((string) file_get_contents($metaPath), true);
    if (is_array($decoded)) {
        $meta = $decoded;
    }
}

$name = (string) ($meta['name'] ?? 'attachment');
$mime = (string) ($meta['mime'] ?? 'application/octet-stream');
$extension = (string) ($meta['extension'] ?? pathinfo($name, PATHINFO_EXTENSION));
$extension = preg_replace('/[^a-z0-9]/i', '', $extension) ?: 'bin';

$filePath = $dir . '/file.' . strtolower($extension);
if (!is_file($filePath)) {
    $matches = glob($dir . '/file.*') ?: [];
    $filePath = $matches[0] ?? '';
}

if ($filePath === '' || !is_file($filePath)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'File not found.';
    exit;
}

$realFile = realpath($filePath);
if ($realFile === false || !str_starts_with($realFile, $dir . DIRECTORY_SEPARATOR)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'File not found.';
    exit;
}

$size = filesize($realFile);
if ($size === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Unable to read file.';
    exit;
}

$safeName = preg_replace('/[\r\n"]+/', '_', $name) ?: 'attachment';
$dispositionType = $forceDownload ? 'attachment' : 'inline';

// Prefer a browser-friendly type for inline viewing.
if (!$forceDownload && $mime === 'application/octet-stream') {
    $lower = strtolower($extension);
    $mime = match ($lower) {
        'pdf' => 'application/pdf',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        default => $mime,
    };
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . (string) $size);
header('Content-Disposition: ' . $dispositionType . '; filename="' . $safeName . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=86400');
header('Referrer-Policy: no-referrer');

$handle = fopen($realFile, 'rb');
if ($handle === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Unable to read file.';
    exit;
}

fpassthru($handle);
fclose($handle);
exit;
