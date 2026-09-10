<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'products';
$page_title = page_title('Custom Medals, Awards & Souvenirs');
$page_description = 'Explore KORA products — custom medals, awards, trophies, and souvenirs made in-house from wood, MDF, plywood, and acrylic in Nanyuki, Laikipia.';
$page_keywords = 'custom medals Kenya, custom trophies, wooden awards, acrylic awards, souvenirs, marathon medals, corporate awards Nanyuki';
$page_og_image = seo_image_url('awards/Hero Section.webp');
$page_og_image_alt = 'Collection of custom KORA awards and trophies';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Products', 'url' => '/products'],
    ]),
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/products.php';
require __DIR__ . '/includes/footer.php';
