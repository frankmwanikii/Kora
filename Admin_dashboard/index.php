<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

if (Auth::check()) {
    redirect('/dashboard.php');
}

$error = '';
$username = '';
$cssPath = __DIR__ . '/assets/css/admin.css';
$cssVersion = is_file($cssPath) ? (int) filemtime($cssPath) : 1;
$jsPath = __DIR__ . '/assets/js/admin.js';
$jsVersion = is_file($jsPath) ? (int) filemtime($jsPath) : 1;
$logoPath = site_root() . '/assets/images/logos/kora_logo_white.webp';
$logoUrl = '/assets/images/logos/kora_logo_white.webp' . (is_file($logoPath) ? '?v=' . (int) filemtime($logoPath) : '');
if (!is_file($logoPath)) {
    $logoPath = site_root() . '/assets/images/logos/kora_logo1.webp';
    $logoUrl = '/assets/images/logos/kora_logo1.webp' . (is_file($logoPath) ? '?v=' . (int) filemtime($logoPath) : '');
}
$year = date('Y');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid session. Please refresh and try again.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($username === '' || mb_strlen($username) < 3) {
            $error = 'Please enter a valid username (at least 3 characters).';
        } elseif ($password === '') {
            $error = 'Please enter your password.';
        } elseif (!Auth::loginAdmin($pdo, $username, $password)) {
            $error = 'Invalid username or password.';
        } else {
            redirect('/dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in · KORA Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= e(admin_base_path()) ?>/assets/css/admin.css?v=<?= $cssVersion ?>">
</head>
<body class="login-body">
    <div id="login-screen" class="login-screen--dark">
        <div class="login-glow login-glow--left" aria-hidden="true"></div>
        <div class="login-glow login-glow--right" aria-hidden="true"></div>

        <div class="login-stage">
            <div class="login-brand-panel">
                <div class="login-brand-inner">
                    <img src="<?= e($logoUrl) ?>" alt="KORA Laser Craft" class="login-logo" width="720" height="720">
                </div>
            </div>

            <div class="login-separator" aria-hidden="true"></div>

            <div class="login-form-panel">
                <div class="login-form-wrap">
                    <div class="login-form-header">
                        <h1>Welcome</h1>
                        <p>Please login to admin dashboard.</p>
                    </div>

                    <?php if ($error !== ''): ?>
                        <div class="login-alert" role="alert">
                            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                            <span><?= e($error) ?></span>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= e(admin_base_path()) ?>/index.php" id="login-form" class="login-form" autocomplete="on">
                        <?= csrf_field() ?>

                        <div class="form-group">
                            <label class="visually-hidden" for="username">Username</label>
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control login-input"
                                autocomplete="username"
                                required
                                minlength="3"
                                maxlength="80"
                                value="<?= e($username) ?>"
                                placeholder="USERNAME"
                                autofocus
                            >
                        </div>

                        <div class="form-group">
                            <label class="visually-hidden" for="password">Password</label>
                            <div class="password-field-wrap">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control login-input"
                                    autocomplete="current-password"
                                    required
                                    minlength="8"
                                    placeholder="PASSWORD"
                                >
                                <button type="button" class="password-toggle-btn" data-password-toggle="password" aria-label="Show password">
                                    <i class="fa-solid fa-eye" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

                        <div class="login-form-actions">
                            <button type="submit" class="login-submit">Login</button>
                        </div>
                    </form>

                    <p class="login-staff-note">
                        <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
                        Authorised staff only
                    </p>
                </div>
            </div>
        </div>

        <p class="login-copyright">Copyright KORA © <?= e($year) ?></p>
    </div>
    <script src="<?= e(admin_base_path()) ?>/assets/js/admin.js?v=<?= $jsVersion ?>" defer></script>
</body>
</html>
