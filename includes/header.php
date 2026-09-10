<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/seo.php';

$current_page = $current_page ?? 'home';

$headerCms = function_exists('cms_section') ? cms_section('header') : [];
$headerNavDefault = [
    ['label' => 'Home', 'href' => '/', 'page' => 'home'],
    ['label' => 'Our work', 'href' => '/work-samples', 'page' => 'work-samples'],
    ['label' => 'Products', 'href' => '/products', 'page' => 'products'],
    ['label' => 'How it works', 'href' => '/how-it-works', 'page' => 'how-it-works'],
    ['label' => 'Contact Us', 'href' => '/contact-us', 'page' => 'contact-us'],
];
$headerNav = (isset($headerCms['nav']) && is_array($headerCms['nav']) && $headerCms['nav'] !== [])
    ? array_values($headerCms['nav'])
    : $headerNavDefault;

$headerCta = is_array($headerCms['cta'] ?? null) ? $headerCms['cta'] : [];
$headerCtaLabel = (string) ($headerCta['label'] ?? 'Request a Quotation');
$headerCtaHref = (string) ($headerCta['href'] ?? '/request-quote');

$page_title = $page_title ?? page_title();
$page_description = $page_description ?? (string) ($headerCms['default_description'] ?? 'KORA creates custom awards, medals, plaques, and souvenirs in Nanyuki, Laikipia — laser-cut and hand-finished. Recognition made personal for organisations, NGOs, corporates, and sports teams.');
$page_keywords = $page_keywords ?? 'custom awards Kenya, custom medals Nanyuki, trophies Laikipia, laser cut awards, plaques, souvenirs, KORA Laser Craft, corporate awards Kenya, marathon medals';
$page_robots = $page_robots ?? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
$page_og_type = $page_og_type ?? 'website';
$page_og_image = $page_og_image ?? seo_default_image();
$page_og_image_alt = $page_og_image_alt ?? (SITE_NAME . ' — custom awards, medals, and souvenirs');
$canonical_url = seo_canonical_url($page_canonical ?? null);
$page_schemas = is_array($page_schemas ?? null) ? $page_schemas : [];

$logo_icon = seo_image_url('logos/kora_logo1.webp');
?>
<!DOCTYPE html>
<html lang="en-KE">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>">
    <meta name="author" content="<?= htmlspecialchars(SITE_NAME) ?>">
    <meta name="robots" content="<?= htmlspecialchars($page_robots) ?>">
    <meta name="googlebot" content="<?= htmlspecialchars($page_robots) ?>">
    <meta name="theme-color" content="#333652">
    <meta name="color-scheme" content="light">
    <meta name="format-detection" content="telephone=yes">
    <meta name="geo.region" content="KE-30">
    <meta name="geo.placename" content="Nanyuki, Laikipia">
    <meta name="geo.position" content="-0.0166;37.0728">
    <meta name="ICBM" content="-0.0166, 37.0728">

    <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>">
    <link rel="alternate" hreflang="en-ke" href="<?= htmlspecialchars($canonical_url) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($canonical_url) ?>">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="<?= htmlspecialchars(seo_url('/sitemap.xml')) ?>">

    <link rel="icon" href="<?= htmlspecialchars($logo_icon) ?>" type="image/webp">
    <link rel="apple-touch-icon" href="<?= htmlspecialchars($logo_icon) ?>">

    <meta property="og:site_name" content="<?= htmlspecialchars(SITE_NAME) ?>">
    <meta property="og:locale" content="en_KE">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta property="og:type" content="<?= htmlspecialchars($page_og_type) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($canonical_url) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($page_og_image) ?>">
    <meta property="og:image:secure_url" content="<?= htmlspecialchars($page_og_image) ?>">
    <meta property="og:image:alt" content="<?= htmlspecialchars($page_og_image_alt) ?>">
    <meta property="og:image:type" content="image/webp">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($page_og_image) ?>">
    <meta name="twitter:image:alt" content="<?= htmlspecialchars($page_og_image_alt) ?>">

    <?php
    $baseSchemas = [seo_organization_schema(), seo_website_schema()];
    seo_render_json_ld(array_merge($baseSchemas, $page_schemas));
    ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>
    <a class="visually-hidden" href="#main">Skip to content</a>
    <header class="site-header" id="top">
        <div class="site-header__inner">
            <?php render_brand_logo('header'); ?>
            <nav class="site-nav" aria-label="Primary">
                <ul class="site-nav__list">
                    <?php foreach ($headerNav as $navItem): ?>
                        <?php
                        if (!is_array($navItem)) {
                            continue;
                        }
                        $label = (string) ($navItem['label'] ?? '');
                        $href = (string) ($navItem['href'] ?? '#');
                        $pageKey = (string) ($navItem['page'] ?? '');
                        if ($label === '') {
                            continue;
                        }
                        $isActive = $pageKey !== '' && $current_page === $pageKey;
                        ?>
                        <li>
                            <a class="site-nav__link<?= $isActive ? ' is-active' : '' ?>" href="<?= htmlspecialchars($href) ?>">
                                <?= htmlspecialchars($label) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
            <div class="site-header__actions">
                <a class="site-header__cta btn btn--solid" href="<?= htmlspecialchars($headerCtaHref) ?>"><?= htmlspecialchars($headerCtaLabel) ?></a>
                <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="mobile-nav" aria-label="Open menu">
                    <span class="nav-toggle__bars" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
        </div>
    </header>
    <button type="button" class="mobile-nav-overlay" id="mobile-nav-overlay" hidden aria-label="Close menu"></button>
    <nav class="mobile-nav" id="mobile-nav" aria-label="Mobile" hidden>
        <ul class="mobile-nav__list">
            <?php foreach ($headerNav as $navItem): ?>
                <?php
                if (!is_array($navItem)) {
                    continue;
                }
                $label = (string) ($navItem['label'] ?? '');
                $href = (string) ($navItem['href'] ?? '#');
                $pageKey = (string) ($navItem['page'] ?? '');
                if ($label === '') {
                    continue;
                }
                $isActive = $pageKey !== '' && $current_page === $pageKey;
                ?>
                <li>
                    <a class="mobile-nav__link<?= $isActive ? ' is-active' : '' ?>" href="<?= htmlspecialchars($href) ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                </li>
            <?php endforeach; ?>
            <li class="mobile-nav__cta-item">
                <a class="mobile-nav__cta btn btn--solid" href="<?= htmlspecialchars($headerCtaHref) ?>"><?= htmlspecialchars($headerCtaLabel) ?></a>
            </li>
        </ul>
    </nav>
    <main id="main">
