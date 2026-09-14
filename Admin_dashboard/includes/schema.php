<?php

declare(strict_types=1);

function kora_install_lock_path(): string
{
    return dirname(__DIR__) . '/data/install.lock';
}

function kora_needs_setup(PDO $pdo): bool
{
    if (!is_file(kora_install_lock_path())) {
        return true;
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();

    return $count === 0;
}

function kora_install_schema(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS admin_users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            name TEXT NOT NULL,
            email TEXT NOT NULL,
            created_at TEXT NOT NULL
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS media_library (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            filename TEXT NOT NULL,
            original_name TEXT NOT NULL,
            mime_type TEXT NOT NULL,
            file_size INTEGER NOT NULL,
            path TEXT NOT NULL UNIQUE,
            folder TEXT,
            created_at TEXT NOT NULL
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS cms_sections (
            slug TEXT PRIMARY KEY,
            title TEXT NOT NULL,
            content_json TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS settings (
            key TEXT PRIMARY KEY,
            value TEXT NOT NULL
        )'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS activity_log (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            admin_id INTEGER,
            action TEXT NOT NULL,
            entity_type TEXT,
            entity_id TEXT,
            details TEXT,
            created_at TEXT NOT NULL,
            FOREIGN KEY (admin_id) REFERENCES admin_users(id) ON DELETE SET NULL
        )'
    );
}

function kora_mark_installed(): void
{
    $lockPath = kora_install_lock_path();
    $dir = dirname($lockPath);

    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Unable to create install lock directory.');
    }

    if (!is_file($lockPath)) {
        file_put_contents($lockPath, now() . PHP_EOL);
    }
}
