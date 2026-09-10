<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

$current_page = 'contact-us';
$page_title = page_title('Contact Us');
$page_description = 'Get in touch with KORA in Nanyuki, Laikipia — WhatsApp, phone, email, or send a message. We respond within one business day.';
$page_keywords = 'contact KORA, awards workshop Nanyuki, custom medals phone, Laikipia laser craft contact';
$page_og_image = seo_image_url('medals/workshop_hero.webp');
$page_og_image_alt = 'Custom KORA awards and medals in the workshop';
$page_schemas = [
    seo_breadcrumb_schema([
        ['name' => 'Home', 'url' => '/'],
        ['name' => 'Contact Us', 'url' => '/contact-us'],
    ]),
    [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact KORA',
        'url' => seo_url('/contact-us'),
        'description' => 'Contact KORA Laser Craft in Nanyuki for custom awards, medals, and souvenirs.',
        'mainEntity' => ['@id' => seo_url('/') . '#organization'],
    ],
];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/components/contact-us-page.php';
require __DIR__ . '/includes/footer.php';
