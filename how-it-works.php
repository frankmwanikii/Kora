<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'how-it-works';
$page_title = page_title('How It Works');
$page_description = 'See how to order custom medals, awards, and souvenirs from KORA — a simple five-step process from brief to delivery, made in-house in Nanyuki, Laikipia.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/how-it-works-page.php';
require __DIR__ . '/includes/footer.php';
