<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/editor.php';
require_once __DIR__ . '/includes/mail-settings.php';
require_once __DIR__ . '/includes/cpanel-email.php';

$pageTitle = 'Settings';
$pageSubtitle = 'Site details, email & account';
$activeNav = 'settings';

$settings = kora_load_settings($pdo);
$defaults = kora_default_settings();
$mailSettings = kora_load_mail_settings();
$cpanelSettings = kora_load_cpanel_settings();

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

    $mailResult = kora_save_mail_settings(
        [
            'host' => $_POST['smtp']['host'] ?? '',
            'port' => $_POST['smtp']['port'] ?? 465,
            'encryption' => $_POST['smtp']['encryption'] ?? 'ssl',
            'username' => $_POST['smtp']['username'] ?? '',
            'password' => $_POST['smtp']['password'] ?? '',
            'from_email' => $_POST['smtp']['from_email'] ?? '',
            'from_name' => $_POST['smtp']['from_name'] ?? '',
            'timeout' => $_POST['smtp']['timeout'] ?? 30,
            'keep_local_fallback' => isset($_POST['smtp']['keep_local_fallback']),
        ],
        (string) ($mailSettings['password'] ?? '')
    );

    if (!$mailResult['ok']) {
        flash('error', (string) ($mailResult['error'] ?? 'Could not save SMTP settings.'));
        redirect('/settings.php');
    }

    $cpanelResult = kora_save_cpanel_settings(
        [
            'host' => $_POST['cpanel']['host'] ?? '',
            'port' => $_POST['cpanel']['port'] ?? 2083,
            'username' => $_POST['cpanel']['username'] ?? '',
            'token' => $_POST['cpanel']['token'] ?? '',
            'domain' => $_POST['cpanel']['domain'] ?? '',
            'default_quota_mb' => $_POST['cpanel']['default_quota_mb'] ?? 1024,
            'verify_ssl' => isset($_POST['cpanel']['verify_ssl']),
        ],
        (string) ($cpanelSettings['token'] ?? '')
    );

    if (!$cpanelResult['ok']) {
        flash('error', (string) ($cpanelResult['error'] ?? 'Could not save cPanel settings.'));
        redirect('/settings.php#cpanel');
    }

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
        flash('success', 'Settings saved. Website, SMTP, and cPanel credentials are up to date.');
    } catch (Throwable $e) {
        flash('error', 'Settings saved, but the live site export failed: ' . $e->getMessage());
    }
    redirect('/settings.php');
}

$settings = kora_load_settings($pdo);
$mailSettings = kora_load_mail_settings();
$cpanelSettings = kora_load_cpanel_settings();

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Global configuration</p>
            <h2 class="pages-hub__title">Site settings</h2>
            <p class="pages-hub__lead">Contact details, outbound email (SMTP), cPanel mailboxes, social links, and account security.</p>
        </div>
    </header>

    <form method="post" autocomplete="off">
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

        <section class="panel" id="smtp" style="margin-top:1.25rem">
            <div class="panel__head"><h2 class="panel__title">Email / SMTP</h2></div>
            <div class="panel__body" style="display:grid;gap:.85rem;max-width:640px">
                <p class="muted" style="font-size:.875rem;margin:0">
                    Credentials used to send quotation, contact, and newsletter emails.
                    Stored securely in <code>data/mail.local.php</code> (not published on the website).
                </p>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_host">SMTP host</label>
                    <input class="form-control" type="text" id="smtp_host" name="smtp[host]" value="<?= e((string) $mailSettings['host']) ?>" placeholder="smtp.hostinger.com" required autocomplete="off">
                </div>

                <div style="display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:.85rem">
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="smtp_port">Port</label>
                        <input class="form-control" type="number" id="smtp_port" name="smtp[port]" value="<?= e((string) $mailSettings['port']) ?>" min="1" max="65535" required>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="smtp_encryption">Encryption</label>
                        <select class="form-select" id="smtp_encryption" name="smtp[encryption]">
                            <?php
                            $encOptions = [
                                'ssl' => 'SSL (port 465)',
                                'tls' => 'TLS / STARTTLS (port 587)',
                                'none' => 'None',
                            ];
                            foreach ($encOptions as $value => $label):
                                $selected = ((string) $mailSettings['encryption'] === $value) ? ' selected' : '';
                                ?>
                                <option value="<?= e($value) ?>"<?= $selected ?>><?= e($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_username">SMTP username</label>
                    <input class="form-control" type="text" id="smtp_username" name="smtp[username]" value="<?= e((string) $mailSettings['username']) ?>" placeholder="info@example.com" autocomplete="off">
                </div>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_password">SMTP password</label>
                    <input
                        class="form-control"
                        type="password"
                        id="smtp_password"
                        name="smtp[password]"
                        value=""
                        placeholder="<?= !empty($mailSettings['password_set']) ? '••••••••  (leave blank to keep current)' : 'Enter SMTP password' ?>"
                        autocomplete="new-password"
                    >
                    <?php if (!empty($mailSettings['password_set'])): ?>
                        <p class="muted" style="font-size:.8rem;margin:.4rem 0 0">A password is already saved. Leave this field blank to keep it, or type a new one to replace it.</p>
                    <?php endif; ?>
                </div>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_from_email">From email</label>
                    <input class="form-control" type="email" id="smtp_from_email" name="smtp[from_email]" value="<?= e((string) $mailSettings['from_email']) ?>" placeholder="info@example.com" autocomplete="off">
                </div>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_from_name">From name</label>
                    <input class="form-control" type="text" id="smtp_from_name" name="smtp[from_name]" value="<?= e((string) $mailSettings['from_name']) ?>" placeholder="KORA" autocomplete="off">
                </div>

                <div class="form-group" style="margin:0">
                    <label class="form-label" for="smtp_timeout">Timeout (seconds)</label>
                    <input class="form-control" type="number" id="smtp_timeout" name="smtp[timeout]" value="<?= e((string) $mailSettings['timeout']) ?>" min="5" max="120">
                </div>

                <label class="form-check" style="display:flex;gap:.6rem;align-items:flex-start;margin:0;cursor:pointer">
                    <input type="checkbox" name="smtp[keep_local_fallback]" value="1"<?= !empty($mailSettings['keep_local_fallback']) ? ' checked' : '' ?> style="margin-top:.2rem">
                    <span>
                        <strong style="display:block;font-size:.9rem">Keep local Postfix fallback</strong>
                        <span class="muted" style="font-size:.8rem">If the remote SMTP host fails, try <code>127.0.0.1:25</code> on this server.</span>
                    </span>
                </label>
            </div>
        </section>

        <section class="panel" id="cpanel" style="margin-top:1.25rem">
            <div class="panel__head">
                <h2 class="panel__title">cPanel connection</h2>
                <?php if (!empty($cpanelSettings['configured'])): ?>
                    <span class="badge badge-success"><i class="fa-solid fa-plug" aria-hidden="true"></i> Ready</span>
                <?php else: ?>
                    <span class="badge badge-warn"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i> Needs setup</span>
                <?php endif; ?>
            </div>
            <div class="panel__body">
                <p class="muted emails-help">
                    Used by <a href="<?= e(admin_base_path()) ?>/emails.php">Emails</a> to create mailboxes.
                    Find the <strong>cPanel username</strong> in cPanel (top-right) or Hostinger hPanel → Hosting → Details.
                    The API token is from cPanel → Security → Manage API Tokens.
                    Stored in <code>data/cpanel.local.php</code> (not published on the website).
                </p>

                <div class="emails-grid">
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="cpanel_host">cPanel host</label>
                        <input class="form-control" type="text" id="cpanel_host" name="cpanel[host]" value="<?= e((string) $cpanelSettings['host']) ?>" placeholder="koralasercraft.com" required autocomplete="off">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="cpanel_port">Port</label>
                        <input class="form-control" type="number" id="cpanel_port" name="cpanel[port]" value="<?= e((string) $cpanelSettings['port']) ?>" min="1" max="65535" required>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="cpanel_username">cPanel username</label>
                        <input class="form-control" type="text" id="cpanel_username" name="cpanel[username]" value="<?= e((string) $cpanelSettings['username']) ?>" placeholder="e.g. allthin2" required autocomplete="off">
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="cpanel_domain">Mailbox domain</label>
                        <input class="form-control" type="text" id="cpanel_domain" name="cpanel[domain]" value="<?= e((string) $cpanelSettings['domain']) ?>" placeholder="koralasercraft.com" required autocomplete="off">
                    </div>
                    <div class="form-group" style="margin:0;grid-column:1 / -1">
                        <label class="form-label" for="cpanel_token">API token</label>
                        <input
                            class="form-control"
                            type="password"
                            id="cpanel_token"
                            name="cpanel[token]"
                            value=""
                            placeholder="<?= !empty($cpanelSettings['token_set']) ? '••••••••  (leave blank to keep current token)' : 'Paste cPanel API token' ?>"
                            autocomplete="new-password"
                            <?= empty($cpanelSettings['token_set']) ? 'required' : '' ?>
                        >
                        <?php if (!empty($cpanelSettings['token_set'])): ?>
                            <p class="muted" style="font-size:.8rem;margin:.4rem 0 0">A token is already saved. Leave blank to keep it.</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="cpanel_quota">Default quota (MB)</label>
                        <input class="form-control" type="number" id="cpanel_quota" name="cpanel[default_quota_mb]" value="<?= e((string) $cpanelSettings['default_quota_mb']) ?>" min="0" max="102400">
                        <p class="muted" style="font-size:.8rem;margin:.4rem 0 0">Use <code>0</code> for unlimited (if your plan allows it).</p>
                    </div>
                    <div class="form-group" style="margin:0;display:flex;align-items:flex-end">
                        <label class="form-check" style="display:flex;gap:.6rem;align-items:flex-start;margin:0;cursor:pointer">
                            <input type="checkbox" name="cpanel[verify_ssl]" value="1"<?= !empty($cpanelSettings['verify_ssl']) ? ' checked' : '' ?> style="margin-top:.2rem">
                            <span>
                                <strong style="display:block;font-size:.9rem">Verify SSL</strong>
                                <span class="muted" style="font-size:.8rem">Recommended. Turn off only if the host uses a self-signed cert.</span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </section>

        <section class="panel" id="password" style="margin-top:1.25rem">
            <div class="panel__head"><h2 class="panel__title">Change admin password</h2></div>
            <div class="panel__body" style="display:grid;gap:.85rem;max-width:480px">
                <p class="muted" style="font-size:.875rem;margin:0">Leave blank to keep your current admin login password.</p>
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
