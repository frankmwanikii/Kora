<?php

declare(strict_types=1);

/**
 * Absolute URL helper for SEO tags and sitemaps.
 */
function seo_url(string $path = '/'): string
{
    $base = rtrim(SITE_URL, '/');
    $path = trim($path);

    if ($path === '' || $path === '/') {
        return $base . '/';
    }

    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return $base . '/' . ltrim($path, '/');
}

/**
 * Build a clean public path (extensionless, no query/fragment).
 */
function seo_clean_path(string $path): string
{
    $path = trim($path);

    if ($path === '' || $path === '/') {
        return '/';
    }

    $parts = parse_url($path);
    $pathOnly = (string) ($parts['path'] ?? '/');

    if ($pathOnly === '' || $pathOnly === '/') {
        return '/';
    }

    if (str_ends_with(strtolower($pathOnly), '.php')) {
        $pathOnly = substr($pathOnly, 0, -4);
    }

    $pathOnly = '/' . ltrim($pathOnly, '/');

    return $pathOnly === '/index' ? '/' : $pathOnly;
}

/**
 * Canonical URL for the current request (extensionless, no query string).
 */
function seo_canonical_url(?string $override = null): string
{
    if ($override !== null && $override !== '') {
        return seo_url(seo_clean_path($override));
    }

    $requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = seo_clean_path($requestUri);

    return seo_url($path);
}

/**
 * Absolute image URL without cache-busting query params (better for sitemaps / OG).
 */
function seo_image_url(string $filename): string
{
    $filename = trim($filename);

    if ($filename === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $filename) || str_starts_with($filename, '//')) {
        return $filename;
    }

    if (str_starts_with($filename, '/assets/')) {
        return seo_url($filename);
    }

    if (str_starts_with($filename, 'assets/images/')) {
        $filename = substr($filename, strlen('assets/images/'));
    }

    if (str_starts_with($filename, '/')) {
        return seo_url($filename);
    }

    $parts = explode('/', ltrim($filename, '/'));
    $encoded = implode('/', array_map('rawurlencode', $parts));

    return seo_url('assets/images/' . $encoded);
}

/**
 * Default social / share image for the site.
 */
function seo_default_image(): string
{
    return seo_image_url('awards/Award_hero.webp');
}

/**
 * @param array<string, mixed> $data
 */
function seo_json_ld(array $data): string
{
    $json = json_encode(
        $data,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS
    );

    return is_string($json) ? $json : '{}';
}

/**
 * Emit one or more JSON-LD script blocks.
 *
 * @param list<array<string, mixed>>|array<string, mixed> $schemas
 */
function seo_render_json_ld(array $schemas): void
{
    if ($schemas === []) {
        return;
    }

    // Single associative schema vs list of schemas
    $isList = array_is_list($schemas);
    $items = $isList ? $schemas : [$schemas];

    foreach ($items as $schema) {
        if (!is_array($schema) || $schema === []) {
            continue;
        }
        echo '<script type="application/ld+json">' . seo_json_ld($schema) . '</script>' . "\n";
    }
}

/**
 * Core Organization / LocalBusiness graph used site-wide.
 *
 * @return array<string, mixed>
 */
function seo_organization_schema(): array
{
    $sameAs = array_values(array_filter([
        SITE_INSTAGRAM,
        SITE_TIKTOK,
        SITE_FACEBOOK,
        SITE_YOUTUBE,
        SITE_WHATSAPP,
    ], static fn(string $url): bool => $url !== '' && !preg_match('#facebook\.com/?$#i', $url) && !preg_match('#youtube\.com/?$#i', $url)));

    return [
        '@context' => 'https://schema.org',
        '@type' => ['Organization', 'LocalBusiness'],
        '@id' => seo_url('/') . '#organization',
        'name' => SITE_NAME,
        'alternateName' => 'KORA',
        'url' => seo_url('/'),
        'logo' => seo_image_url('logos/kora_logo1.webp'),
        'image' => seo_default_image(),
        'description' => 'Custom awards, medals, plaques, and souvenirs — laser-cut and hand-finished in Nanyuki, Laikipia, Kenya.',
        'email' => SITE_EMAIL,
        'telephone' => SITE_PHONE_LINK,
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => SITE_ADDRESS,
            'addressLocality' => 'Nanyuki',
            'addressRegion' => 'Laikipia',
            'addressCountry' => 'KE',
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => -0.0166,
            'longitude' => 37.0728,
        ],
        'openingHoursSpecification' => [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            'opens' => '08:00',
            'closes' => '18:00',
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Kenya',
        ],
        'priceRange' => '$$',
        'sameAs' => $sameAs,
        'contactPoint' => [
            [
                '@type' => 'ContactPoint',
                'telephone' => SITE_PHONE_LINK,
                'contactType' => 'customer service',
                'areaServed' => 'KE',
                'availableLanguage' => ['en', 'sw'],
            ],
        ],
    ];
}

/**
 * @return array<string, mixed>
 */
function seo_website_schema(): array
{
    return [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => seo_url('/') . '#website',
        'url' => seo_url('/'),
        'name' => SITE_NAME,
        'description' => SITE_TAGLINE,
        'publisher' => ['@id' => seo_url('/') . '#organization'],
        'inLanguage' => 'en-KE',
    ];
}

/**
 * @param list<array{name: string, url: string}> $crumbs
 * @return array<string, mixed>
 */
function seo_breadcrumb_schema(array $crumbs): array
{
    $items = [];
    $position = 1;

    foreach ($crumbs as $crumb) {
        $name = (string) ($crumb['name'] ?? '');
        $url = (string) ($crumb['url'] ?? '');
        if ($name === '' || $url === '') {
            continue;
        }
        $items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $name,
            'item' => seo_url(seo_clean_path($url)),
        ];
        $position++;
    }

    return [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

/**
 * Collect image entries from a CMS-style image array.
 *
 * @param mixed $images
 * @return list<array{loc: string, title?: string, caption?: string}>
 */
function seo_collect_images(mixed $images, string $fallbackTitle = ''): array
{
    if (!is_array($images)) {
        return [];
    }

    $out = [];
    $seen = [];

    foreach ($images as $image) {
        if (!is_array($image)) {
            continue;
        }

        $file = (string) ($image['file'] ?? $image['image'] ?? '');
        if ($file === '') {
            continue;
        }

        $loc = seo_image_url($file);
        if ($loc === '' || isset($seen[$loc])) {
            continue;
        }
        $seen[$loc] = true;

        $entry = ['loc' => $loc];
        $title = (string) ($image['title'] ?? $fallbackTitle);
        $caption = (string) ($image['alt'] ?? $image['caption'] ?? '');

        if ($title !== '') {
            $entry['title'] = $title;
        }
        if ($caption !== '') {
            $entry['caption'] = $caption;
        }

        $out[] = $entry;
    }

    return $out;
}
