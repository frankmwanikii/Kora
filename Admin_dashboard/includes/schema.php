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

    try {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn();
    } catch (Throwable) {
        return true;
    }

    return $count === 0;
}

function kora_is_installed(?PDO $pdo): bool
{
    return $pdo instanceof PDO && !kora_needs_setup($pdo);
}

function kora_install_schema(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS admin_users (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            username VARCHAR(80) NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            name VARCHAR(160) NOT NULL,
            email VARCHAR(190) NOT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_admin_users_username (username)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS media_library (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            filename VARCHAR(255) NOT NULL,
            original_name VARCHAR(255) NOT NULL,
            mime_type VARCHAR(120) NOT NULL,
            file_size INT UNSIGNED NOT NULL,
            path VARCHAR(500) NOT NULL,
            folder VARCHAR(255) NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uq_media_library_path (path)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS cms_sections (
            slug VARCHAR(80) NOT NULL,
            title VARCHAR(190) NOT NULL,
            content_json LONGTEXT NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY (slug)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS settings (
            `key` VARCHAR(120) NOT NULL,
            `value` LONGTEXT NOT NULL,
            PRIMARY KEY (`key`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS activity_log (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            admin_id INT UNSIGNED NULL,
            action VARCHAR(120) NOT NULL,
            entity_type VARCHAR(80) NULL,
            entity_id VARCHAR(80) NULL,
            details LONGTEXT NULL,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_activity_log_admin_id (admin_id),
            CONSTRAINT fk_activity_log_admin
                FOREIGN KEY (admin_id) REFERENCES admin_users(id)
                ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
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
        file_put_contents($lockPath, date('c') . PHP_EOL);
    }
}
