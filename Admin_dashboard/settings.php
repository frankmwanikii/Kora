<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/editor.php';

$pageTitle = 'Settings';
$pageSubtitle = 'Site details & account';
$activeNav = 'settings';

$settings = kora_load_settings($pdo);
$defaults = kora_default_settings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid session. Please try again.');
        redirect('/settings.php');
    }

    $updated = [];
    foreach (array_keys($defaults) as $key) {
        $updated[$key] = trim((string) ($_POST['settings'][$key] ?? ($settings[$key] ?? '')));
    }

    kora_save_settings($pdo, $updated);

    $newPassword = (string) ($_POST['new_password'] ?? '');
    $confirmPassword = (string) ($_POST['new_password_confirm'] ?? '');

    if ($newPassword !== '' || $confirmPassword !== '') {
        if (mb_strlen($newPassword) < 8) {
            flash('error', 'New password must be at least 8 characters.');
            redirect('/settings.php');
        }
        if ($newPassword !== $confirmPassword) {
            flash('error', 'New passwords do not match.');
            redirect('/settings.php');
        }

        $adminId = Auth::id();
        if ($adminId !== null) {
            $stmt = $pdo->prepare('UPDATE admin_users SET password_hash = :hash WHERE id = :id');
            $stmt->execute([
                'hash' => password_hash($newPassword, PASSWORD_DEFAULT),
                'id' => $adminId,
            ]);
        }
    }

    try {
        kora_export_site($pdo);
        flash('success', 'Settings saved. The live website now uses these details.');
    } catch (Throwable $e) {
        flash('error', 'Settings saved, but the live site export failed: ' . $e->getMessage());
    }
    redirect('/settings.php');
}

$settings = kora_load_settings($pdo);

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Global configuration</p>
            <h2 class="pages-hub__title">Site settings</h2>
            <p class="pages-hub__lead">Contact details, social links, and metadata used across the public website.</p>
        </div>
    </header>

    <form method="post">
        <?= csrf_field() ?>

        <section class="panel">
            <div class="panel__head"><h2 class="panel__title">Website details</h2></div>
            <div class="panel__body" style="display:grid;gap:.85rem">
                <?php foreach ($defaults as $key => $defaultValue): ?>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="setting-<?= e($key) ?>"><?= e(ucwords(str_replace('_', ' ', $key))) ?></label>
                        <?php if (kora_is_long_text_key($key, (string) ($settings[$key] ?? $defaultValue))): ?>
                            <textarea class="form-control" id="setting-<?= e($key) ?>" name="settings[<?= e($key) ?>]" rows="3"><?= e((string) ($settings[$key] ?? $defaultValue)) ?></textarea>
                        <?php else: ?>
                            <input class="form-control" type="text" id="setting-<?= e($key) ?>" name="settings[<?= e($key) ?>]" value="<?= e((string) ($settings[$key] ?? $defaultValue)) ?>">
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="panel" id="password" style="margin-top:1.25rem">
            <div class="panel__head"><h2 class="panel__title">Change password</h2></div>
            <div class="panel__body" style="display:grid;gap:.85rem;max-width:480px">
                <p class="muted" style="font-size:.875rem;margin:0">Leave blank to keep your current password.</p>
                <div class="form-group" style="margin:0">
                    <label class="form-label" for="new_password">New password</label>
                    <input class="form-control" type="password" id="new_password" name="new_password" autocomplete="new-password" minlength="8">
                </div>
                <div class="form-group" style="margin:0">
                    <label class="form-label" for="new_password_confirm">Confirm new password</label>
                    <input class="form-control" type="password" id="new_password_confirm" name="new_password_confirm" autocomplete="new-password" minlength="8">
                </div>
            </div>
        </section>

        <div class="save-bar">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save settings
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
