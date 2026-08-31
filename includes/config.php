<?php

declare(strict_types=1);

const SITE_NAME = 'KORA';
const SITE_TAGLINE = 'Custom Awards & Trophies';
const SITE_LOCATION = 'Made in-house in Laikipia';
const SITE_ADDRESS = 'Laikipia, Nanyuki, Kenya';
const SITE_PHONE = '0790355707';
const SITE_PHONE_LINK = '+254790355707';
const SITE_EMAIL = 'koradesignprint@gmail.com';
const SITE_URL = 'https://kora.fraittech.co.ke';
const SITE_YEAR = '2026';

function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

function img(string $filename): string
{
    return asset('assets/images/' . ltrim($filename, '/'));
}

function page_title(string $page = ''): string
{
    if ($page === '') {
        return SITE_NAME . ' | Custom Awards, Medals & Souvenirs';
    }

    return $page . ' | ' . SITE_NAME;
}
