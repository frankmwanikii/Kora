<?php

declare(strict_types=1);

/**
 * cPanel UAPI helpers for listing and creating email accounts.
 * Credentials live in data/cpanel.local.php (never exported to the public site).
 */

function kora_cpanel_local_path(): string
{
    return site_root() . '/data/cpanel.local.php';
}

/**
 * @return array{
 *     host: string,
 *     port: int,
 *     username: string,
 *     token: string,
 *     domain: string,
 *     default_quota_mb: int,
 *     verify_ssl: bool,
 *     token_set: bool,
 *     configured: bool
 * }
 */
function kora_load_cpanel_settings(): array
{
    $defaults = [
        'host' => 'koralasercraft.com',
        'port' => 2083,
        'username' => '',
        'token' => '',
        'domain' => 'koralasercraft.com',
        'default_quota_mb' => 1024,
        'verify_ssl' => true,
    ];

    $path = kora_cpanel_local_path();
    if (!is_file($path)) {
        return $defaults + ['token_set' => false, 'configured' => false];
    }

    $local = require $path;
    if (!is_array($local)) {
        return $defaults + ['token_set' => false, 'configured' => false];
    }

    $host = trim((string) ($local['host'] ?? $defaults['host']));
    $host = preg_replace('#^https?://#i', '', $host) ?? $host;
    $host = rtrim($host, '/');

    $username = trim((string) ($local['username'] ?? ''));
    $token = trim((string) ($local['token'] ?? ''));
    $domain = strtolower(trim((string) ($local['domain'] ?? $defaults['domain'])));

    return [
        'host' => $host !== '' ? $host : $defaults['host'],
        'port' => max(1, min(65535, (int) ($local['port'] ?? $defaults['port']))),
        'username' => $username,
        'token' => $token,
        'domain' => $domain !== '' ? $domain : $defaults['domain'],
        'default_quota_mb' => max(0, (int) ($local['default_quota_mb'] ?? $defaults['default_quota_mb'])),
        'verify_ssl' => array_key_exists('verify_ssl', $local) ? (bool) $local['verify_ssl'] : true,
        'token_set' => $token !== '',
        'configured' => $username !== '' && $token !== '' && $host !== '' && $domain !== '',
    ];
}

/**
 * @param array<string, mixed> $input
 * @return array{ok: bool, error?: string}
 */
function kora_save_cpanel_settings(array $input, ?string $existingToken = null): array
{
    $host = trim((string) ($input['host'] ?? ''));
    $host = preg_replace('#^https?://#i', '', $host) ?? $host;
    $host = rtrim($host, '/');
    $port = (int) ($input['port'] ?? 2083);
    $username = trim((string) ($input['username'] ?? ''));
    $token = trim((string) ($input['token'] ?? ''));
    $domain = strtolower(trim((string) ($input['domain'] ?? '')));
    $quota = max(0, (int) ($input['default_quota_mb'] ?? 1024));
    $verifySsl = !empty($input['verify_ssl']);

    if ($host === '') {
        return ['ok' => false, 'error' => 'cPanel host is required.'];
    }

    if ($port < 1 || $port > 65535) {
        return ['ok' => false, 'error' => 'cPanel port must be between 1 and 65535.'];
    }

    if ($username === '') {
        return ['ok' => false, 'error' => 'cPanel username is required.'];
    }

    if ($token === '' && $existingToken !== null) {
        $token = $existingToken;
    }

    if ($token === '') {
        return ['ok' => false, 'error' => 'cPanel API token is required.'];
    }

    if ($domain === '' || !preg_match('/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/i', $domain)) {
        return ['ok' => false, 'error' => 'Enter a valid mailbox domain (e.g. koralasercraft.com).'];
    }

    $config = [
        'host' => $host,
        'port' => $port,
        'username' => $username,
        'token' => $token,
        'domain' => $domain,
        'default_quota_mb' => $quota,
        'verify_ssl' => $verifySsl,
    ];

    $path = kora_cpanel_local_path();
    $dir = dirname($path);

    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        return ['ok' => false, 'error' => 'Unable to create the data directory for cPanel settings.'];
    }

    if (!is_writable($dir)) {
        return [
            'ok' => false,
            'error' => 'Unable to write cpanel.local.php. The data/ directory must be writable by the web server.',
        ];
    }

    $exported = var_export($config, true);
    $php = <<<PHP
<?php

declare(strict_types=1);

/**
 * Local cPanel API credentials for mailbox management.
 * Managed from Admin Dashboard → Settings. Do not commit this file.
 */
return {$exported};

PHP;

    // Atomic write via temp + rename so we don't need ownership of an existing file.
    $tmp = $dir . '/.cpanel.local.php.' . bin2hex(random_bytes(4)) . '.tmp';
    $written = @file_put_contents($tmp, $php, LOCK_EX);
    if ($written === false) {
        @unlink($tmp);

        return [
            'ok' => false,
            'error' => 'Unable to write cpanel.local.php. The web server needs write access to data/.',
        ];
    }

    @chmod($tmp, 0664);
    if (!@rename($tmp, $path)) {
        // Fallback if rename can't replace a root-owned file.
        $copied = @copy($tmp, $path);
        @unlink($tmp);
        if (!$copied) {
            return [
                'ok' => false,
                'error' => 'Unable to replace cpanel.local.php. Check ownership of data/cpanel.local.php (should be www-data).',
            ];
        }
    }

    @chmod($path, 0664);

    return ['ok' => true];
}

/**
 * @param array<string, scalar|null> $params
 * @return array{ok: bool, status: int, data: mixed, errors: list<string>, raw: array<string, mixed>|null, error?: string}
 */
function kora_cpanel_uapi(string $module, string $function, array $params = []): array
{
    $settings = kora_load_cpanel_settings();

    if (!$settings['configured']) {
        return [
            'ok' => false,
            'status' => 0,
            'data' => null,
            'errors' => ['cPanel is not configured. Save host, username, token, and domain first.'],
            'raw' => null,
            'error' => 'cPanel is not configured.',
        ];
    }

    if (!function_exists('curl_init')) {
        return [
            'ok' => false,
            'status' => 0,
            'data' => null,
            'errors' => ['PHP cURL extension is required to call the cPanel API.'],
            'raw' => null,
            'error' => 'PHP cURL extension is required.',
        ];
    }

    $query = http_build_query($params);
    $url = sprintf(
        'https://%s:%d/execute/%s/%s%s',
        $settings['host'],
        $settings['port'],
        rawurlencode($module),
        rawurlencode($function),
        $query !== '' ? '?' . $query : ''
    );

    $ch = curl_init($url);
    if ($ch === false) {
        return [
            'ok' => false,
            'status' => 0,
            'data' => null,
            'errors' => ['Unable to initialize cURL.'],
            'raw' => null,
            'error' => 'Unable to initialize cURL.',
        ];
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_HTTPHEADER => [
            'Authorization: cpanel ' . $settings['username'] . ':' . $settings['token'],
            'Accept: application/json',
        ],
        CURLOPT_SSL_VERIFYPEER => $settings['verify_ssl'],
        CURLOPT_SSL_VERIFYHOST => $settings['verify_ssl'] ? 2 : 0,
    ]);

    $body = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($body === false) {
        return [
            'ok' => false,
            'status' => $status,
            'data' => null,
            'errors' => [$curlError !== '' ? $curlError : 'cPanel request failed.'],
            'raw' => null,
            'error' => $curlError !== '' ? $curlError : 'cPanel request failed.',
        ];
    }

    $decoded = json_decode((string) $body, true);
    if (!is_array($decoded)) {
        $plain = trim((string) $body);
        $message = $plain !== '' ? $plain : 'Unexpected response from cPanel.';
        if ($status === 401 || $status === 403 || strcasecmp($plain, 'Access denied') === 0) {
            $message = 'Access denied. Check the cPanel username and API token.';
        }

        return [
            'ok' => false,
            'status' => $status,
            'data' => null,
            'errors' => [$message],
            'raw' => null,
            'error' => $message,
        ];
    }

    $errors = [];
    if (isset($decoded['errors']) && is_array($decoded['errors'])) {
        foreach ($decoded['errors'] as $err) {
            if (is_string($err) && $err !== '') {
                $errors[] = $err;
            }
        }
    }

    $statusFlag = $decoded['status'] ?? null;
    $ok = ((int) $statusFlag === 1) && $errors === [];

    return [
        'ok' => $ok,
        'status' => $status,
        'data' => $decoded['data'] ?? null,
        'errors' => $errors,
        'raw' => $decoded,
        'error' => $ok ? null : ($errors[0] ?? 'cPanel API call failed.'),
    ];
}

/**
 * @return array{ok: bool, accounts: list<array<string, mixed>>, error?: string}
 */
function kora_cpanel_list_emails(): array
{
    $settings = kora_load_cpanel_settings();
    $result = kora_cpanel_uapi('Email', 'list_pops_with_disk', [
        'domain' => $settings['domain'],
    ]);

    if (!$result['ok']) {
        // Fallback for older/restricted hosts.
        $result = kora_cpanel_uapi('Email', 'list_pops', [
            'domain' => $settings['domain'],
        ]);
    }

    if (!$result['ok']) {
        return [
            'ok' => false,
            'accounts' => [],
            'error' => (string) ($result['error'] ?? 'Could not list email accounts.'),
        ];
    }

    $data = $result['data'];
    $accounts = [];

    if (is_array($data)) {
        // list_pops_with_disk returns a list of account objects.
        $isList = array_is_list($data) || (isset($data[0]) && is_array($data[0]));
        if ($isList) {
            foreach ($data as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $email = (string) ($row['email'] ?? $row['login'] ?? '');
                if ($email === '' && isset($row['user'], $row['domain'])) {
                    $email = (string) $row['user'] . '@' . (string) $row['domain'];
                }
                if ($email === '') {
                    continue;
                }
                $accounts[] = [
                    'email' => $email,
                    'user' => (string) ($row['user'] ?? strtok($email, '@')),
                    'domain' => (string) ($row['domain'] ?? $settings['domain']),
                    'diskused' => $row['diskused'] ?? ($row['humandiskused'] ?? null),
                    'diskquota' => $row['diskquota'] ?? ($row['humandiskquota'] ?? null),
                    'suspended_login' => !empty($row['suspended_login']),
                ];
            }
        } else {
            // list_pops may return email => path map.
            foreach ($data as $key => $value) {
                if (is_string($key) && str_contains($key, '@')) {
                    $accounts[] = [
                        'email' => $key,
                        'user' => (string) strtok($key, '@'),
                        'domain' => (string) (strstr($key, '@') !== false ? substr(strstr($key, '@'), 1) : $settings['domain']),
                        'diskused' => null,
                        'diskquota' => null,
                        'suspended_login' => false,
                    ];
                } elseif (is_array($value)) {
                    $email = (string) ($value['email'] ?? $value['login'] ?? $key);
                    if ($email === '') {
                        continue;
                    }
                    $accounts[] = [
                        'email' => $email,
                        'user' => (string) ($value['user'] ?? strtok($email, '@')),
                        'domain' => (string) ($value['domain'] ?? $settings['domain']),
                        'diskused' => $value['diskused'] ?? null,
                        'diskquota' => $value['diskquota'] ?? null,
                        'suspended_login' => !empty($value['suspended_login']),
                    ];
                }
            }
        }
    }

    usort($accounts, static fn(array $a, array $b): int => strcasecmp((string) $a['email'], (string) $b['email']));

    return ['ok' => true, 'accounts' => $accounts];
}

/**
 * @return array{ok: bool, email?: string, error?: string}
 */
function kora_cpanel_create_email(string $localPart, string $password, ?int $quotaMb = null): array
{
    $settings = kora_load_cpanel_settings();
    $localPart = strtolower(trim($localPart));
    $password = (string) $password;

    if ($localPart === '' || !preg_match('/^[a-z0-9](?:[a-z0-9._+-]{0,62}[a-z0-9])?$/i', $localPart)) {
        return ['ok' => false, 'error' => 'Enter a valid mailbox name (letters, numbers, . _ + -).'];
    }

    if (mb_strlen($password) < 8) {
        return ['ok' => false, 'error' => 'Password must be at least 8 characters.'];
    }

    $quota = $quotaMb ?? $settings['default_quota_mb'];
    if ($quota < 0) {
        $quota = 0;
    }

    $result = kora_cpanel_uapi('Email', 'add_pop', [
        'email' => $localPart,
        'password' => $password,
        'domain' => $settings['domain'],
        'quota' => $quota,
        'send_welcome_email' => 0,
    ]);

    if (!$result['ok']) {
        return [
            'ok' => false,
            'error' => (string) ($result['error'] ?? 'Could not create the email account.'),
        ];
    }

    return [
        'ok' => true,
        'email' => $localPart . '@' . $settings['domain'],
    ];
}

/**
 * @return array{ok: bool, error?: string}
 */
function kora_cpanel_delete_email(string $email): array
{
    $settings = kora_load_cpanel_settings();
    $email = strtolower(trim($email));

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'error' => 'Invalid email address.'];
    }

    $suffix = '@' . strtolower($settings['domain']);
    if (!str_ends_with($email, $suffix)) {
        return ['ok' => false, 'error' => 'You can only delete mailboxes on ' . $settings['domain'] . '.'];
    }

    $local = substr($email, 0, -strlen($suffix));
    $result = kora_cpanel_uapi('Email', 'delete_pop', [
        'email' => $local,
        'domain' => $settings['domain'],
    ]);

    if (!$result['ok']) {
        return [
            'ok' => false,
            'error' => (string) ($result['error'] ?? 'Could not delete the email account.'),
        ];
    }

    return ['ok' => true];
}

function kora_cpanel_format_quota(mixed $value): string
{
    if ($value === null || $value === '') {
        return '—';
    }

    if (is_string($value) && !is_numeric($value)) {
        return $value;
    }

    $num = (float) $value;
    if ($num <= 0) {
        return 'Unlimited';
    }

    // list_pops_with_disk often returns megabytes already.
    if ($num < 1024 * 10) {
        return rtrim(rtrim(number_format($num, 1), '0'), '.') . ' MB';
    }

    // Bytes fallback.
    $mb = $num / (1024 * 1024);
    if ($mb >= 1024) {
        return rtrim(rtrim(number_format($mb / 1024, 2), '0'), '.') . ' GB';
    }

    return rtrim(rtrim(number_format($mb, 1), '0'), '.') . ' MB';
}
