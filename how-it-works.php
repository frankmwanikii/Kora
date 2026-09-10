<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'how-it-works';
$page_title = page_title('How It Works — Order Process');
$page_description = 'See how to order custom medals, awards, and souvenirs from KORA — a simple five-step process from brief to delivery, made in-house in Nanyuki, Laikipia.';
$page_keywords = 'order custom awards Kenya, medal ordering process, trophy quote steps, KORA how it works';
$page_og_image = seo_image_url('laser_1.webp');
$page_og_image_alt = 'Laser engraving a custom design in the KORA workshop';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'How it works', 'url' => '/how-it-works'],
    ]),
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/how-it-works-page.php';
require __DIR__ . '/includes/footer.php';
