<?php

declare(strict_types=1);

/** @var string $pageTitle */
/** @var string|null $pageSubtitle */
/** @var string|null $activeNav */
/** @var array<int, string>|string|null $extraScripts */

$pageSubtitle = $pageSubtitle ?? '';
$activeNav = $activeNav ?? '';
$extraScripts = $extraScripts ?? [];
$adminBase = admin_base_path();
$adminUser = Auth::admin();
$flash = flash_take();

if (!$adminUser) {
    Auth::requireLogin();
    exit;
}

$adminName = (string) ($adminUser['name'] ?? 'Admin');
$adminUsername = (string) ($adminUser['username'] ?? '');
$adminInitials = initials($adminName !== '' ? $adminName : $adminUsername);
$cssPath = __DIR__ . '/../assets/css/admin.css';
$cssVersion = is_file($cssPath) ? (int) filemtime($cssPath) : 1;
$logoWhitePath = site_root() . '/assets/images/logos/kora_logo_white.webp';
$logoFallbackPath = site_root() . '/assets/images/logos/kora_logo1.webp';
$logoPath = is_file($logoWhitePath) ? $logoWhitePath : $logoFallbackPath;
$logoUrl = '/assets/images/logos/' . basename($logoPath) . '?v=' . (is_file($logoPath) ? (int) filemtime($logoPath) : 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> · KORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= e($adminBase) ?>/assets/css/admin.css?v=<?= $cssVersion ?>">
    <script>
        (function () {
            try {
                if (localStorage.getItem('kora.sidebarCollapsed') === '1' && window.matchMedia('(min-width: 1025px)').matches) {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) { /* ignore */ }
        })();
    </script>
</head>
<body class="admin-body">
    <div class="sidebar-overlay" id="sidebar-overlay" hidden></div>

    <aside class="sidebar" id="sidebar" aria-label="Admin navigation">
        <div class="sidebar-header">
            <a href="<?= e($adminBase) ?>/dashboard.php" class="sidebar-brand">
                <img class="sidebar-brand-logo" src="<?= e($logoUrl) ?>" alt="KORA Laser Craft" width="220" height="220" decoding="async">
                <img class="sidebar-brand-mark" src="<?= e($logoUrl) ?>" alt="" width="44" height="44" decoding="async">
                <span class="sidebar-brand-sub">Admin Console</span>
            </a>
            <button type="button" class="sidebar-close" id="sidebar-close" aria-label="Close menu">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-group" data-nav-group="overview">
                <button type="button" class="nav-group-toggle" aria-expanded="true">
                    <span>Overview</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="nav-group-items">
                    <a href="<?= e($adminBase) ?>/dashboard.php" class="nav-item<?= nav_active('dashboard', $activeNav) ?>" title="Dashboard">
                        <i class="fa-solid fa-gauge-high" aria-hidden="true"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            <div class="nav-group" data-nav-group="website">
                <button type="button" class="nav-group-toggle" aria-expanded="true">
                    <span>Website</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="nav-group-items">
                    <a href="<?= e($adminBase) ?>/pages.php" class="nav-item<?= nav_active('pages', $activeNav) ?>" title="Pages">
                        <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
                        <span>Pages</span>
                    </a>
                    <a href="<?= e($adminBase) ?>/gallery.php" class="nav-item<?= nav_active('gallery', $activeNav) ?>" title="Gallery">
                        <i class="fa-solid fa-images" aria-hidden="true"></i>
                        <span>Gallery</span>
                    </a>
                </div>
            </div>

            <div class="nav-group" data-nav-group="mail">
                <button type="button" class="nav-group-toggle" aria-expanded="true">
                    <span>Mail</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="nav-group-items">
                    <a href="<?= e($adminBase) ?>/emails.php" class="nav-item<?= nav_active('emails', $activeNav) ?>" title="Emails">
                        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                        <span>Emails</span>
                    </a>
                </div>
            </div>

            <div class="nav-group" data-nav-group="account">
                <button type="button" class="nav-group-toggle" aria-expanded="true">
                    <span>Account</span>
                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="nav-group-items">
                    <a href="<?= e($adminBase) ?>/settings.php" class="nav-item<?= nav_active('settings', $activeNav) ?>" title="Settings">
                        <i class="fa-solid fa-gear" aria-hidden="true"></i>
                        <span>Settings</span>
                    </a>
                    <a href="<?= e($adminBase) ?>/logout.php" class="nav-item" title="Sign out">
                        <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-user-profile" title="<?= e($adminName) ?>">
                    <span class="sidebar-user-avatar" aria-hidden="true"><?= e($adminInitials) ?></span>
                    <span class="sidebar-user-meta">
                        <span class="sidebar-user-name"><?= e($adminName !== '' ? $adminName : $adminUsername) ?></span>
                        <span class="sidebar-user-role">Administrator</span>
                    </span>
                </div>
                <a href="<?= e($adminBase) ?>/logout.php" class="sidebar-user-signout" title="Sign out" aria-label="Sign out">
                    <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        <button type="button" class="sidebar-collapse" id="sidebar-collapse" aria-label="Collapse sidebar" aria-expanded="true" title="Collapse sidebar">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
    </aside>

    <div class="admin-main">
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="menu-toggle" id="menu-toggle" aria-label="Open menu">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <div class="topbar-titles">
                    <h1 class="topbar-title"><?= e($pageTitle) ?></h1>
                    <?php if ($pageSubtitle !== ''): ?>
                        <p class="topbar-sub"><?= e($pageSubtitle) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="topbar-right">
                <div class="profile-menu" data-dropdown>
                    <button type="button" class="profile-trigger" data-dropdown-toggle aria-expanded="false" aria-haspopup="true">
                        <span class="profile-avatar"><?= e($adminInitials) ?></span>
                        <span class="profile-meta">
                            <span class="profile-name"><?= e($adminName !== '' ? $adminName : $adminUsername) ?></span>
                            <span class="profile-role">Administrator</span>
                        </span>
                        <i class="fa-solid fa-chevron-down profile-caret" aria-hidden="true"></i>
                    </button>
                    <div class="dropdown-panel profile-dropdown" data-dropdown-panel hidden>
                        <div class="dropdown-head">
                            <strong><?= e($adminName !== '' ? $adminName : $adminUsername) ?></strong>
                            <span>@<?= e($adminUsername) ?></span>
                        </div>
                        <a href="<?= e($adminBase) ?>/settings.php"><i class="fa-solid fa-gear" aria-hidden="true"></i> Settings</a>
                        <a href="<?= e($adminBase) ?>/settings.php#password"><i class="fa-solid fa-key" aria-hidden="true"></i> Change password</a>
                        <hr>
                        <a href="/" target="_blank" rel="noopener"><i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> View website</a>
                        <hr>
                        <a href="<?= e($adminBase) ?>/logout.php" class="danger"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Sign out</a>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            <?php if ($flash !== null): ?>
                <?php
                $flashType = (string) ($flash['type'] ?? 'info');
                $alertClass = $flashType === 'success' ? 'alert-success' : ($flashType === 'error' ? 'alert-error' : 'alert-info');
                $iconClass = $flashType === 'success'
                    ? 'fa-circle-check'
                    : ($flashType === 'error' ? 'fa-circle-exclamation' : 'fa-circle-info');
                ?>
                <div class="alert <?= e($alertClass) ?>" role="status">
                    <i class="fa-solid <?= e($iconClass) ?>" aria-hidden="true"></i>
                    <span><?= e((string) ($flash['message'] ?? '')) ?></span>
                    <button type="button" class="alert-close" aria-label="Dismiss" onclick="this.parentElement.remove()">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>
            <?php endif; ?>
