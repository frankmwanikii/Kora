<?php

declare(strict_types=1);

require_once __DIR__ . '/cms.php';

$_kora_cms = cms_settings();

define('SITE_NAME', (string) ($_kora_cms['site_name'] ?? 'KORA'));
define('SITE_TAGLINE', (string) ($_kora_cms['site_tagline'] ?? 'Custom Awards & Trophies'));
define('SITE_LOCATION', (string) ($_kora_cms['site_location'] ?? 'Made in-house in Laikipia'));
define('SITE_ADDRESS', (string) ($_kora_cms['site_address'] ?? 'Laikipia, Nanyuki, Kenya'));
define('SITE_PHONE', (string) ($_kora_cms['site_phone'] ?? '0790355707'));
define('SITE_PHONE_LINK', (string) ($_kora_cms['site_phone_link'] ?? '+254790355707'));
define('SITE_EMAIL', (string) ($_kora_cms['site_email'] ?? 'koradesignprint@gmail.com'));
define('SITE_URL', (string) ($_kora_cms['site_url'] ?? 'https://kora.fraittech.co.ke'));
define('SITE_YEAR', (string) ($_kora_cms['site_year'] ?? '2026'));
define('SITE_INSTAGRAM', (string) ($_kora_cms['site_instagram'] ?? 'https://www.instagram.com/koralasercraft'));
define('SITE_TIKTOK', (string) ($_kora_cms['site_tiktok'] ?? 'https://www.tiktok.com/@koralasercraft'));
define('SITE_FACEBOOK', (string) ($_kora_cms['site_facebook'] ?? 'https://www.facebook.com/'));
define('SITE_YOUTUBE', (string) ($_kora_cms['site_youtube'] ?? 'https://www.youtube.com/'));
define('SITE_WHATSAPP', (string) ($_kora_cms['whatsapp_url'] ?? 'https://wa.me/254790355707'));
define('SITE_HOURS', (string) ($_kora_cms['business_hours'] ?? 'Mon–Sat, 8am–6pm'));
define('SITE_FOOTER_TAGLINE', (string) ($_kora_cms['site_tagline_footer'] ?? 'Recognition Made Personal'));

unset($_kora_cms);

require_once __DIR__ . '/brand-logo.php';

function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

function img(string $filename): string
{
    $parts = explode('/', ltrim($filename, '/'));
    $encoded = implode('/', array_map('rawurlencode', $parts));

    return asset('assets/images/' . $encoded);
}

function page_title(string $page = ''): string
{
    if ($page === '') {
        return SITE_NAME . ' | Custom Awards, Medals & Souvenirs';
    }

    return $page . ' | ' . SITE_NAME;
}
