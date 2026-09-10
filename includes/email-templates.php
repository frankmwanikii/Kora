<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

function kora_email_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function kora_email_format_date(string $value): string
{
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);

    if ($date === false || $date->format('Y-m-d') !== $value) {
        return $value;
    }

    return $date->format('j F Y');
}

function kora_email_format_bytes(int $bytes): string
{
    if ($bytes < 1024) {
        return $bytes . ' B';
    }
    if ($bytes < 1048576) {
        return number_format($bytes / 1024, 1) . ' KB';
    }

    return number_format($bytes / 1048576, 1) . ' MB';
}

function kora_email_is_image_mime(string $mime): bool
{
    return str_starts_with(strtolower($mime), 'image/');
}

/**
 * Absolute URL for the brand logo used in outbound emails (remote fallback).
 */
function kora_email_logo_url(string $variant = 'white'): string
{
    $file = $variant === 'mark' ? 'kora_logo1.webp' : 'kora_logo_white.webp';

    return rtrim(SITE_URL, '/') . '/assets/images/logos/' . $file;
}

/**
 * CID used when embedding the brand logo inline in HTML emails.
 */
function kora_email_logo_cid(string $variant = 'white'): string
{
    return $variant === 'mark' ? 'kora-brand-logo-mark' : 'kora-brand-logo';
}

/**
 * Absolute filesystem path to the brand logo for email embedding.
 */
function kora_email_logo_path(string $variant = 'white'): string
{
    $file = $variant === 'mark' ? 'kora_logo1.webp' : 'kora_logo_white.webp';

    return dirname(__DIR__) . '/assets/images/logos/' . $file;
}

/**
 * Prefer CID embedding so logos still show when remote image URLs are blocked (403/CDN).
 */
function kora_email_logo_src(string $variant = 'white'): string
{
    $path = kora_email_logo_path($variant);

    if (is_file($path) && is_readable($path)) {
        return 'cid:' . kora_email_logo_cid($variant);
    }

    return kora_email_logo_url($variant);
}

/**
 * Navy brand header with logo for HTML emails.
 */
function kora_email_brand_header_html(string $eyebrow = ''): string
{
    $navy = '#333652';
    $copper = '#a66a3d';
    $logoSrc = kora_email_logo_src('white');
    $siteUrl = rtrim(SITE_URL, '/');

    $eyebrowHtml = '';
    if (trim($eyebrow) !== '') {
        $eyebrowHtml =
            '<p style="margin:0 0 14px;font-family:Arial,Helvetica,sans-serif;font-size:11px;letter-spacing:0.16em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">'
            . kora_email_escape($eyebrow)
            . '</p>';
    }

    return
        '<td style="background:linear-gradient(135deg,' . $navy . ' 0%,#2a2d44 100%);padding:26px 32px 22px;" class="email-pad">'
        . $eyebrowHtml
        . '<a href="' . kora_email_escape($siteUrl) . '" style="text-decoration:none;display:inline-block;">'
        . '<img src="' . kora_email_escape($logoSrc) . '" alt="' . kora_email_escape(SITE_NAME . ' Laser Craft') . '" width="180" height="80" style="display:block;width:180px;max-width:70%;height:auto;border:0;outline:none;">'
        . '</a>'
        . '<p style="margin:12px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:1.45;color:#cfd1db;">'
        . kora_email_escape(SITE_TAGLINE) . ' · ' . kora_email_escape(SITE_LOCATION)
        . '</p>'
        . '</td>';
}

/**
 * Compact logo strip for simpler transactional emails.
 */
function kora_email_simple_header_html(string $eyebrow = ''): string
{
    $navy = '#333652';
    $copper = '#a66a3d';
    $logoSrc = kora_email_logo_src('white');
    $siteUrl = rtrim(SITE_URL, '/');

    $eyebrowHtml = '';
    if (trim($eyebrow) !== '') {
        $eyebrowHtml =
            '<p style="margin:0 0 12px;font-size:11px;letter-spacing:0.14em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">'
            . kora_email_escape($eyebrow)
            . '</p>';
    }

    return
        '<tr><td style="background:' . $navy . ';padding:22px 24px;">'
        . $eyebrowHtml
        . '<a href="' . kora_email_escape($siteUrl) . '" style="text-decoration:none;display:inline-block;">'
        . '<img src="' . kora_email_escape($logoSrc) . '" alt="' . kora_email_escape(SITE_NAME . ' Laser Craft') . '" width="160" height="72" style="display:block;width:160px;max-width:70%;height:auto;border:0;outline:none;">'
        . '</a>'
        . '</td></tr>';
}

/**
 * Plain-text closing used on customer emails.
 */
function kora_email_regards_text(): string
{
    return "Regards,\nKora Laser Craft team.";
}

/**
 * HTML closing used on customer emails.
 */
function kora_email_regards_html(): string
{
    $navy = '#333652';

    return
        '<p style="margin:22px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:1.6;color:' . $navy . ';">'
        . 'Regards,<br>'
        . '<strong>Kora Laser Craft team.</strong>'
        . '</p>';
}

/**
 * @param list<array{name: string, mime?: string, data?: string, size?: int, cid?: string, token?: string, view_url?: string, download_url?: string}> $attachments
 * @return list<array{name: string, mime: string, size: int, is_image: bool, cid: string, token: string, view_url: string, download_url: string}>
 */
function kora_email_normalize_attachments(array $attachments): array
{
    $normalized = [];

    foreach ($attachments as $index => $attachment) {
        if (!is_array($attachment)) {
            continue;
        }

        $name = trim((string) ($attachment['name'] ?? ''));
        if ($name === '') {
            continue;
        }

        $mime = (string) ($attachment['mime'] ?? 'application/octet-stream');
        $data = (string) ($attachment['data'] ?? '');
        $size = (int) ($attachment['size'] ?? ($data !== '' ? strlen($data) : 0));
        $isImage = kora_email_is_image_mime($mime);
        $token = (string) ($attachment['token'] ?? '');
        $viewUrl = (string) ($attachment['view_url'] ?? '');
        $downloadUrl = (string) ($attachment['download_url'] ?? '');

        $normalized[] = [
            'name' => $name,
            'mime' => $mime,
            'size' => $size,
            'is_image' => $isImage,
            'cid' => (string) ($attachment['cid'] ?? ('kora-attach-' . $index)),
            'token' => $token,
            'view_url' => $viewUrl,
            'download_url' => $downloadUrl,
        ];
    }

    return $normalized;
}

/**
 * @param array<string, string> $fields
 * @param list<array{name: string, mime?: string, data?: string, size?: int}|string> $attachments
 * @return array{subject: string, text: string, html: string}
 */
function kora_quote_admin_email(array $fields, array $attachments): array
{
    $files = kora_email_normalize_attachments(kora_email_coerce_attachments($attachments));
    $name = $fields['name'];
    $subject = 'New quotation · ' . $fields['product'] . ' · ' . $name;
    $preheader = $fields['product'] . ' · qty ' . $fields['quantity'] . ' · ' . kora_email_format_date($fields['event_date']);

    return [
        'subject' => $subject,
        'text' => kora_quote_email_text($fields, $files, true),
        'html' => kora_quote_email_html(
            'New quotation request',
            $preheader,
            'A new brief just arrived from ' . $name . '. Reply to this email to reach them directly — inspiration files are attached.',
            $fields,
            $files,
            [
                [
                    'label' => 'Reply to ' . $name,
                    'href' => 'mailto:' . $fields['email'] . '?subject=' . rawurlencode('Re: Your KORA quotation request'),
                ],
                [
                    'label' => 'Call ' . $fields['phone'],
                    'href' => 'tel:' . preg_replace('/[^\d+]/', '', $fields['phone']),
                ],
            ],
            true
        ),
    ];
}

/**
 * @param array<string, string> $fields
 * @param list<array{name: string, mime?: string, data?: string, size?: int}|string> $attachments
 * @return array{subject: string, text: string, html: string}
 */
function kora_quote_confirmation_email(array $fields, array $attachments): array
{
    $files = kora_email_normalize_attachments(kora_email_coerce_attachments($attachments));
    $firstName = explode(' ', trim($fields['name']), 2)[0];
    $subject = 'We received your quotation request';
    $preheader = 'Thank you, ' . $firstName . '. KORA will review your brief and reply within 24 hours.';

    return [
        'subject' => $subject,
        'text' => kora_quote_email_text($fields, $files, false),
        'html' => kora_quote_email_html(
            'Thank you, ' . $firstName,
            $preheader,
            'Your quotation request is with our workshop team. We usually reply within one business day with pricing and design direction.',
            $fields,
            $files,
            [
                [
                    'label' => 'Message on WhatsApp',
                    'href' => SITE_WHATSAPP,
                ],
                [
                    'label' => 'Call ' . SITE_PHONE,
                    'href' => 'tel:' . SITE_PHONE_LINK,
                ],
            ],
            false
        ),
    ];
}

/**
 * @param list<array{name: string, mime?: string, data?: string, size?: int}|string> $attachments
 * @return list<array{name: string, mime?: string, data?: string, size?: int}>
 */
function kora_email_coerce_attachments(array $attachments): array
{
    $out = [];
    foreach ($attachments as $item) {
        if (is_string($item) && trim($item) !== '') {
            $out[] = ['name' => $item, 'mime' => 'application/octet-stream', 'size' => 0];
            continue;
        }
        if (is_array($item)) {
            $out[] = $item;
        }
    }

    return $out;
}

/**
 * @param array<string, string> $fields
 * @param list<array{name: string, mime: string, size: int, is_image: bool, cid: string}> $files
 */
function kora_quote_email_text(array $fields, array $files, bool $isAdmin): string
{
    $lines = $isAdmin
        ? [
            'New quotation request from ' . $fields['name'] . '.',
            'Reply to this email to contact them directly.',
            '',
        ]
        : [
            'Thank you for your quotation request.',
            'KORA has received your brief and will respond within 24 hours.',
            '',
            'Here is a copy of what you sent:',
            '',
        ];

    foreach (kora_quote_email_rows($fields) as $row) {
        $lines[] = $row['label'] . ': ' . $row['value'];
    }

    $lines[] = 'Inspiration files: ' . ($files === []
        ? 'None attached'
        : implode(', ', array_map(static fn(array $f): string => $f['name'], $files)));
    $lines[] = '';

    if (!$isAdmin) {
        $lines[] = kora_email_regards_text();
        $lines[] = '';
    }

    $lines[] = SITE_NAME . ' · ' . SITE_TAGLINE;
    $lines[] = SITE_ADDRESS;
    $lines[] = SITE_PHONE . ' · ' . SITE_EMAIL;
    $lines[] = SITE_URL;

    return implode("\n", $lines);
}

/**
 * @param array<string, string> $fields
 * @return list<array{label: string, value: string}>
 */
function kora_quote_email_rows(array $fields): array
{
    $rows = [
        ['label' => 'Name', 'value' => $fields['name']],
    ];

    if (trim($fields['organization'] ?? '') !== '') {
        $rows[] = ['label' => 'Organization', 'value' => $fields['organization']];
    }

    $rows[] = ['label' => 'Phone / WhatsApp', 'value' => $fields['phone']];
    $rows[] = ['label' => 'Email', 'value' => $fields['email']];
    $rows[] = ['label' => 'Event type', 'value' => $fields['event_type']];
    $rows[] = ['label' => 'Product', 'value' => $fields['product']];
    $rows[] = ['label' => 'Quantity', 'value' => $fields['quantity']];
    $rows[] = ['label' => 'Event date', 'value' => kora_email_format_date($fields['event_date'])];
    $rows[] = ['label' => 'Message', 'value' => $fields['message']];

    return $rows;
}

/**
 * @param list<array{name: string, mime: string, size: int, is_image: bool, cid: string, token?: string, view_url?: string, download_url?: string}> $files
 */
function kora_email_attachments_html(array $files, bool $embedImages): string
{
    if ($files === []) {
        return '<p style="margin:0;font-size:14px;line-height:1.5;color:#6f7388;">No inspiration files were attached.</p>';
    }

    $navy = '#333652';
    $copper = '#a66a3d';
    $chipBg = '#f7f0ea';
    $line = '#e6e7eb';

    $cards = '';
    $imageFiles = [];

    foreach ($files as $file) {
        $ext = strtoupper((string) pathinfo($file['name'], PATHINFO_EXTENSION));
        $sizeLabel = $file['size'] > 0 ? kora_email_format_bytes($file['size']) : '';
        $meta = trim($ext . ($sizeLabel !== '' ? ' · ' . $sizeLabel : ''));
        $viewUrl = trim((string) ($file['view_url'] ?? ''));
        $downloadUrl = trim((string) ($file['download_url'] ?? ''));

        $actions = '';
        if ($viewUrl !== '' || $downloadUrl !== '') {
            $buttons = '';
            if ($viewUrl !== '') {
                $buttons .=
                    '<a href="' . kora_email_escape($viewUrl) . '" target="_blank" rel="noopener noreferrer" '
                    . 'style="display:inline-block;padding:8px 14px;margin:0 8px 0 0;border-radius:999px;background:' . $navy . ';color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;line-height:1.2;">'
                    . 'View'
                    . '</a>';
            }
            if ($downloadUrl !== '') {
                $buttons .=
                    '<a href="' . kora_email_escape($downloadUrl) . '" target="_blank" rel="noopener noreferrer" '
                    . 'style="display:inline-block;padding:8px 14px;border-radius:999px;background:' . $copper . ';color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;line-height:1.2;">'
                    . 'Download'
                    . '</a>';
            }
            $actions =
                '<tr><td colspan="2" style="padding:0 14px 12px;font-family:Arial,Helvetica,sans-serif;">'
                . $buttons
                . '</td></tr>';
        }

        $cards .=
            '<tr>'
            . '<td style="padding:0 0 10px;">'
            . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid ' . $line . ';border-radius:12px;background:#ffffff;">'
            . '<tr>'
            . '<td width="52" valign="middle" style="padding:12px 0 12px 12px;">'
            . '<div style="width:40px;height:40px;border-radius:10px;background:' . $chipBg . ';color:' . $copper . ';font-family:Arial,Helvetica,sans-serif;font-size:11px;font-weight:700;letter-spacing:0.04em;text-align:center;line-height:40px;">'
            . kora_email_escape($ext !== '' ? $ext : 'FILE')
            . '</div>'
            . '</td>'
            . '<td valign="middle" style="padding:12px 14px;font-family:Arial,Helvetica,sans-serif;">'
            . '<p style="margin:0 0 2px;font-size:15px;line-height:1.35;color:' . $navy . ';font-weight:700;word-break:break-word;">'
            . kora_email_escape($file['name'])
            . '</p>'
            . '<p style="margin:0;font-size:12px;line-height:1.4;color:#6f7388;">'
            . kora_email_escape($meta !== '' ? $meta : 'Attached file')
            . ($file['is_image'] ? ' · Image' : '')
            . '</p>'
            . '</td>'
            . '</tr>'
            . $actions
            . '</table>'
            . '</td>'
            . '</tr>';

        if ($embedImages && $file['is_image']) {
            $imageFiles[] = $file;
        }
    }

    $html =
        '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">'
        . $cards
        . '</table>';

    if ($imageFiles !== []) {
        $rows = '';
        for ($i = 0, $n = count($imageFiles); $i < $n; $i += 2) {
            $rows .= '<tr>';
            for ($j = 0; $j < 2; $j++) {
                $file = $imageFiles[$i + $j] ?? null;
                if ($file === null) {
                    $rows .= '<td width="50%" style="padding:6px;width:50%;">&nbsp;</td>';
                    continue;
                }

                $viewUrl = trim((string) ($file['view_url'] ?? ''));
                $imgSrc = $viewUrl !== ''
                    ? kora_email_escape($viewUrl)
                    : ('cid:' . kora_email_escape($file['cid']));
                $imgLinkOpen = $viewUrl !== ''
                    ? '<a href="' . kora_email_escape($viewUrl) . '" target="_blank" rel="noopener noreferrer" style="text-decoration:none;color:inherit;">'
                    : '';
                $imgLinkClose = $viewUrl !== '' ? '</a>' : '';

                $rows .=
                    '<td width="50%" valign="top" style="padding:6px;width:50%;">'
                    . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border:1px solid ' . $line . ';border-radius:12px;overflow:hidden;background:#f3f4f6;">'
                    . '<tr><td style="padding:0;line-height:0;font-size:0;">'
                    . $imgLinkOpen
                    . '<img src="' . $imgSrc . '" alt="' . kora_email_escape($file['name']) . '" width="260" style="display:block;width:100%;max-width:260px;height:auto;border:0;">'
                    . $imgLinkClose
                    . '</td></tr>'
                    . '<tr><td style="padding:8px 10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:1.35;color:' . $navy . ';word-break:break-word;">'
                    . kora_email_escape($file['name'])
                    . ($viewUrl !== ''
                        ? '<br><a href="' . kora_email_escape($viewUrl) . '" target="_blank" rel="noopener noreferrer" style="color:' . $copper . ';font-weight:700;text-decoration:none;">Open full size</a>'
                            . ' · <a href="' . kora_email_escape((string) ($file['download_url'] ?? $viewUrl)) . '" target="_blank" rel="noopener noreferrer" style="color:' . $copper . ';font-weight:700;text-decoration:none;">Download</a>'
                        : '')
                    . '</td></tr>'
                    . '</table>'
                    . '</td>';
            }
            $rows .= '</tr>';
        }

        $html .=
            '<p style="margin:18px 0 10px;font-family:Arial,Helvetica,sans-serif;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">Image previews</p>'
            . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">'
            . $rows
            . '</table>';
    }

    return $html;
}

/**
 * @param array<string, string> $fields
 * @param list<array{name: string, mime: string, size: int, is_image: bool, cid: string}> $files
 * @param list<array{label: string, href: string}> $actions
 */
function kora_quote_email_html(
    string $title,
    string $preheader,
    string $intro,
    array $fields,
    array $files,
    array $actions,
    bool $isAdmin
): string {
    $navy = '#333652';
    $copper = '#a66a3d';
    $bg = '#f0eeea';
    $white = '#ffffff';
    $muted = '#6f7388';
    $line = '#e4e1db';
    $soft = '#f7f0ea';

    $stats = [
        ['label' => 'Product', 'value' => $fields['product']],
        ['label' => 'Quantity', 'value' => $fields['quantity']],
        ['label' => 'Event date', 'value' => kora_email_format_date($fields['event_date'])],
    ];

    $statsHtml = '<tr>';
    foreach ($stats as $stat) {
        $statsHtml .=
            '<td width="33.33%" valign="top" style="padding:6px;width:33.33%;">'
            . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:' . $soft . ';border-radius:12px;">'
            . '<tr><td style="padding:14px 12px;font-family:Arial,Helvetica,sans-serif;text-align:center;">'
            . '<p style="margin:0 0 4px;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">'
            . kora_email_escape($stat['label'])
            . '</p>'
            . '<p style="margin:0;font-size:15px;line-height:1.35;color:' . $navy . ';font-weight:700;word-break:break-word;">'
            . kora_email_escape($stat['value'])
            . '</p>'
            . '</td></tr></table></td>';
    }
    $statsHtml .= '</tr>';

    $detailRows = '';
    foreach (kora_quote_email_rows($fields) as $row) {
        if (in_array($row['label'], ['Product', 'Quantity', 'Event date'], true)) {
            continue;
        }

        $detailRows .=
            '<tr>'
            . '<td style="padding:14px 0;border-bottom:1px solid ' . $line . ';font-family:Arial,Helvetica,sans-serif;">'
            . '<p style="margin:0 0 4px;font-size:11px;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">'
            . kora_email_escape($row['label'])
            . '</p>'
            . '<p style="margin:0;font-size:16px;line-height:1.55;color:' . $navy . ';white-space:pre-wrap;word-break:break-word;">'
            . nl2br(kora_email_escape($row['value']), false)
            . '</p>'
            . '</td>'
            . '</tr>';
    }

    $actionHtml = '';
    foreach ($actions as $index => $action) {
        $isPrimary = $index === 0;
        $bgColor = $isPrimary ? $copper : $navy;
        $actionHtml .=
            '<td valign="top" style="padding:' . ($index === 0 ? '0 6px 0 0' : '0 0 0 6px') . ';width:50%;">'
            . '<a href="' . kora_email_escape($action['href']) . '" style="display:block;background:' . $bgColor . ';color:' . $white . ';text-decoration:none;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:700;line-height:1.25;padding:14px 12px;border-radius:999px;text-align:center;">'
            . kora_email_escape($action['label'])
            . '</a>'
            . '</td>';
    }

    $attachmentsBlock = kora_email_attachments_html($files, $isAdmin);
    $eyebrow = $isAdmin ? 'Studio inbox' : 'Request received';
    $fileCountLabel = count($files) === 1 ? '1 file attached' : count($files) . ' files attached';

    return '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>' . kora_email_escape($title) . '</title>
<style type="text/css">
  html, body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
  body { background: ' . $bg . '; -webkit-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { border: 0; line-height: 100%; outline: none; text-decoration: none; }
  @media only screen and (max-width: 620px) {
    .email-shell { width: 100% !important; }
    .email-pad { padding-left: 20px !important; padding-right: 20px !important; }
    .email-title { font-size: 26px !important; }
    .email-stat { display: block !important; width: 100% !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background:' . $bg . ';">
<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;">'
    . kora_email_escape($preheader) .
'</div>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:' . $bg . ';width:100%;">
  <tr>
    <td align="center" style="padding:28px 12px;">
      <table role="presentation" class="email-shell" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;max-width:640px;background:' . $white . ';border-radius:20px;overflow:hidden;box-shadow:0 12px 40px rgba(51,54,82,0.08);">
        <tr>
          ' . kora_email_brand_header_html($eyebrow) . '
        </tr>
        <tr>
          <td style="height:5px;background:' . $copper . ';font-size:0;line-height:0;">&nbsp;</td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:32px 32px 8px;font-family:Arial,Helvetica,sans-serif;">
            <h1 class="email-title" style="margin:0 0 10px;font-family:Georgia,\'Times New Roman\',serif;font-size:30px;line-height:1.2;color:' . $navy . ';font-weight:700;">'
    . kora_email_escape($title) .
            '</h1>
            <p style="margin:0 0 22px;font-size:16px;line-height:1.6;color:' . $navy . ';">'
    . kora_email_escape($intro) .
            '</p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin:0 0 8px;">'
    . $statsHtml .
            '</table>
          </td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:12px 32px 8px;font-family:Arial,Helvetica,sans-serif;">
            <p style="margin:0 0 8px;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">Request details</p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">'
    . $detailRows .
            '</table>
          </td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:20px 32px 8px;font-family:Arial,Helvetica,sans-serif;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>
                <td style="padding:0 0 12px;">
                  <p style="margin:0;font-size:12px;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">Inspiration files</p>
                  <p style="margin:4px 0 0;font-size:13px;color:' . $muted . ';">' . kora_email_escape($fileCountLabel) . ($isAdmin && $files !== [] ? ' · tap View or Download below (also attached to this email)' : '') . '</p>
                </td>
              </tr>
              <tr>
                <td>'
    . $attachmentsBlock .
                '</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:24px 32px 8px;font-family:Arial,Helvetica,sans-serif;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
              <tr>'
    . $actionHtml .
              '</tr>
            </table>'
    . (!$isAdmin ? kora_email_regards_html() : '') .
          '</td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:22px 32px 26px;background:#f7f6f3;font-family:Arial,Helvetica,sans-serif;text-align:center;">
            <a href="' . kora_email_escape(rtrim(SITE_URL, '/')) . '" style="text-decoration:none;display:inline-block;margin:0 0 10px;">
              <img src="' . kora_email_escape(kora_email_logo_src('mark')) . '" alt="' . kora_email_escape(SITE_NAME) . '" width="140" height="62" style="display:block;width:140px;height:auto;margin:0 auto;border:0;outline:none;">
            </a>
            <p style="margin:0 0 4px;font-size:14px;color:' . $navy . ';font-weight:700;">' . kora_email_escape(SITE_NAME) . '</p>
            <p style="margin:0 0 6px;font-size:13px;line-height:1.5;color:' . $muted . ';">' . kora_email_escape(SITE_ADDRESS) . '</p>
            <p style="margin:0;font-size:13px;line-height:1.6;color:' . $muted . ';">
              <a href="tel:' . kora_email_escape(SITE_PHONE_LINK) . '" style="color:' . $navy . ';text-decoration:none;">' . kora_email_escape(SITE_PHONE) . '</a>
              &nbsp;·&nbsp;
              <a href="mailto:' . kora_email_escape(SITE_EMAIL) . '" style="color:' . $navy . ';text-decoration:none;">' . kora_email_escape(SITE_EMAIL) . '</a>
            </p>
            <p style="margin:10px 0 0;font-size:13px;">
              <a href="' . kora_email_escape(SITE_URL) . '" style="color:' . $copper . ';text-decoration:none;font-weight:700;">Visit website</a>
            </p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
}

/**
 * Modern newsletter subscription notice for the studio inbox.
 */
function kora_newsletter_email_html(string $email): string
{
    $navy = '#333652';
    $copper = '#a66a3d';

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Newsletter subscription</title></head>'
        . '<body style="margin:0;padding:24px;background:#f0eeea;font-family:Arial,Helvetica,sans-serif;color:' . $navy . ';">'
        . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:560px;margin:0 auto;background:#ffffff;border-radius:18px;overflow:hidden;">'
        . kora_email_simple_header_html('Newsletter')
        . '<tr><td style="padding:24px;"><p style="margin:0 0 12px;font-size:16px;line-height:1.55;">Someone subscribed from the website.</p>'
        . '<p style="margin:0;padding:14px 16px;background:#f7f0ea;border-radius:12px;font-size:16px;font-weight:700;word-break:break-word;">' . kora_email_escape($email) . '</p>'
        . '<p style="margin:16px 0 0;font-size:13px;color:#6f7388;">Submitted ' . kora_email_escape(date('j F Y, H:i')) . '</p></td></tr>'
        . '</table></body></html>';
}

/**
 * @param array<string, string> $fields
 * @return array{subject: string, text: string, html: string}
 */
function kora_contact_admin_email(array $fields): array
{
    $name = (string) ($fields['name'] ?? '');
    $subjectLine = (string) ($fields['subject'] ?? 'Website enquiry');
    $subject = 'Contact · ' . $subjectLine . ' · ' . $name;

    $text = "New contact message from the website.\n\n"
        . 'Name: ' . $name . "\n"
        . 'Organization: ' . ((string) ($fields['organization'] ?? '') !== '' ? $fields['organization'] : '—') . "\n"
        . 'Email: ' . ($fields['email'] ?? '') . "\n"
        . 'Phone: ' . ($fields['phone'] ?? '') . "\n"
        . 'Subject: ' . $subjectLine . "\n\n"
        . "Message:\n" . ($fields['message'] ?? '') . "\n\n"
        . 'Submitted: ' . date('Y-m-d H:i:s') . "\n";

    return [
        'subject' => $subject,
        'text' => $text,
        'html' => kora_contact_email_html($fields, true),
    ];
}

/**
 * @param array<string, string> $fields
 * @return array{subject: string, text: string, html: string}
 */
function kora_contact_confirmation_email(array $fields): array
{
    $firstName = explode(' ', trim((string) ($fields['name'] ?? '')), 2)[0];
    if ($firstName === '') {
        $firstName = 'there';
    }

    $subject = 'We received your message — ' . SITE_NAME;

    $text = 'Hi ' . $firstName . ",\n\n"
        . "Thank you for contacting KORA. We have received your message and will reply within one business day.\n\n"
        . 'Subject: ' . ($fields['subject'] ?? '') . "\n\n"
        . "Your message:\n" . ($fields['message'] ?? '') . "\n\n"
        . 'Need a quicker answer? WhatsApp us: ' . SITE_WHATSAPP . "\n\n"
        . kora_email_regards_text() . "\n\n"
        . SITE_NAME . ' — ' . SITE_ADDRESS . "\n";

    return [
        'subject' => $subject,
        'text' => $text,
        'html' => kora_contact_email_html($fields, false),
    ];
}

/**
 * @param array<string, string> $fields
 */
function kora_contact_email_html(array $fields, bool $isAdmin): string
{
    $navy = '#333652';
    $copper = '#a66a3d';
    $name = kora_email_escape((string) ($fields['name'] ?? ''));
    $org = trim((string) ($fields['organization'] ?? ''));
    $email = kora_email_escape((string) ($fields['email'] ?? ''));
    $phone = kora_email_escape((string) ($fields['phone'] ?? ''));
    $subject = kora_email_escape((string) ($fields['subject'] ?? ''));
    $message = nl2br(kora_email_escape((string) ($fields['message'] ?? '')), false);

    $heading = $isAdmin ? 'New contact message' : 'Message received';
    $intro = $isAdmin
        ? 'A visitor sent a message from the Contact Us page. Reply to this email to reach them directly.'
        : 'Thanks for writing to KORA. We will get back to you shortly!.';

    $rows = '';
    $rowData = [
        'Name' => $name,
        'Organization' => $org !== '' ? kora_email_escape($org) : '—',
        'Email' => $email,
        'Phone' => $phone,
        'Subject' => $subject,
    ];

    foreach ($rowData as $label => $value) {
        $rows .= '<tr>'
            . '<td style="padding:10px 0;border-bottom:1px solid #eceae6;font-size:12px;letter-spacing:0.06em;text-transform:uppercase;color:#6f7388;width:34%;vertical-align:top;">' . kora_email_escape($label) . '</td>'
            . '<td style="padding:10px 0;border-bottom:1px solid #eceae6;font-size:15px;color:' . $navy . ';font-weight:600;vertical-align:top;">' . $value . '</td>'
            . '</tr>';
    }

    $actions = '';
    if ($isAdmin) {
        $actions = '<p style="margin:22px 0 0;">'
            . '<a href="mailto:' . $email . '?subject=' . rawurlencode('Re: ' . (string) ($fields['subject'] ?? 'Your KORA enquiry')) . '" style="display:inline-block;padding:12px 18px;background:' . $copper . ';color:#fff;text-decoration:none;border-radius:999px;font-weight:700;font-size:14px;">Reply by email</a>'
            . '</p>';
    } else {
        $actions = '<p style="margin:22px 0 0;">'
            . '<a href="' . kora_email_escape(SITE_WHATSAPP) . '" style="display:inline-block;padding:12px 18px;background:' . $copper . ';color:#fff;text-decoration:none;border-radius:999px;font-weight:700;font-size:14px;">Message on WhatsApp</a>'
            . '</p>';
    }

    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>' . kora_email_escape($heading) . '</title></head>'
        . '<body style="margin:0;padding:24px;background:#f0eeea;font-family:Arial,Helvetica,sans-serif;color:' . $navy . ';">'
        . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:18px;overflow:hidden;">'
        . kora_email_simple_header_html('Contact')
        . '<tr><td style="padding:28px;">'
        . '<h1 style="margin:0 0 12px;font-size:24px;line-height:1.25;color:' . $navy . ';font-weight:700;">' . kora_email_escape($heading) . '</h1>'
        . '<p style="margin:0 0 18px;font-size:16px;line-height:1.55;">' . kora_email_escape($intro) . '</p>'
        . '<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">' . $rows . '</table>'
        . '<div style="margin-top:20px;padding:16px 18px;background:#f7f0ea;border-radius:14px;">'
        . '<p style="margin:0 0 8px;font-size:12px;letter-spacing:0.06em;text-transform:uppercase;color:#6f7388;font-weight:700;">Message</p>'
        . '<p style="margin:0;font-size:15px;line-height:1.65;color:' . $navy . ';">' . $message . '</p>'
        . '</div>'
        . $actions
        . (!$isAdmin ? kora_email_regards_html() : '')
        . '<p style="margin:22px 0 0;font-size:13px;color:#6f7388;">' . kora_email_escape(SITE_NAME) . ' · ' . kora_email_escape(SITE_ADDRESS) . '</p>'
        . '</td></tr></table></body></html>';
}
