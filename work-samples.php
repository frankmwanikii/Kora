<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'work-samples';
$page_title = page_title('Our Work');
$page_description = 'Browse KORA work samples — custom medals, awards, trophies, and souvenirs made in-house in Nanyuki, Laikipia.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/work-samples.php';
require __DIR__ . '/includes/footer.php';
