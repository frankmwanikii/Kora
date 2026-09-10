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

$subject = 'Newsletter subscription — ' . SITE_NAME;
$plain = "New newsletter subscription from the website.\n\n"
    . "Email: {$email}\n"
    . 'Submitted: ' . date('Y-m-d H:i:s') . "\n";

$html = '<p style="margin:0 0 12px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:1.5;color:#333652;">'
    . 'New newsletter subscription from the website.'
    . '</p>'
    . '<p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.5;color:#333652;">'
    . '<strong>Email:</strong> ' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8')
    . '</p>';

kora_send_mail(SITE_EMAIL, $subject, $plain, $email, [], $html);

header('Location: ' . $redirectBase . '?newsletter=1#footer-newsletter-title');
exit;
