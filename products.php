<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'products';
$page_title = page_title('Products');
$page_description = 'Explore KORA products — custom medals, awards, trophies, and souvenirs made in-house from wood, MDF, plywood, and acrylic in Nanyuki, Laikipia.';

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/products.php';
require __DIR__ . '/includes/footer.php';
