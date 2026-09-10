<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'request-quote';
$page_title = page_title('Request a Quotation');
$page_description = 'Request a custom quotation from KORA — medals, awards, trophies, and souvenirs made in-house in Nanyuki, Laikipia. Share your brief and get a tailored quote within 24 hours.';
$page_keywords = 'request quote awards Kenya, custom medal quotation, trophy price quote Nanyuki, KORA quotation form';
$page_og_image = seo_image_url('awards/Award_hero.webp');
$page_og_image_alt = 'Request a custom award quotation from KORA';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Request a Quotation', 'url' => '/request-quote'],
    ]),
    [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => 'Request a Quotation',
        'url' => seo_url('/request-quote'),
        'description' => 'Request a custom quotation for medals, awards, trophies, and souvenirs from KORA.',
        'isPartOf' => ['@id' => seo_url('/') . '#website'],
        'about' => ['@id' => seo_url('/') . '#organization'],
    ],
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/request-quote-page.php';
require __DIR__ . '/includes/footer.php';
