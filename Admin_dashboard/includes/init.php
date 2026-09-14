<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/schema.php';
require_once __DIR__ . '/content.php';
require_once __DIR__ . '/gallery.php';

Auth::startSession();
date_default_timezone_set('Africa/Nairobi');

$script = basename($_SERVER['SCRIPT_FILENAME'] ?? 'index.php');
$publicScripts = ['index.php', 'setup.php', 'logout.php'];
$isPublicScript = in_array($script, $publicScripts, true);

$pdo = null;
$dbConnectError = '';

if (Database::isConfigured()) {
    try {
        $pdo = Database::connect();
        kora_install_schema($pdo);
    } catch (Throwable $e) {
        $pdo = null;
        $dbConnectError = Database::friendlyError($e->getMessage(), false, Database::isConfigured() ? Database::config() : null);
    }
}

if ($script !== 'setup.php') {
    if (!Database::isConfigured() || $pdo === null || kora_needs_setup($pdo)) {
        redirect('/setup.php');
    }
}

if (!$isPublicScript) {
    Auth::requireLogin();
}
