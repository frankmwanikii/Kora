<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /#contact');
    exit;
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

if ($errors !== []) {
    header('Location: /#contact');
    exit;
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

$headers = 'From: ' . SITE_EMAIL . "\r\n"
    . 'Reply-To: ' . $fields['email'] . "\r\n"
    . 'Content-Type: text/plain; charset=UTF-8';

@mail(SITE_EMAIL, $subject, $body, $headers);

header('Location: /?submitted=1#contact');
exit;
