<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'contact-us';
$page_title = page_title('Contact Us');
$page_description = 'Get in touch with KORA in Nanyuki, Laikipia — WhatsApp, phone, email, or send a message. We respond within one business day.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/contact-us-page.php';
require __DIR__ . '/includes/footer.php';
