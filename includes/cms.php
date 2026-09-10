<?php

declare(strict_types=1);

/**
 * Public-site CMS reader. Content is edited in Admin_dashboard and exported to data/cms/site.json.
 */
function cms_site(): array
{
    static $data = null;
    static $loadedMtime = null;

    $path = dirname(__DIR__) . '/data/cms/site.json';
    $mtime = is_file($path) ? (int) filemtime($path) : 0;

    // Re-read when the export file changes (PHP-FPM workers persist statics across requests).
    if ($data !== null && $loadedMtime === $mtime) {
        return $data;
    }

    $loadedMtime = $mtime;

    if (!is_file($path)) {
        $data = [];

        return $data;
    }

    $raw = file_get_contents($path);

    if ($raw === false || $raw === '') {
        $data = [];

        return $data;
    }

    $decoded = json_decode($raw, true);
    $data = is_array($decoded) ? $decoded : [];

    return $data;
}

function cms_settings(): array
{
    $site = cms_site();
    $settings = $site['settings'] ?? [];

    return is_array($settings) ? $settings : [];
}

function cms_setting(string $key, string $default = ''): string
{
    $settings = cms_settings();
    $value = $settings[$key] ?? $default;

    return is_scalar($value) ? (string) $value : $default;
}

/**
 * @return array<string, mixed>
 */
function cms_section(string $slug): array
{
    $site = cms_site();
    $sections = $site['sections'] ?? [];

    if (!is_array($sections) || !isset($sections[$slug]) || !is_array($sections[$slug])) {
        return [];
    }

    $content = $sections[$slug]['content'] ?? $sections[$slug];

    return is_array($content) ? $content : [];
}

/**
 * @template T
 * @param T $default
 * @return mixed|T
 */
function cms_get(string $slug, string $key, mixed $default = '')
{
    $section = cms_section($slug);

    if (!array_key_exists($key, $section)) {
        return $default;
    }

    return $section[$key];
}

function cms_text(string $slug, string $key, string $default = ''): string
{
    $value = cms_get($slug, $key, $default);

    return is_scalar($value) ? (string) $value : $default;
}

/**
 * @return list<mixed>
 */
function cms_list(string $slug, string $key, array $default = []): array
{
    $value = cms_get($slug, $key, $default);

    return is_array($value) ? array_values($value) : $default;
}
