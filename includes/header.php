<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$current_page = $current_page ?? 'home';
$page_title = $page_title ?? page_title();
$page_description = $page_description ?? 'KORA creates custom awards, medals, plaques, and souvenirs by hand in Nanyuki, Laikipia. Recognition made personal for organisations, NGOs, corporates, and sports teams.';
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
    <meta property="og:image" content="<?= htmlspecialchars(SITE_URL . asset('assets/images/kora-logo.png')) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=League+Spartan:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/glacial-indifference" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('assets/css/main.css') ?>">
</head>
<body>
    <a class="visually-hidden" href="#main">Skip to content</a>
    <header class="site-header" id="top">
        <div class="site-header__inner">
            <?php render_brand_logo('header'); ?>
            <nav class="site-nav" aria-label="Primary">
                <ul class="site-nav__list">
                    <li><a class="site-nav__link<?= $current_page === 'home' ? ' is-active' : '' ?>" href="/">Home</a></li>
                    <li><a class="site-nav__link<?= $current_page === 'work-samples' ? ' is-active' : '' ?>" href="/work-samples.php">Our work</a></li>
                    <li><a class="site-nav__link<?= $current_page === 'products' ? ' is-active' : '' ?>" href="/products.php">Products</a></li>
                    <li><a class="site-nav__link<?= $current_page === 'how-it-works' ? ' is-active' : '' ?>" href="/how-it-works.php">How it works</a></li>
                </ul>
            </nav>
            <div class="site-header__actions">
                <a class="site-header__cta btn btn--solid" href="/#contact">Request a Quotation</a>
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
            <li><a class="mobile-nav__link<?= $current_page === 'home' ? ' is-active' : '' ?>" href="/">Home</a></li>
            <li><a class="mobile-nav__link<?= $current_page === 'work-samples' ? ' is-active' : '' ?>" href="/work-samples.php">Our work</a></li>
            <li><a class="mobile-nav__link<?= $current_page === 'products' ? ' is-active' : '' ?>" href="/products.php">Products</a></li>
            <li><a class="mobile-nav__link<?= $current_page === 'how-it-works' ? ' is-active' : '' ?>" href="/how-it-works.php">How it works</a></li>
            <li class="mobile-nav__cta-item"><a class="mobile-nav__cta btn btn--solid" href="/#contact">Request a Quotation</a></li>
        </ul>
    </nav>
    <main id="main">
