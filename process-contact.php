<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/mail.php';

function isContactAjaxRequest(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * @param array<string, string> $errors
 */
function respondContactRequest(array $errors): void
{
    $successRedirect = '/contact-us?submitted=1#contact-form-section';
    $errorRedirect = '/contact-us#contact-form-section';

    if (isContactAjaxRequest()) {
        header('Content-Type: application/json; charset=UTF-8');

        if ($errors !== []) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'errors' => $errors], JSON_THROW_ON_ERROR);
            exit;
        }

        echo json_encode([
            'ok' => true,
            'success' => true,
            'message' => 'Message sent — thank you!',
            'whatsapp' => SITE_WHATSAPP,
            'redirect' => $successRedirect,
        ], JSON_THROW_ON_ERROR);
        exit;
    }

    header('Location: ' . ($errors !== [] ? $errorRedirect : $successRedirect));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contact-us');
    exit;
}

if (trim((string) ($_POST['website'] ?? '')) !== '') {
    respondContactRequest([]);
}

$fields = [
    'name' => trim((string) ($_POST['name'] ?? '')),
    'organization' => trim((string) ($_POST['organization'] ?? '')),
    'email' => trim((string) ($_POST['email'] ?? '')),
    'phone' => trim((string) ($_POST['phone'] ?? '')),
    'subject' => trim((string) ($_POST['subject'] ?? '')),
    'message' => trim((string) ($_POST['message'] ?? '')),
];

$errors = [];
$required = ['name', 'email', 'phone', 'subject', 'message'];

foreach ($required as $key) {
    if ($fields[$key] === '') {
        $errors[$key] = 'This field is required.';
    }
}

if ($fields['email'] !== '' && !filter_var($fields['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Enter a valid email address.';
}

if ($fields['phone'] !== '' && !preg_match('/^[\d\s+()-]{7,20}$/', $fields['phone'])) {
    $errors['phone'] = 'Enter a valid phone number.';
}

if (mb_strlen($fields['subject']) > 160) {
    $errors['subject'] = 'Keep the subject under 160 characters.';
}

if (mb_strlen($fields['message']) > 5000) {
    $errors['message'] = 'Please keep your message under 5,000 characters.';
}

if ($errors !== []) {
    respondContactRequest($errors);
}

$admin = kora_contact_admin_email($fields);
$customer = kora_contact_confirmation_email($fields);

$adminOk = kora_send_mail(
    SITE_EMAIL,
    $admin['subject'],
    $admin['text'],
    $fields['email'],
    [],
    $admin['html']
);

$customerOk = kora_send_mail(
    $fields['email'],
    $customer['subject'],
    $customer['text'],
    SITE_EMAIL,
    [],
    $customer['html']
);

if (!$adminOk && !$customerOk) {
    respondContactRequest([
        'form' => 'Your message could not be sent. Please try WhatsApp or email us directly.',
    ]);
}

$storeDir = __DIR__ . '/data/contacts';
if (!is_dir($storeDir)) {
    @mkdir($storeDir, 0775, true);
}

if (is_dir($storeDir) && is_writable($storeDir)) {
    $payload = [
        'id' => 'contact-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)),
        'submitted_at' => date('c'),
        'fields' => $fields,
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

respondContactRequest([]);
