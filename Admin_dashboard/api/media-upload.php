<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!verify_csrf($_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'error' => 'Invalid session']);
    exit;
}

$file = $_FILES['file'] ?? null;

if (!is_array($file)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'No file uploaded']);
    exit;
}

$result = kora_store_upload($pdo, $file);

if (empty($result['ok'])) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => (string) ($result['error'] ?? 'Upload failed'),
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'path' => (string) ($result['path'] ?? ''),
    'url' => (string) ($result['url'] ?? ''),
]);
