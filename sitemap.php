<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/seo.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

/**
 * @param list<array{loc: string, title?: string, caption?: string}> $images
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_unique_images(array $images): array
{
    $seen = [];
    $out = [];

    foreach ($images as $image) {
        $loc = (string) ($image['loc'] ?? '');
        if ($loc === '' || isset($seen[$loc])) {
            continue;
        }
        $seen[$loc] = true;
        $out[] = $image;
    }

    return $out;
}

/**
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_home_images(): array
{
    $images = [];

    $heroSlides = cms_list('hero', 'slides', []);
    $images = array_merge($images, seo_collect_images($heroSlides, 'KORA custom awards and medals'));

    $about = cms_section('about');
    $aboutBg = (string) ($about['background_image'] ?? '');
    if ($aboutBg !== '') {
        $images[] = [
            'loc' => seo_image_url($aboutBg),
            'title' => 'KORA workshop',
            'caption' => 'Where laser meets craft — KORA studio in Nanyuki',
        ];
    }

    $whatCards = cms_list('what_we_make', 'cards', []);
    foreach ($whatCards as $card) {
        if (!is_array($card)) {
            continue;
        }
        $label = (string) ($card['label'] ?? 'KORA product');
        $image = $card['image'] ?? null;
        if (is_array($image)) {
            $images = array_merge($images, seo_collect_images([$image], $label));
        }
    }

    $processImages = cms_list('what_we_make', 'process_images', []);
    $images = array_merge($images, seo_collect_images($processImages, 'KORA laser craft process'));

    return sitemap_unique_images($images);
}

/**
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_products_images(): array
{
    $images = [];

    $productsCms = cms_section('products');
    $hero = is_array($productsCms['hero'] ?? null) ? $productsCms['hero'] : [];
    $heroImage = is_array($hero['image'] ?? null) ? $hero['image'] : [];
    if ($heroImage !== []) {
        $images = array_merge($images, seo_collect_images([$heroImage], 'KORA products'));
    }

    $categories = cms_list('products', 'categories', []);
    foreach ($categories as $category) {
        if (!is_array($category)) {
            continue;
        }
        $title = (string) ($category['title'] ?? 'KORA product');
        $heroCat = is_array($category['hero'] ?? null) ? $category['hero'] : [];
        if ($heroCat !== []) {
            $images = array_merge($images, seo_collect_images([$heroCat], $title));
        }
        $gallery = is_array($category['images'] ?? null) ? $category['images'] : [];
        $images = array_merge($images, seo_collect_images($gallery, $title));
    }

    $materials = is_array($productsCms['materials_section'] ?? null)
        ? $productsCms['materials_section']
        : (is_array($productsCms['materials_block'] ?? null) ? $productsCms['materials_block'] : []);
    $materialItems = is_array($materials['items'] ?? null) ? $materials['items'] : [];
    foreach ($materialItems as $item) {
        if (!is_array($item)) {
            continue;
        }
        $file = (string) ($item['image'] ?? '');
        if ($file === '') {
            continue;
        }
        $images[] = [
            'loc' => seo_image_url($file),
            'title' => (string) ($item['name'] ?? 'Material'),
            'caption' => (string) ($item['alt'] ?? $item['text'] ?? ''),
        ];
    }

    return sitemap_unique_images($images);
}

/**
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_work_samples_images(): array
{
    $images = [];
    $work = cms_section('work_samples');
    $hero = is_array($work['hero'] ?? null) ? $work['hero'] : [];
    $heroImage = is_array($hero['image'] ?? null) ? $hero['image'] : [];
    if ($heroImage !== []) {
        $images = array_merge($images, seo_collect_images([$heroImage], 'KORA studio samples'));
    }

    $galleries = cms_list('work_samples', 'galleries', []);
    foreach ($galleries as $gallery) {
        if (!is_array($gallery)) {
            continue;
        }
        $title = (string) ($gallery['title'] ?? 'KORA work sample');
        $galleryImages = is_array($gallery['images'] ?? null) ? $gallery['images'] : [];
        $images = array_merge($images, seo_collect_images($galleryImages, $title));
    }

    return sitemap_unique_images($images);
}

/**
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_how_it_works_images(): array
{
    $images = [];
    $how = cms_section('how_it_works');
    $hero = is_array($how['hero'] ?? null) ? $how['hero'] : [];
    $heroImage = is_array($hero['image'] ?? null) ? $hero['image'] : [];
    if ($heroImage !== []) {
        $images = array_merge($images, seo_collect_images([$heroImage], 'How ordering from KORA works'));
    }

    $steps = cms_list('how_it_works', 'step_details', []);
    foreach ($steps as $step) {
        if (!is_array($step)) {
            continue;
        }
        $title = (string) ($step['title'] ?? 'KORA process step');
        $image = is_array($step['image'] ?? null) ? $step['image'] : [];
        if ($image !== []) {
            $images = array_merge($images, seo_collect_images([$image], $title));
        }
    }

    return sitemap_unique_images($images);
}

/**
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function sitemap_contact_images(): array
{
    $contact = cms_section('contact');
    $image = is_array($contact['image'] ?? null) ? $contact['image'] : [];

    return $image !== [] ? seo_collect_images([$image], 'Contact KORA workshop') : [];
}

$pages = [
    [
        'loc' => '/',
        'file' => __DIR__ . '/index.php',
        'changefreq' => 'weekly',
        'priority' => '1.0',
        'images' => sitemap_home_images(),
    ],
    [
        'loc' => '/work-samples',
        'file' => __DIR__ . '/work-samples.php',
        'changefreq' => 'weekly',
        'priority' => '0.9',
        'images' => sitemap_work_samples_images(),
    ],
    [
        'loc' => '/products',
        'file' => __DIR__ . '/products.php',
        'changefreq' => 'weekly',
        'priority' => '0.9',
        'images' => sitemap_products_images(),
    ],
    [
        'loc' => '/how-it-works',
        'file' => __DIR__ . '/how-it-works.php',
        'changefreq' => 'monthly',
        'priority' => '0.8',
        'images' => sitemap_how_it_works_images(),
    ],
    [
        'loc' => '/request-quote',
        'file' => __DIR__ . '/request-quote.php',
        'changefreq' => 'monthly',
        'priority' => '0.85',
        'images' => [],
    ],
    [
        'loc' => '/contact-us',
        'file' => __DIR__ . '/contact-us.php',
        'changefreq' => 'monthly',
        'priority' => '0.8',
        'images' => sitemap_contact_images(),
    ],
    [
        'loc' => '/privacy',
        'file' => __DIR__ . '/privacy.php',
        'changefreq' => 'yearly',
        'priority' => '0.3',
        'images' => [],
    ],
];

// Prefer CMS export mtime when newer than the page PHP file.
$cmsPath = __DIR__ . '/data/cms/site.json';
$cmsMtime = is_file($cmsPath) ? (int) filemtime($cmsPath) : 0;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset
  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
  xmlns:xhtml="http://www.w3.org/1999/xhtml"
>
<?php foreach ($pages as $page): ?>
<?php
    $loc = seo_url($page['loc']);
    $fileMtime = is_file($page['file']) ? (int) filemtime($page['file']) : 0;
    $lastmodTs = max($fileMtime, $cmsMtime, time() - 86400 * 365);
    $lastmod = date('Y-m-d', $lastmodTs > 0 ? $lastmodTs : time());
    $images = is_array($page['images'] ?? null) ? $page['images'] : [];
?>
  <url>
    <loc><?= htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></loc>
    <lastmod><?= htmlspecialchars($lastmod, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></lastmod>
    <changefreq><?= htmlspecialchars($page['changefreq'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></changefreq>
    <priority><?= htmlspecialchars($page['priority'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></priority>
    <xhtml:link rel="alternate" hreflang="en-ke" href="<?= htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?>"/>
    <xhtml:link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?>"/>
<?php foreach ($images as $image): ?>
<?php
    $imageLoc = (string) ($image['loc'] ?? '');
    if ($imageLoc === '') {
        continue;
    }
?>
    <image:image>
      <image:loc><?= htmlspecialchars($imageLoc, ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></image:loc>
<?php if (!empty($image['title'])): ?>
      <image:title><?= htmlspecialchars((string) $image['title'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></image:title>
<?php endif; ?>
<?php if (!empty($image['caption'])): ?>
      <image:caption><?= htmlspecialchars((string) $image['caption'], ENT_XML1 | ENT_QUOTES, 'UTF-8') ?></image:caption>
<?php endif; ?>
    </image:image>
<?php endforeach; ?>
  </url>
<?php endforeach; ?>
</urlset>
