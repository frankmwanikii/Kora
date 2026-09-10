<?php

declare(strict_types=1);

/**
 * Admin helpers for editing outbound SMTP credentials in data/mail.local.php.
 * Kept separate from public CMS settings so the password is never exported to site.json.
 */

function kora_mail_local_path(): string
{
    return site_root() . '/data/mail.local.php';
}

/**
 * @return array{
 *     host: string,
 *     port: int,
 *     encryption: string,
 *     username: string,
 *     password: string,
 *     from_email: string,
 *     from_name: string,
 *     timeout: int,
 *     keep_local_fallback: bool,
 *     password_set: bool
 * }
 */
function kora_load_mail_settings(): array
{
    $defaults = [
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => '',
        'password' => '',
        'from_email' => '',
        'from_name' => 'KORA',
        'timeout' => 30,
        'keep_local_fallback' => true,
        'password_set' => false,
    ];

    $path = kora_mail_local_path();
    if (!is_file($path)) {
        return $defaults;
    }

    $local = require $path;
    if (!is_array($local)) {
        return $defaults;
    }

    $password = (string) ($local['password'] ?? '');
    $encryption = strtolower((string) ($local['encryption'] ?? $defaults['encryption']));
    if (!in_array($encryption, ['ssl', 'tls', 'none'], true)) {
        $encryption = 'ssl';
    }

    $keepFallback = true;
    $transports = $local['transports'] ?? null;
    if (is_array($transports)) {
        $keepFallback = false;
        foreach ($transports as $transport) {
            if (!is_array($transport)) {
                continue;
            }
            $host = (string) ($transport['host'] ?? '');
            $port = (int) ($transport['port'] ?? 0);
            if (($host === '127.0.0.1' || $host === 'localhost') && $port === 25) {
                $keepFallback = true;
                break;
            }
        }
        if ($transports === []) {
            $keepFallback = true;
        }
    }

    return [
        'host' => (string) ($local['host'] ?? $defaults['host']),
        'port' => (int) ($local['port'] ?? $defaults['port']),
        'encryption' => $encryption,
        'username' => (string) ($local['username'] ?? ''),
        'password' => $password,
        'from_email' => (string) ($local['from_email'] ?? ''),
        'from_name' => (string) ($local['from_name'] ?? $defaults['from_name']),
        'timeout' => max(5, (int) ($local['timeout'] ?? $defaults['timeout'])),
        'keep_local_fallback' => $keepFallback,
        'password_set' => $password !== '',
    ];
}

/**
 * @param array<string, mixed> $input
 * @return array{ok: bool, error?: string}
 */
function kora_save_mail_settings(array $input, ?string $existingPassword = null): array
{
    $host = trim((string) ($input['host'] ?? ''));
    $port = (int) ($input['port'] ?? 0);
    $encryption = strtolower(trim((string) ($input['encryption'] ?? 'ssl')));
    $username = trim((string) ($input['username'] ?? ''));
    $password = (string) ($input['password'] ?? '');
    $fromEmail = trim((string) ($input['from_email'] ?? ''));
    $fromName = trim((string) ($input['from_name'] ?? ''));
    $timeout = max(5, (int) ($input['timeout'] ?? 30));
    $keepFallback = !empty($input['keep_local_fallback']);

    if ($host === '') {
        return ['ok' => false, 'error' => 'SMTP host is required.'];
    }

    if ($port < 1 || $port > 65535) {
        return ['ok' => false, 'error' => 'SMTP port must be between 1 and 65535.'];
    }

    if (!in_array($encryption, ['ssl', 'tls', 'none'], true)) {
        return ['ok' => false, 'error' => 'SMTP encryption must be SSL, TLS, or None.'];
    }

    if ($fromEmail !== '' && !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'From email is not a valid email address.'];
    }

    if ($username !== '' && str_contains($username, '@') && !filter_var($username, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'SMTP username looks like an email but is not valid.'];
    }

    // Empty password field keeps the existing secret.
    if ($password === '' && $existingPassword !== null) {
        $password = $existingPassword;
    }

    if ($fromEmail === '' && $username !== '' && filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $fromEmail = $username;
    }

    if ($fromName === '') {
        $fromName = 'KORA';
    }

    $config = [
        'host' => $host,
        'port' => $port,
        'encryption' => $encryption,
        'username' => $username,
        'password' => $password,
        'from_email' => $fromEmail,
        'from_name' => $fromName,
        'timeout' => $timeout,
        'transports' => [
            [
                'host' => $host,
                'port' => $port,
                'encryption' => $encryption,
                'username' => $username,
                'password' => $password,
            ],
        ],
    ];

    if ($keepFallback) {
        $config['transports'][] = [
            'host' => '127.0.0.1',
            'port' => 25,
            'encryption' => 'none',
            'username' => '',
            'password' => '',
        ];
    }

    $path = kora_mail_local_path();
    $dir = dirname($path);

    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return ['ok' => false, 'error' => 'Unable to create the data directory for mail settings.'];
    }

    $exported = var_export($config, true);
    $php = <<<PHP
<?php

declare(strict_types=1);

/**
 * Local SMTP credentials for KORA outbound mail.
 * Managed from Admin Dashboard → Settings. Do not commit this file.
 */
return {$exported};

PHP;

    $written = @file_put_contents($path, $php, LOCK_EX);
    if ($written === false) {
        return ['ok' => false, 'error' => 'Unable to write mail.local.php. Check file permissions.'];
    }

    @chmod($path, 0640);

    return ['ok' => true];
}
