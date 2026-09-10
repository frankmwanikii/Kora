<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'work-samples';
$page_title = page_title('Our Work — Studio Samples');
$page_description = 'Browse KORA work samples — custom medals, awards, trophies, and souvenirs made in-house in Nanyuki, Laikipia for sports, corporates, schools, and NGOs.';
$page_keywords = 'KORA work samples, custom medal gallery, award samples Kenya, trophy examples, souvenir samples Nanyuki';
$page_og_image = seo_image_url('workshop_hero.webp');
$page_og_image_alt = 'Custom KORA awards, medals, and souvenirs in the workshop';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Our work', 'url' => '/work-samples'],
    ]),
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/work-samples.php';
require __DIR__ . '/includes/footer.php';
