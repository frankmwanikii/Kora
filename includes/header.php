<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$current_page = $current_page ?? 'home';

$headerCms = function_exists('cms_section') ? cms_section('header') : [];
$headerNavDefault = [
    ['label' => 'Home', 'href' => '/', 'page' => 'home'],
    ['label' => 'Our work', 'href' => '/work-samples', 'page' => 'work-samples'],
    ['label' => 'Products', 'href' => '/products', 'page' => 'products'],
    ['label' => 'How it works', 'href' => '/how-it-works', 'page' => 'how-it-works'],
];
$headerNav = (isset($headerCms['nav']) && is_array($headerCms['nav']) && $headerCms['nav'] !== [])
    ? array_values($headerCms['nav'])
    : $headerNavDefault;

$headerCta = is_array($headerCms['cta'] ?? null) ? $headerCms['cta'] : [];
$headerCtaLabel = (string) ($headerCta['label'] ?? 'Request a Quotation');
$headerCtaHref = (string) ($headerCta['href'] ?? '/request-quote');

$page_title = $page_title ?? page_title();
$page_description = $page_description ?? (string) ($headerCms['default_description'] ?? 'KORA creates custom awards, medals, plaques, and souvenirs in Nanyuki, Laikipia — laser-cut and hand-finished. Recognition made personal for organisations, NGOs, corporates, and sports teams.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
    <link rel="canonical" href="<?= htmlspecialchars(SITE_URL . ($_SERVER['REQUEST_URI'] === '/' ? '' : $_SERVER['REQUEST_URI'])) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= htmlspecialchars(SITE_URL) ?>">
    <meta property="og:image" content="<?= htmlspecialchars(SITE_URL . asset('assets/images/logos/kora_logo1.png')) ?>">
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
