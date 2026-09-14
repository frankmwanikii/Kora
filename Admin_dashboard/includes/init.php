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

if ($script !== 'setup.php') {
    if (!is_file(kora_install_lock_path())) {
        redirect('/setup.php');
    }

    $pdo = Database::connect();
    kora_install_schema($pdo);

    if (kora_needs_setup($pdo)) {
        redirect('/setup.php');
    }
} else {
    $pdo = Database::connect();
    kora_install_schema($pdo);
}

if (!$isPublicScript) {
    Auth::requireLogin();
}
