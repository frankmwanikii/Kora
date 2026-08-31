<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

function isAjaxRequest(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

function respondQuoteRequest(array $errors, string $successRedirect, string $errorRedirect): void
{
    if (isAjaxRequest()) {
        header('Content-Type: application/json; charset=UTF-8');

        if ($errors !== []) {
            http_response_code(422);
            echo json_encode(['errors' => $errors], JSON_THROW_ON_ERROR);
            exit;
        }

        echo json_encode(['redirect' => $successRedirect], JSON_THROW_ON_ERROR);
        exit;
    }

    header('Location: ' . ($errors !== [] ? $errorRedirect : $successRedirect));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /#contact');
    exit;
}

// Whitelisted redirect targets per form origin.
$fromQuotePage = trim((string) ($_POST['form_origin'] ?? '')) === 'request-quote';
$successRedirect = $fromQuotePage ? '/request-quote.php?submitted=1' : '/?submitted=1#contact';
$errorRedirect = $fromQuotePage ? '/request-quote.php#quote-form-section' : '/#contact';

// Honeypot: bots that fill this hidden field get a fake success response.
if (trim((string) ($_POST['website'] ?? '')) !== '') {
    respondQuoteRequest([], $successRedirect, $errorRedirect);
}

$fields = [
    'name' => trim($_POST['name'] ?? ''),
    'organization' => trim($_POST['organization'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'email' => trim($_POST['email'] ?? ''),
    'event_type' => trim($_POST['event_type'] ?? ''),
    'product' => trim($_POST['product'] ?? ''),
    'quantity' => trim($_POST['quantity'] ?? ''),
    'event_date' => trim($_POST['event_date'] ?? ''),
    'message' => trim($_POST['message'] ?? ''),
];

$required = ['name', 'phone', 'email', 'event_type', 'product', 'quantity', 'event_date', 'message'];
$errors = [];

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

if ($fields['quantity'] !== '' && (!ctype_digit($fields['quantity']) || (int) $fields['quantity'] < 1)) {
    $errors['quantity'] = 'Enter a quantity of 1 or more.';
}

if ($fields['event_date'] !== '') {
    $eventDate = DateTimeImmutable::createFromFormat('Y-m-d', $fields['event_date']);

    if ($eventDate === false || $eventDate->format('Y-m-d') !== $fields['event_date']) {
        $errors['event_date'] = 'Enter a valid event date.';
    }
}

$allowedExtensions = ['pdf', 'png', 'webp', 'jpg', 'jpeg'];
$allowedMimeTypes = [
    'application/pdf',
    'image/png',
    'image/webp',
    'image/jpeg',
];
$maxFileSize = 20 * 1024 * 1024;
$attachments = [];

if (!isset($_FILES['inspo_files'])) {
    $uploads = [];
} else {
    $uploads = $_FILES['inspo_files'];
}

if ($uploads !== [] && is_array($uploads['name'])) {
    $fileCount = count($uploads['name']);

    for ($i = 0; $i < $fileCount; $i += 1) {
        $errorCode = (int) $uploads['error'][$i];

        if ($errorCode === UPLOAD_ERR_NO_FILE) {
            continue;
        }

        if ($errorCode !== UPLOAD_ERR_OK) {
            $errors['inspo_files'] = 'One or more inspiration files could not be uploaded.';
            break;
        }

        $originalName = (string) $uploads['name'][$i];
        $tmpPath = (string) $uploads['tmp_name'][$i];
        $size = (int) $uploads['size'][$i];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            $errors['inspo_files'] = 'Only PDF, PNG, WebP, and JPG files are allowed.';
            break;
        }

        if ($size > $maxFileSize) {
            $errors['inspo_files'] = 'Each inspiration file must be 20MB or smaller.';
            break;
        }

        $detectedMime = mime_content_type($tmpPath) ?: '';
        if ($detectedMime !== '' && !in_array($detectedMime, $allowedMimeTypes, true)) {
            $errors['inspo_files'] = 'Only PDF, PNG, WebP, and JPG files are allowed.';
            break;
        }

        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($originalName)) ?: 'inspo-file.' . $extension;
        $fileData = file_get_contents($tmpPath);

        if ($fileData === false) {
            $errors['inspo_files'] = 'One or more inspiration files could not be read.';
            break;
        }

        $attachments[] = [
            'name' => $safeName,
            'mime' => $detectedMime !== '' ? $detectedMime : 'application/octet-stream',
            'data' => $fileData,
        ];
    }
}

if ($errors !== []) {
    respondQuoteRequest($errors, $successRedirect, $errorRedirect);
}

$subject = 'Quotation request from ' . $fields['name'];
$body = "Name: {$fields['name']}\n"
    . "Organization: {$fields['organization']}\n"
    . "Phone: {$fields['phone']}\n"
    . "Email: {$fields['email']}\n"
    . "Event type: {$fields['event_type']}\n"
    . "Product: {$fields['product']}\n"
    . "Quantity: {$fields['quantity']}\n"
    . "Event date: {$fields['event_date']}\n\n"
    . "Message:\n{$fields['message']}\n";

if ($attachments !== []) {
    $body .= "\nInspiration files attached: "
        . implode(', ', array_column($attachments, 'name'))
        . "\n";
} else {
    $body .= "\nNo inspiration files attached.\n";
}

if ($attachments === []) {
    $headers = 'From: ' . SITE_EMAIL . "\r\n"
        . 'Reply-To: ' . $fields['email'] . "\r\n"
        . 'Content-Type: text/plain; charset=UTF-8';
    $message = $body;
} else {
    $boundary = 'kora_' . bin2hex(random_bytes(12));
    $headers = 'From: ' . SITE_EMAIL . "\r\n"
        . 'Reply-To: ' . $fields['email'] . "\r\n"
        . 'MIME-Version: 1.0' . "\r\n"
        . 'Content-Type: multipart/mixed; boundary="' . $boundary . '"';

    $message = '--' . $boundary . "\r\n"
        . 'Content-Type: text/plain; charset=UTF-8' . "\r\n\r\n"
        . $body . "\r\n";

    foreach ($attachments as $attachment) {
        $message .= '--' . $boundary . "\r\n"
            . 'Content-Type: ' . $attachment['mime'] . '; name="' . $attachment['name'] . '"' . "\r\n"
            . 'Content-Transfer-Encoding: base64' . "\r\n"
            . 'Content-Disposition: attachment; filename="' . $attachment['name'] . '"' . "\r\n\r\n"
            . chunk_split(base64_encode($attachment['data'])) . "\r\n";
    }

    $message .= '--' . $boundary . '--';
}

@mail(SITE_EMAIL, $subject, $message, $headers);

respondQuoteRequest([], $successRedirect, $errorRedirect);
