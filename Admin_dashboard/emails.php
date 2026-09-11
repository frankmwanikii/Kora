<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/cpanel-email.php';

$pageTitle = 'Emails';
$pageSubtitle = 'Create & manage mailboxes';
$activeNav = 'emails';

$cpanel = kora_load_cpanel_settings();
$formLocal = '';
$formQuota = (string) $cpanel['default_quota_mb'];
$webmailUrl = 'https://' . $cpanel['domain'] . '/webmail';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid session. Please try again.');
        redirect('/emails.php');
    }

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'create_email') {
        $formLocal = strtolower(trim((string) ($_POST['local_part'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');
        $formQuota = (string) ($_POST['quota_mb'] ?? $cpanel['default_quota_mb']);
        $quotaMb = (int) $formQuota;

        if ($password !== $passwordConfirm) {
            flash('error', 'Passwords do not match.');
            redirect('/emails.php');
        }

        $created = kora_cpanel_create_email($formLocal, $password, $quotaMb);
        if (!$created['ok']) {
            flash('error', (string) ($created['error'] ?? 'Could not create mailbox.'));
            redirect('/emails.php');
        }

        flash('success', 'Created mailbox ' . (string) ($created['email'] ?? '') . '.');
        redirect('/emails.php');
    }

    if ($action === 'delete_email') {
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $deleted = kora_cpanel_delete_email($email);
        if (!$deleted['ok']) {
            flash('error', (string) ($deleted['error'] ?? 'Could not delete mailbox.'));
        } else {
            flash('success', 'Deleted mailbox ' . $email . '.');
        }
        redirect('/emails.php');
    }

    flash('error', 'Unknown action.');
    redirect('/emails.php');
}

$cpanel = kora_load_cpanel_settings();
$webmailUrl = 'https://' . $cpanel['domain'] . '/webmail';
$list = ['ok' => false, 'accounts' => [], 'error' => null];
if ($cpanel['configured']) {
    $list = kora_cpanel_list_emails();
}

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub emails-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Mailbox management</p>
            <h2 class="pages-hub__title">Emails for <?= e($cpanel['domain']) ?></h2>
            <p class="pages-hub__lead">
                Create new addresses like <code>name@<?= e($cpanel['domain']) ?></code>.
                Connection settings live in
                <a href="<?= e(admin_base_path()) ?>/settings.php#cpanel">Settings → cPanel connection</a>.
            </p>
        </div>
    </header>

    <?php if (!$cpanel['configured']): ?>
        <div class="alert alert-info" role="status">
            <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
            <span>
                cPanel is not configured yet.
                <a href="<?= e(admin_base_path()) ?>/settings.php#cpanel">Set it up in Settings</a>, then come back here to create mailboxes.
            </span>
        </div>
    <?php endif; ?>

    <div class="emails-layout">
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Create email</h2>
            </div>
            <div class="panel__body">
                <form method="post" autocomplete="off" class="emails-create-form"<?= $cpanel['configured'] ? '' : ' aria-disabled="true"' ?>>
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="create_email">

                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="local_part">Email address</label>
                        <div class="emails-address-row">
                            <input
                                class="form-control"
                                type="text"
                                id="local_part"
                                name="local_part"
                                value="<?= e($formLocal) ?>"
                                placeholder="info"
                                required
                                pattern="[A-Za-z0-9](?:[A-Za-z0-9._+-]{0,62}[A-Za-z0-9])?"
                                maxlength="64"
                                autocomplete="off"
                                <?= $cpanel['configured'] ? '' : 'disabled' ?>
                            >
                            <span class="emails-domain-suffix">@<?= e($cpanel['domain']) ?></span>
                        </div>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="email_password">Password</label>
                        <div class="password-field-wrap">
                            <input
                                class="form-control"
                                type="password"
                                id="email_password"
                                name="password"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                <?= $cpanel['configured'] ? '' : 'disabled' ?>
                            >
                            <button type="button" class="password-toggle-btn" data-password-toggle="email_password" aria-label="Show password"<?= $cpanel['configured'] ? '' : ' disabled' ?>>
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="email_password_confirm">Confirm password</label>
                        <div class="password-field-wrap">
                            <input
                                class="form-control"
                                type="password"
                                id="email_password_confirm"
                                name="password_confirm"
                                required
                                minlength="8"
                                autocomplete="new-password"
                                <?= $cpanel['configured'] ? '' : 'disabled' ?>
                            >
                            <button type="button" class="password-toggle-btn" data-password-toggle="email_password_confirm" aria-label="Show password"<?= $cpanel['configured'] ? '' : ' disabled' ?>>
                                <i class="fa-solid fa-eye" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin:0">
                        <label class="form-label" for="quota_mb">Quota (MB)</label>
                        <div class="number-stepper" data-number-stepper>
                            <button type="button" class="number-stepper__btn" data-step="-100" aria-label="Decrease quota"<?= $cpanel['configured'] ? '' : ' disabled' ?>>
                                <i class="fa-solid fa-minus" aria-hidden="true"></i>
                            </button>
                            <input
                                class="form-control number-stepper__input"
                                type="number"
                                id="quota_mb"
                                name="quota_mb"
                                value="<?= e($formQuota) ?>"
                                min="0"
                                max="102400"
                                step="100"
                                <?= $cpanel['configured'] ? '' : 'disabled' ?>
                            >
                            <button type="button" class="number-stepper__btn" data-step="100" aria-label="Increase quota"<?= $cpanel['configured'] ? '' : ' disabled' ?>>
                                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                            </button>
                        </div>
                        <p class="muted" style="font-size:.8rem;margin:.4rem 0 0">Use <code>0</code> for unlimited (if your plan allows it).</p>
                    </div>

                    <div class="emails-actions">
                        <button type="submit" class="btn btn-primary"<?= $cpanel['configured'] ? '' : ' disabled' ?>>
                            <i class="fa-solid fa-envelope-circle-plus" aria-hidden="true"></i> Create mailbox
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title">Existing mailboxes</h2>
                <?php if ($list['ok']): ?>
                    <span class="badge"><?= count($list['accounts']) ?> account<?= count($list['accounts']) === 1 ? '' : 's' ?></span>
                <?php endif; ?>
            </div>
            <div class="panel__body">
                <?php if (!$cpanel['configured']): ?>
                    <p class="muted" style="margin:0">Configure cPanel in Settings to load mailboxes for <?= e($cpanel['domain']) ?>.</p>
                <?php elseif (!$list['ok']): ?>
                    <div class="alert alert-error" role="alert">
                        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
                        <span><?= e((string) ($list['error'] ?? 'Could not load mailboxes.')) ?></span>
                    </div>
                <?php elseif ($list['accounts'] === []): ?>
                    <p class="muted" style="margin:0">No mailboxes found on <?= e($cpanel['domain']) ?> yet.</p>
                <?php else: ?>
                    <div class="emails-table-wrap">
                        <table class="emails-table">
                            <thead>
                                <tr>
                                    <th scope="col">Email</th>
                                    <th scope="col">Used</th>
                                    <th scope="col">Quota</th>
                                    <th scope="col" class="emails-table-actions-head">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($list['accounts'] as $index => $account): ?>
                                    <?php
                                    $email = (string) ($account['email'] ?? '');
                                    $used = kora_cpanel_format_quota($account['diskused'] ?? null);
                                    $quota = kora_cpanel_format_quota($account['diskquota'] ?? null);
                                    $deleteId = 'delete-email-' . $index;
                                    ?>
                                    <tr>
                                        <td>
                                            <span class="emails-table-email"><?= e($email) ?></span>
                                            <?php if (!empty($account['suspended_login'])): ?>
                                                <span class="badge badge-warn">Suspended</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= e($used) ?></td>
                                        <td><?= e($quota) ?></td>
                                        <td class="emails-table-actions">
                                            <form method="post" id="<?= e($deleteId) ?>" hidden>
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_email">
                                                <input type="hidden" name="email" value="<?= e($email) ?>">
                                            </form>
                                            <div class="action-menu" data-dropdown>
                                                <button
                                                    type="button"
                                                    class="btn btn-ghost btn-sm emails-action-trigger"
                                                    data-dropdown-toggle
                                                    aria-expanded="false"
                                                    aria-haspopup="true"
                                                    title="Actions"
                                                >
                                                    <span>Actions</span>
                                                    <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                                                </button>
                                                <div class="dropdown-panel" data-dropdown-panel hidden>
                                                    <a href="<?= e($webmailUrl) ?>" target="_blank" rel="noopener">
                                                        <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                                                        Login
                                                    </a>
                                                    <hr>
                                                    <button
                                                        type="submit"
                                                        class="danger"
                                                        form="<?= e($deleteId) ?>"
                                                        data-confirm="Delete <?= e($email) ?>? This cannot be undone."
                                                        data-confirm-danger="1"
                                                    >
                                                        <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
