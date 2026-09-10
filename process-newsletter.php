<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/mail.php';

$referer = $_SERVER['HTTP_REFERER'] ?? '/';
$path = parse_url($referer, PHP_URL_PATH) ?: '/';
$redirectBase = is_string($path) && str_starts_with($path, '/') ? $path : '/';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

// Honeypot
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    header('Location: ' . $redirectBase . '?newsletter=1#footer-newsletter-title');
    exit;
}

$email = trim((string) ($_POST['email'] ?? ''));

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $redirectBase . '?newsletter=error#footer-newsletter-title');
    exit;
}

$admin = kora_newsletter_admin_email($email);
$customer = kora_newsletter_confirmation_email($email);

$adminOk = kora_send_mail(
    SITE_EMAIL,
    $admin['subject'],
    $admin['text'],
    $email,
    [],
    $admin['html']
);

$customerOk = kora_send_mail(
    $email,
    $customer['subject'],
    $customer['text'],
    SITE_EMAIL,
    [],
    $customer['html']
);

if (!$customerOk) {
    error_log('KORA newsletter confirmation email failed for ' . $email);
}

if (!$adminOk && !$customerOk) {
    header('Location: ' . $redirectBase . '?newsletter=error#footer-newsletter-title');
    exit;
}

$storeDir = __DIR__ . '/data/newsletter';
if (!is_dir($storeDir)) {
    @mkdir($storeDir, 0775, true);
}

if (is_dir($storeDir) && is_writable($storeDir)) {
    $payload = [
        'id' => 'newsletter-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)),
        'submitted_at' => date('c'),
        'email' => $email,
        'mail' => [
            'admin' => $adminOk,
            'customer' => $customerOk,
        ],
    ];
    @file_put_contents(
        $storeDir . '/' . $payload['id'] . '.json',
        json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
    );
}

header('Location: ' . $redirectBase . '?newsletter=1#footer-newsletter-title');
exit;
