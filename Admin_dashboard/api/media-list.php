<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$q = trim((string) ($_GET['q'] ?? ''));
$folder = trim((string) ($_GET['folder'] ?? ''));
$limit = max(1, min(300, (int) ($_GET['limit'] ?? 200)));

$folderFilter = $folder !== '' ? $folder : null;
$items = kora_gallery_list($q !== '' ? $q : null, $folderFilter);

$payload = [];
foreach (array_slice($items, 0, $limit) as $item) {
    $path = (string) ($item['path'] ?? '');
    if ($path === '') {
        continue;
    }

    $payload[] = [
        'path' => $path,
        'filename' => (string) ($item['filename'] ?? basename($path)),
        'folder' => (string) ($item['folder'] ?? ''),
        'url' => (string) ($item['url'] ?? kora_media_url($path)),
        'preview' => (string) ($item['url'] ?? kora_media_url($path)),
        'size' => (int) ($item['size'] ?? 0),
        'kind' => 'image',
    ];
}

echo json_encode([
    'ok' => true,
    'total' => count($items),
    'items' => $payload,
]);
