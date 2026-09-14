<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

if (kora_is_installed($pdo)) {
    redirect('/index.php');
}

$errors = [];
$dbValues = Database::formDefaults();
$dbValues['create_database'] = true;

$adminValues = [
    'name' => '',
    'email' => '',
    'username' => 'admin',
    'password' => '',
    'password_confirm' => '',
];

$existingPassword = null;
try {
    $existingPassword = Database::config()['password'];
} catch (Throwable) {
    $existingPassword = null;
}

if ($dbConnectError !== '') {
    $errors[] = 'Could not use the saved database settings: ' . $dbConnectError;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid session. Please refresh and try again.';
    } else {
        $action = (string) ($_POST['action'] ?? '');

        if ($action === 'save_database') {
            $dbValues['host'] = trim((string) ($_POST['host'] ?? ''));
            $dbValues['port'] = (int) ($_POST['port'] ?? 3306);
            $dbValues['database'] = trim((string) ($_POST['database'] ?? ''));
            $dbValues['username'] = trim((string) ($_POST['username'] ?? ''));
            $dbValues['password'] = (string) ($_POST['password'] ?? '');
            $dbValues['create_database'] = isset($_POST['create_database']);

            $result = Database::provision(
                [
                    'host' => $dbValues['host'],
                    'port' => $dbValues['port'],
                    'database' => $dbValues['database'],
                    'username' => $dbValues['username'],
                    'password' => $dbValues['password'],
                    'create_database' => $dbValues['create_database'],
                ],
                $existingPassword
            );

            if (!$result['ok']) {
                $errors[] = (string) ($result['error'] ?? 'Could not save database settings.');
            } else {
                $pdo = $result['pdo'] ?? Database::connect();
                kora_install_schema($pdo);
                flash('success', 'Database connected. Create your administrator account next.');
                redirect('/setup.php');
            }
        }

        if ($action === 'create_admin') {
            if ($pdo === null) {
                $errors[] = 'Connect the database first.';
            } else {
                foreach (array_keys($adminValues) as $key) {
                    $adminValues[$key] = trim((string) ($_POST[$key] ?? ''));
                }

                if ($adminValues['name'] === '') {
                    $errors[] = 'Display name is required.';
                }
                if (!filter_var($adminValues['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Enter a valid email address.';
                }
                if ($adminValues['username'] === '' || mb_strlen($adminValues['username']) < 3) {
                    $errors[] = 'Username must be at least 3 characters.';
                }
                if (mb_strlen($adminValues['password']) < 8) {
                    $errors[] = 'Password must be at least 8 characters.';
                }
                if ($adminValues['password'] !== $adminValues['password_confirm']) {
                    $errors[] = 'Passwords do not match.';
                }

                if ($errors === []) {
                    try {
                        $stmt = $pdo->prepare(
                            'INSERT INTO admin_users (username, password_hash, name, email, created_at)
                             VALUES (:username, :password_hash, :name, :email, :created_at)'
                        );
                        $stmt->execute([
                            'username' => $adminValues['username'],
                            'password_hash' => password_hash($adminValues['password'], PASSWORD_DEFAULT),
                            'name' => $adminValues['name'],
                            'email' => $adminValues['email'],
                            'created_at' => date('Y-m-d H:i:s'),
                        ]);

                        kora_seed_all($pdo);
                        kora_mark_installed();
                        flash('success', 'Setup complete. Sign in with your new admin account.');
                        redirect('/index.php');
                    } catch (Throwable $e) {
                        $errors[] = 'Setup failed: ' . $e->getMessage();
                    }
                }
            }
        }
    }
}

$step = ($pdo instanceof PDO) ? 2 : 1;
$cssPath = __DIR__ . '/assets/css/admin.css';
$cssVersion = is_file($cssPath) ? (int) filemtime($cssPath) : 1;
$jsPath = __DIR__ . '/assets/js/admin.js';
$jsVersion = is_file($jsPath) ? (int) filemtime($jsPath) : 1;
$flash = function_exists('flash_take') ? flash_take() : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup · KORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= e(admin_base_path()) ?>/assets/css/admin.css?v=<?= $cssVersion ?>">
</head>
<body class="admin-body setup-body">
    <div class="setup-shell">
        <header class="setup-hero">
            <p class="setup-kicker">First-time install</p>
            <h1>Set up KORA Admin</h1>
            <p>Enter MySQL details, then create your admin login. The site is ready as soon as you finish.</p>
            <ol class="setup-steps" aria-label="Setup progress">
                <li class="<?= $step >= 1 ? 'is-current' : '' ?><?= $step > 1 ? ' is-done' : '' ?>">
                    <span>1</span> Database
                </li>
                <li class="<?= $step >= 2 ? 'is-current' : '' ?>">
                    <span>2</span> Admin account
                </li>
            </ol>
        </header>

        <?php if (is_array($flash)): ?>
            <?php
            $flashType = (string) ($flash['type'] ?? 'info');
            $alertClass = $flashType === 'success' ? 'alert-success' : ($flashType === 'error' ? 'alert-error' : 'alert-info');
            ?>
            <div class="alert <?= e($alertClass) ?>" role="status">
                <i class="fa-solid <?= $flashType === 'success' ? 'fa-circle-check' : 'fa-circle-info' ?>" aria-hidden="true"></i>
                <span><?= e((string) ($flash['message'] ?? '')) ?></span>
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $err): ?>
            <div class="alert alert-error" role="alert">
                <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                <span><?= e($err) ?></span>
            </div>
        <?php endforeach; ?>

        <?php if ($step === 1): ?>
            <section class="panel">
                <div class="panel__head"><h2 class="panel__title">MySQL database</h2></div>
                <div class="panel__body">
                    <p class="muted" style="margin:0 0 1rem;font-size:.875rem">
                        Use an existing MySQL user. Tick the box below if the database has not been created yet.
                        Credentials are saved to <code>data/db.local.php</code> (not published on the website).
                    </p>
                    <form method="post" autocomplete="off" class="setup-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="save_database">

                        <div class="setup-grid">
                            <div class="form-group" style="margin:0">
                                <label class="form-label" for="host">Host</label>
                                <input class="form-control" id="host" name="host" required value="<?= e((string) $dbValues['host']) ?>" placeholder="127.0.0.1">
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label" for="port">Port</label>
                                <input class="form-control" id="port" name="port" type="number" min="1" max="65535" required value="<?= e((string) $dbValues['port']) ?>">
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label" for="database">Database name</label>
                                <input class="form-control" id="database" name="database" required value="<?= e((string) $dbValues['database']) ?>" placeholder="kora_admin" pattern="[A-Za-z0-9_]+">
                            </div>
                            <div class="form-group" style="margin:0">
                                <label class="form-label" for="db_username">Username</label>
                                <input class="form-control" id="db_username" name="username" required value="<?= e((string) $dbValues['username']) ?>" autocomplete="off">
                            </div>
                            <div class="form-group" style="margin:0;grid-column:1 / -1">
                                <label class="form-label" for="db_password">Password</label>
                                <div class="password-field-wrap">
                                    <input
                                        class="form-control"
                                        id="db_password"
                                        name="password"
                                        type="password"
                                        value=""
                                        autocomplete="new-password"
                                        placeholder="<?= !empty($dbValues['password_set']) ? '••••••••  (leave blank to keep current)' : 'MySQL password' ?>"
                                    >
                                    <button type="button" class="password-toggle-btn" data-password-toggle="db_password" aria-label="Show password">
                                        <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <label class="form-check" style="display:flex;gap:.6rem;align-items:flex-start;margin:1rem 0 0;cursor:pointer">
                            <input type="checkbox" name="create_database" value="1"<?= !empty($dbValues['create_database']) ? ' checked' : '' ?> style="margin-top:.2rem">
                            <span>
                                <strong style="display:block;font-size:.9rem">Create database if it does not exist</strong>
                                <span class="muted" style="font-size:.8rem">Requires CREATE privilege on this MySQL user.</span>
                            </span>
                        </label>

                        <div style="margin-top:1.25rem">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plug" aria-hidden="true"></i> Save &amp; connect
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        <?php else: ?>
            <section class="panel">
                <div class="panel__head"><h2 class="panel__title">Administrator account</h2></div>
                <div class="panel__body">
                    <p class="muted" style="margin:0 0 1rem;font-size:.875rem">
                        This login is for <code>/Admin_dashboard/</code>. Website content is seeded automatically after you create it.
                    </p>
                    <form method="post" autocomplete="off" class="setup-form">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="create_admin">

                        <div class="form-group">
                            <label class="form-label" for="name">Display name</label>
                            <input class="form-control" id="name" name="name" required value="<?= e($adminValues['name']) ?>" placeholder="Site Admin">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="email">Email</label>
                            <input class="form-control" id="email" name="email" type="email" required value="<?= e($adminValues['email']) ?>" placeholder="you@example.com">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" id="username" name="username" required minlength="3" value="<?= e($adminValues['username']) ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="password-field-wrap">
                                <input class="form-control" id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
                                <button type="button" class="password-toggle-btn" data-password-toggle="password" aria-label="Show password">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="password_confirm">Confirm password</label>
                            <div class="password-field-wrap">
                                <input class="form-control" id="password_confirm" name="password_confirm" type="password" required minlength="8" autocomplete="new-password">
                                <button type="button" class="password-toggle-btn" data-password-toggle="password_confirm" aria-label="Show password">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Create admin &amp; finish
                        </button>
                    </form>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <script src="<?= e(admin_base_path()) ?>/assets/js/admin.js?v=<?= $jsVersion ?>" defer></script>
</body>
</html>
