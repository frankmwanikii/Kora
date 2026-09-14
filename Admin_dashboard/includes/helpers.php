<?php

declare(strict_types=1);

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function admin_base_path(): string
{
    static $cached = null;

    if ($cached !== null) {
        return $cached;
    }

    $adminFolder = basename(dirname(__DIR__));
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');

    if ($script !== '') {
        $needle = '/' . $adminFolder;
        $pos = strpos($script, $needle);

        if ($pos !== false) {
            $cached = substr($script, 0, $pos + strlen($needle));

            return $cached;
        }

        $dir = rtrim(dirname($script), '/');
        $cached = ($dir === '' || $dir === '.') ? '/' . $adminFolder : $dir;

        return $cached;
    }

    $cached = '/' . $adminFolder;

    return $cached;
}

function site_root(): string
{
    return dirname(dirname(__DIR__));
}

/**
 * Find an existing image on disk when the stored extension does not match
 * (e.g. medals/hero.jpeg vs medals/hero.webp).
 */
function kora_admin_existing_image_rel(string $rel): ?string
{
    $rel = ltrim(str_replace('\\', '/', $rel), '/');
    if ($rel === '' || str_contains($rel, '..')) {
        return null;
    }

    $root = site_root() . '/assets/images';
    if (is_file($root . '/' . $rel)) {
        return $rel;
    }

    $info = pathinfo($rel);
    $dir = (string) ($info['dirname'] ?? '');
    $base = (string) ($info['filename'] ?? '');
    if ($base === '') {
        return null;
    }

    $prefix = ($dir !== '' && $dir !== '.') ? $dir . '/' : '';
    foreach (['webp', 'jpg', 'jpeg', 'png', 'gif'] as $ext) {
        $candidate = $prefix . $base . '.' . $ext;
        if (is_file($root . '/' . $candidate)) {
            return $candidate;
        }
    }

    return null;
}

function kora_admin_image_preview_url(string $value): string
{
    $value = trim($value);
    if ($value === '') {
        return '';
    }

    if (preg_match('#^https?://#i', $value) || str_starts_with($value, '//')) {
        return $value;
    }

    if (str_starts_with($value, '/assets/')) {
        return $value;
    }

    if (str_starts_with($value, 'assets/images/')) {
        $value = substr($value, strlen('assets/images/'));
    }

    $rel = kora_admin_existing_image_rel($value) ?? ltrim($value, '/');
    $parts = explode('/', $rel);
    $encoded = implode('/', array_map('rawurlencode', $parts));

    return '/assets/images/' . $encoded;
}

function redirect(string $path): never
{
    $path = '/' . ltrim(str_replace('\\', '/', $path), '/');
    $base = rtrim(admin_base_path(), '/');
    $location = ($base === '' ? '' : $base) . $path;

    header('Location: ' . $location);
    exit;
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        if (class_exists(Auth::class, false)) {
            Auth::startSession();
        } elseif (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        // Avoid fatalling mid-page (e.g. login form); verification will fail safely.
        return bin2hex(random_bytes(32));
    }

    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(?string $token): bool
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        return false;
    }

    $sessionToken = $_SESSION['_csrf_token'] ?? '';

    if ($token === null || $token === '') {
        $token = $_POST['csrf_token'] ?? null;
    }

    if ($token === null || $token === '' || $sessionToken === '') {
        return false;
    }

    return hash_equals((string) $sessionToken, (string) $token);
}

function flash(string $type, string $msg): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        throw new RuntimeException('Session must be active before setting flash message.');
    }

    $_SESSION['_flash'] = ['type' => $type, 'message' => $msg];
}

function flash_take(): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE || !isset($_SESSION['_flash'])) {
        return null;
    }

    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);

    return is_array($flash) ? $flash : null;
}

function format_bytes(int $bytes): string
{
    if ($bytes < 0) {
        $bytes = 0;
    }

    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $value = (float) $bytes;
    $unit = 0;

    while ($value >= 1024 && $unit < count($units) - 1) {
        $value /= 1024;
        $unit++;
    }

    if ($unit === 0) {
        return (int) $value . ' ' . $units[$unit];
    }

    return number_format($value, $value >= 10 ? 0 : 1) . ' ' . $units[$unit];
}

function now(): string
{
    return (new DateTimeImmutable('now', new DateTimeZone('Africa/Nairobi')))->format('Y-m-d H:i:s');
}

function nav_active(string $key, ?string $activeNav): string
{
    return $key === $activeNav ? ' active' : '';
}

function initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];

    if ($parts === []) {
        return 'K';
    }

    if (count($parts) === 1) {
        return strtoupper(substr($parts[0], 0, 2));
    }

    return strtoupper(substr($parts[0], 0, 1) . substr($parts[count($parts) - 1], 0, 1));
}
