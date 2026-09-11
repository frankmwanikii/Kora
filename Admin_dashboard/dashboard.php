<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Dashboard';
$pageSubtitle = 'Website overview';
$activeNav = 'dashboard';

$admin = Auth::admin();
$firstName = trim(explode(' ', (string) ($admin['name'] ?? 'there'))[0] ?: 'there');
$hour = (int) date('G');
$greeting = $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening');

$sectionsCount = count(kora_sections_catalog()) - 1;
$mediaItems = kora_gallery_list();
$mediaCount = count($mediaItems);
$siteData = kora_get_site_json();
$settings = is_array($siteData['settings'] ?? null) ? $siteData['settings'] : kora_default_settings();
$siteName = (string) ($settings['site_name'] ?? 'KORA');
$lastExport = (string) ($siteData['updated_at'] ?? '—');

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro">
        <div>
            <p class="pages-hub__eyebrow"><?= e($greeting) ?></p>
            <h2 class="pages-hub__title"><?= e($firstName) ?>, welcome back</h2>
            <p class="pages-hub__lead">Manage KORA website content, media, and site settings from here.</p>
        </div>
    </header>

    <div class="ov-metrics">
        <div class="ov-metric">
            <span class="ov-metric-icon"><i class="fa-solid fa-file-lines" aria-hidden="true"></i></span>
            <span class="ov-metric-label">Content sections</span>
            <span class="ov-metric-value"><?= (int) $sectionsCount ?></span>
        </div>
        <div class="ov-metric ov-metric--copper">
            <span class="ov-metric-icon"><i class="fa-solid fa-images" aria-hidden="true"></i></span>
            <span class="ov-metric-label">Media library</span>
            <span class="ov-metric-value"><?= (int) $mediaCount ?></span>
        </div>
        <div class="ov-metric">
            <span class="ov-metric-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></span>
            <span class="ov-metric-label">Last export</span>
            <span class="ov-metric-value" style="font-size:1.1rem"><?= e($lastExport) ?></span>
        </div>
        <div class="ov-metric">
            <span class="ov-metric-icon"><i class="fa-solid fa-globe" aria-hidden="true"></i></span>
            <span class="ov-metric-label">Site name</span>
            <span class="ov-metric-value" style="font-size:1.35rem"><?= e($siteName) ?></span>
        </div>
    </div>

    <div class="ov-quick">
        <span class="ov-quick-label">Quick links</span>
        <div class="ov-quick-list">
            <a class="ov-quick-link" href="<?= e(admin_base_path()) ?>/pages.php">
                <i class="fa-solid fa-file-lines" aria-hidden="true"></i> Pages
            </a>
            <a class="ov-quick-link" href="<?= e(admin_base_path()) ?>/gallery.php">
                <i class="fa-solid fa-images" aria-hidden="true"></i> Gallery
            </a>
            <a class="ov-quick-link" href="<?= e(admin_base_path()) ?>/emails.php">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i> Emails
            </a>
            <a class="ov-quick-link" href="<?= e(admin_base_path()) ?>/settings.php">
                <i class="fa-solid fa-gear" aria-hidden="true"></i> Settings
            </a>
            <a class="ov-quick-link" href="/" target="_blank" rel="noopener">
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> View website
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
