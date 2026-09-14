<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

if (is_file(kora_install_lock_path()) && !kora_needs_setup($pdo)) {
    redirect('/index.php');
}

$errors = [];
$values = [
    'name' => '',
    'email' => '',
    'username' => 'admin',
    'password' => '',
    'password_confirm' => '',
];

$cssPath = __DIR__ . '/assets/css/admin.css';
$cssVersion = is_file($cssPath) ? (int) filemtime($cssPath) : 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid session. Please refresh and try again.';
    } else {
        foreach (array_keys($values) as $key) {
            $values[$key] = trim((string) ($_POST[$key] ?? ''));
        }

        if ($values['name'] === '') {
            $errors[] = 'Display name is required.';
        }
        if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Enter a valid email address.';
        }
        if ($values['username'] === '' || mb_strlen($values['username']) < 3) {
            $errors[] = 'Username must be at least 3 characters.';
        }
        if (mb_strlen($values['password']) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }
        if ($values['password'] !== $values['password_confirm']) {
            $errors[] = 'Passwords do not match.';
        }

        if ($errors === []) {
            try {
                $stmt = $pdo->prepare(
                    'INSERT INTO admin_users (username, password_hash, name, email, created_at)
                     VALUES (:username, :password_hash, :name, :email, :created_at)'
                );
                $stmt->execute([
                    'username' => $values['username'],
                    'password_hash' => password_hash($values['password'], PASSWORD_DEFAULT),
                    'name' => $values['name'],
                    'email' => $values['email'],
                    'created_at' => now(),
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
<body class="admin-body" style="display:flex;align-items:flex-start;justify-content:center;min-height:100vh;padding:2rem 1rem;">
    <div style="max-width:640px;width:100%">
        <div style="margin-bottom:1.25rem">
            <h1 style="margin:0 0 .35rem;font-family:var(--font-display,serif)">Install KORA Admin</h1>
            <p style="margin:0;color:var(--gray-500)">Create your administrator account and seed website content.</p>
        </div>

        <div class="panel">
            <div class="panel__body">
                <?php foreach ($errors as $err): ?>
                    <div class="alert alert-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                        <span><?= e($err) ?></span>
                    </div>
                <?php endforeach; ?>

                <form method="post" autocomplete="off">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label class="form-label" for="name">Display name</label>
                        <input class="form-control" id="name" name="name" required value="<?= e($values['name']) ?>" placeholder="Site Admin">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" required value="<?= e($values['email']) ?>" placeholder="you@example.com">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-control" id="username" name="username" required minlength="3" value="<?= e($values['username']) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-control" id="password" name="password" type="password" required minlength="8" autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="password_confirm">Confirm password</label>
                        <input class="form-control" id="password_confirm" name="password_confirm" type="password" required minlength="8" autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary">Create admin &amp; install</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
