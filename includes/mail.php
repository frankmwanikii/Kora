<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/email-templates.php';

$koraAutoload = dirname(__DIR__) . '/vendor/autoload.php';
if (is_file($koraAutoload)) {
    require_once $koraAutoload;
}

use PHPMailer\PHPMailer\Exception as PHPMailerException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * @return array<string, mixed>
 */
function kora_mail_config(): array
{
    static $config = null;

    if (is_array($config)) {
        return $config;
    }

    $defaults = [
        'host' => 'smtp.hostinger.com',
        'port' => 465,
        'encryption' => 'ssl',
        'username' => 'info@kora.fraittech.co.ke',
        'password' => '',
        'from_email' => 'info@kora.fraittech.co.ke',
        'from_name' => SITE_NAME,
        'timeout' => 30,
        'transports' => [],
    ];

    $path = dirname(__DIR__) . '/data/mail.local.php';
    $local = is_file($path) ? require $path : [];

    if (!is_array($local)) {
        $local = [];
    }

    $config = array_merge($defaults, $local);
    $config['port'] = (int) $config['port'];
    $config['timeout'] = max(5, (int) $config['timeout']);
    $config['host'] = (string) $config['host'];
    $config['encryption'] = strtolower((string) $config['encryption']);
    $config['username'] = (string) $config['username'];
    $config['password'] = (string) $config['password'];
    $config['from_email'] = (string) ($config['from_email'] !== '' ? $config['from_email'] : $config['username']);
    $config['from_name'] = (string) ($config['from_name'] !== '' ? $config['from_name'] : SITE_NAME);

    return $config;
}

/**
 * @param array<string, mixed> $config
 * @return list<array<string, mixed>>
 */
function kora_mail_transports(array $config): array
{
    $base = [
        'host' => (string) $config['host'],
        'port' => (int) $config['port'],
        'encryption' => (string) $config['encryption'],
        'username' => (string) $config['username'],
        'password' => (string) $config['password'],
        'from_email' => (string) $config['from_email'],
        'from_name' => (string) $config['from_name'],
        'timeout' => (int) $config['timeout'],
    ];

    $transports = [];
    $raw = $config['transports'] ?? null;

    if (is_array($raw) && $raw !== []) {
        foreach ($raw as $item) {
            if (!is_array($item)) {
                continue;
            }

            $transports[] = array_merge($base, [
                'host' => (string) ($item['host'] ?? $base['host']),
                'port' => (int) ($item['port'] ?? $base['port']),
                'encryption' => strtolower((string) ($item['encryption'] ?? $base['encryption'])),
                'username' => (string) ($item['username'] ?? $base['username']),
                'password' => array_key_exists('password', $item) ? (string) $item['password'] : $base['password'],
            ]);
        }
    } else {
        $transports[] = $base;
        $transports[] = array_merge($base, [
            'host' => '127.0.0.1',
            'port' => 25,
            'encryption' => 'none',
            'username' => '',
            'password' => '',
        ]);
    }

    return $transports;
}

/**
 * @param list<array{name: string, mime: string, data: string}> $attachments
 */
function kora_send_mail(
    string $to,
    string $subject,
    string $body,
    string $replyTo,
    array $attachments = [],
    string $htmlBody = ''
): bool {
    if (!class_exists(PHPMailer::class)) {
        error_log('KORA mail: PHPMailer is not installed.');

        return false;
    }

    $to = str_replace(["\r", "\n"], '', $to);
    $replyTo = str_replace(["\r", "\n"], '', $replyTo);
    $plainBody = str_replace(["\r\n", "\r", "\n"], "\r\n", $body);
    $htmlBody = $htmlBody !== '' ? $htmlBody : kora_plain_to_html($plainBody);

    $config = kora_mail_config();

    foreach (kora_mail_transports($config) as $transport) {
        if (kora_phpmailer_send($transport, $to, $subject, $plainBody, $htmlBody, $replyTo, $attachments)) {
            return true;
        }
    }

    return false;
}

/**
 * @param array<string, mixed> $transport
 * @param list<array{name: string, mime: string, data: string}> $attachments
 */
function kora_phpmailer_send(
    array $transport,
    string $to,
    string $subject,
    string $plainBody,
    string $htmlBody,
    string $replyTo,
    array $attachments
): bool {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = (string) $transport['host'];
        $mail->Port = (int) $transport['port'];
        $mail->Timeout = (int) ($transport['timeout'] ?? 30);
        $mail->SMTPAutoTLS = false;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->XMailer = 'KORA website PHPMailer';
        $mail->Hostname = 'kora.fraittech.co.ke';

        $encryption = strtolower((string) ($transport['encryption'] ?? 'none'));
        $password = (string) ($transport['password'] ?? '');

        if ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->SMTPAutoTLS = true;
        } else {
            $mail->SMTPSecure = false;
        }

        if ($password !== '') {
            $mail->SMTPAuth = true;
            $mail->Username = (string) ($transport['username'] ?? '');
            $mail->Password = $password;
        } else {
            $mail->SMTPAuth = false;
        }

        // Shared hosts sometimes present mismatched certs.
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
        ];

        $fromEmail = (string) ($transport['from_email'] ?? SITE_EMAIL);
        $fromName = (string) ($transport['from_name'] ?? SITE_NAME);

        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($to);

        if ($replyTo !== '') {
            $mail->addReplyTo($replyTo);
        }

        $mail->Subject = $subject;
        $mail->isHTML(true);
        $mail->Body = $htmlBody;
        $mail->AltBody = $plainBody;

        // Embed brand logos referenced as cid: so they display even when remote asset URLs 403.
        foreach (['white', 'mark'] as $logoVariant) {
            $cid = kora_email_logo_cid($logoVariant);
            if (!str_contains($htmlBody, 'cid:' . $cid)) {
                continue;
            }

            $logoPath = kora_email_logo_path($logoVariant);
            if (!is_file($logoPath) || !is_readable($logoPath)) {
                continue;
            }

            $logoData = file_get_contents($logoPath);
            if ($logoData === false || $logoData === '') {
                continue;
            }

            $logoMime = str_ends_with(strtolower($logoPath), '.png') ? 'image/png' : 'image/webp';

            $mail->addStringEmbeddedImage(
                $logoData,
                $cid,
                basename($logoPath),
                PHPMailer::ENCODING_BASE64,
                $logoMime
            );
        }

        foreach ($attachments as $index => $attachment) {
            $name = (string) ($attachment['name'] ?? 'attachment');
            $mime = (string) ($attachment['mime'] ?? 'application/octet-stream');
            $data = (string) ($attachment['data'] ?? '');
            if ($data === '') {
                continue;
            }

            $cid = (string) ($attachment['cid'] ?? ('kora-attach-' . $index));
            $isImage = str_starts_with(strtolower($mime), 'image/');

            // Always attach the original file.
            $mail->addStringAttachment($data, $name, PHPMailer::ENCODING_BASE64, $mime);

            // Embed image previews inline for HTML (cid:...) in admin emails.
            if ($isImage && str_contains($htmlBody, 'cid:' . $cid)) {
                $mail->addStringEmbeddedImage($data, $cid, $name, PHPMailer::ENCODING_BASE64, $mime);
            }
        }

        $mail->send();

        return true;
    } catch (PHPMailerException $e) {
        error_log(
            'KORA PHPMailer failed (' . ($transport['host'] ?? '') . ':' . ($transport['port'] ?? '') . '): '
            . $mail->ErrorInfo
        );

        return false;
    } catch (Throwable $e) {
        error_log('KORA PHPMailer error: ' . $e->getMessage());

        return false;
    }
}

function kora_plain_to_html(string $plainBody): string
{
    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>'
        . '<body style="margin:0;padding:24px;font-family:Arial,Helvetica,sans-serif;color:#333652;background:#e9eaec;">'
        . '<div style="max-width:600px;margin:0 auto;background:#ffffff;padding:24px;border-radius:8px;">'
        . nl2br(kora_email_escape($plainBody), false)
        . '</div></body></html>';
}

/**
 * Persist quote inspiration files and return attachments enriched with secure view/download URLs.
 *
 * @param list<array{name: string, mime: string, data: string, size?: int, cid?: string}> $attachments
 * @return list<array{name: string, mime: string, data: string, size: int, cid: string, token: string, view_url: string, download_url: string}>
 */
function kora_persist_quote_attachments(array $attachments): array
{
    if ($attachments === []) {
        return [];
    }

    $root = dirname(__DIR__) . '/data/quote-files';

    if (!is_dir($root) && !@mkdir($root, 0775, true) && !is_dir($root)) {
        error_log('KORA unable to create quote-files directory.');

        return array_map(static function (array $attachment): array {
            $attachment['size'] = (int) ($attachment['size'] ?? strlen((string) ($attachment['data'] ?? '')));
            $attachment['cid'] = (string) ($attachment['cid'] ?? 'kora-attach-0');
            $attachment['token'] = '';
            $attachment['view_url'] = '';
            $attachment['download_url'] = '';

            return $attachment;
        }, $attachments);
    }

    @chmod($root, 0775);

    $stored = [];

    foreach ($attachments as $index => $attachment) {
        $name = (string) ($attachment['name'] ?? 'attachment');
        $mime = (string) ($attachment['mime'] ?? 'application/octet-stream');
        $data = (string) ($attachment['data'] ?? '');
        $size = (int) ($attachment['size'] ?? strlen($data));
        $cid = (string) ($attachment['cid'] ?? ('kora-attach-' . $index));

        $item = [
            'name' => $name,
            'mime' => $mime,
            'data' => $data,
            'size' => $size,
            'cid' => $cid,
            'token' => '',
            'view_url' => '',
            'download_url' => '',
        ];

        if ($data === '') {
            $stored[] = $item;
            continue;
        }

        try {
            $token = bin2hex(random_bytes(24));
        } catch (Throwable $e) {
            $token = hash('sha256', uniqid('kora', true) . microtime(true) . $index);
        }

        $dir = $root . '/' . $token;
        if (!@mkdir($dir, 0775, true) && !is_dir($dir)) {
            $stored[] = $item;
            continue;
        }

        @chmod($dir, 0775);

        $extension = strtolower((string) pathinfo($name, PATHINFO_EXTENSION));
        $safeExt = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'bin';
        $filePath = $dir . '/file.' . $safeExt;

        if (@file_put_contents($filePath, $data) === false) {
            $stored[] = $item;
            continue;
        }

        @chmod($filePath, 0664);

        $meta = [
            'token' => $token,
            'name' => $name,
            'mime' => $mime,
            'size' => $size,
            'extension' => $safeExt,
            'created_at' => date('c'),
        ];

        @file_put_contents(
            $dir . '/meta.json',
            json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );
        @chmod($dir . '/meta.json', 0664);

        $base = rtrim(SITE_URL, '/') . '/view-quote-attachment.php?t=' . rawurlencode($token);
        $item['token'] = $token;
        $item['view_url'] = $base;
        $item['download_url'] = $base . '&dl=1';
        $stored[] = $item;
    }

    return $stored;
}

/**
 * Store quote submissions under data/quotes/ so leads survive SMTP outages.
 *
 * @param array<string, string> $fields
 * @param list<array{name?: string, token?: string, view_url?: string, download_url?: string, mime?: string, size?: int}|string> $attachments
 */
function kora_store_quote_request(array $fields, array $attachments = []): void
{
    $dir = dirname(__DIR__) . '/data/quotes';

    if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
        error_log('KORA unable to create quotes directory.');

        return;
    }

    @chmod($dir, 0775);

    $attachmentMeta = [];
    foreach ($attachments as $item) {
        if (is_string($item)) {
            $attachmentMeta[] = ['name' => $item];
            continue;
        }
        if (!is_array($item)) {
            continue;
        }
        $attachmentMeta[] = [
            'name' => (string) ($item['name'] ?? ''),
            'token' => (string) ($item['token'] ?? ''),
            'mime' => (string) ($item['mime'] ?? ''),
            'size' => (int) ($item['size'] ?? 0),
            'view_url' => (string) ($item['view_url'] ?? ''),
            'download_url' => (string) ($item['download_url'] ?? ''),
        ];
    }

    $payload = [
        'received_at' => date('c'),
        'fields' => $fields,
        'attachments' => $attachmentMeta,
        'ip' => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
        'user_agent' => (string) ($_SERVER['HTTP_USER_AGENT'] ?? ''),
    ];

    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return;
    }

    $file = $dir . '/quote-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.json';
    @file_put_contents($file, $json);
    @chmod($file, 0664);
}
