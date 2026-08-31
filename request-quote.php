<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'request-quote';
$page_title = page_title('Request a Quotation');
$page_description = 'Request a custom quotation from KORA — medals, awards, trophies, and souvenirs made in-house in Nanyuki, Laikipia. Share your brief and get a tailored quote within 24 hours.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/request-quote-page.php';
require __DIR__ . '/includes/footer.php';
