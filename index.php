<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'home';
$page_title = page_title();
$page_description = 'KORA Laser Craft creates custom awards, medals, plaques, and souvenirs in Nanyuki, Laikipia — laser-cut and hand-finished. Recognition made personal for organisations, NGOs, corporates, schools, and sports teams.';
$page_og_image = seo_image_url('awards/Award_hero.webp');
$page_og_image_alt = 'Custom KORA awards and trophies made in Nanyuki';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
    ]),
];

require __DIR__ . '/includes/header.php';

require __DIR__ . '/components/hero.php';
require __DIR__ . '/components/about.php';
require __DIR__ . '/components/what-we-make.php';
require __DIR__ . '/components/how-to-order.php';
require __DIR__ . '/components/workshop-banner.php';
require __DIR__ . '/components/faq.php';
require __DIR__ . '/components/contact-form.php';

require __DIR__ . '/includes/footer.php';
