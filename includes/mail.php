<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/email-templates.php';

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
    $to = str_replace(["\r", "\n"], '', $to);
    $replyTo = str_replace(["\r", "\n"], '', $replyTo);
    $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';
    $plainBody = str_replace(["\r\n", "\r", "\n"], "\r\n", $body);
    $htmlBody = $htmlBody !== '' ? $htmlBody : kora_plain_to_html($plainBody);

    $altBoundary = '=_KoraAlt_' . bin2hex(random_bytes(12));
    $mixedBoundary = '=_KoraMix_' . bin2hex(random_bytes(12));
    $alternative = kora_mime_alternative($altBoundary, $plainBody, $htmlBody);

    $from = SITE_NAME . ' <' . SITE_EMAIL . '>';

    if ($attachments === []) {
        $headers = [
            'From' => $from,
            'Reply-To' => $replyTo,
            'MIME-Version' => '1.0',
            'Content-Type' => 'multipart/alternative; boundary="' . $altBoundary . '"',
            'X-Mailer' => 'KORA website',
        ];

        return mail($to, $encodedSubject, $alternative, $headers);
    }

    $headers = [
        'From' => $from,
        'Reply-To' => $replyTo,
        'MIME-Version' => '1.0',
        'Content-Type' => 'multipart/mixed; boundary="' . $mixedBoundary . '"',
        'X-Mailer' => 'KORA website',
    ];

    $message = 'This is a multi-part message in MIME format.' . "\r\n\r\n"
        . '--' . $mixedBoundary . "\r\n"
        . 'Content-Type: multipart/alternative; boundary="' . $altBoundary . '"' . "\r\n\r\n"
        . $alternative;

    foreach ($attachments as $attachment) {
        $filename = str_replace(["\r", "\n", '"'], '', $attachment['name']);
        $mime = $attachment['mime'] !== '' ? $attachment['mime'] : 'application/octet-stream';
        $encodedName = rawurlencode($filename);

        $message .= '--' . $mixedBoundary . "\r\n"
            . 'Content-Type: ' . $mime . "\r\n"
            . 'Content-Transfer-Encoding: base64' . "\r\n"
            . 'Content-Disposition: attachment; filename="' . $filename . '"; filename*=' . "UTF-8''" . $encodedName . "\r\n\r\n"
            . chunk_split(base64_encode($attachment['data']), 76, "\r\n")
            . "\r\n";
    }

    $message .= '--' . $mixedBoundary . "--\r\n";

    return mail($to, $encodedSubject, $message, $headers);
}

function kora_mime_alternative(string $boundary, string $plainBody, string $htmlBody): string
{
    return '--' . $boundary . "\r\n"
        . 'Content-Type: text/plain; charset=UTF-8' . "\r\n"
        . 'Content-Transfer-Encoding: base64' . "\r\n\r\n"
        . chunk_split(base64_encode($plainBody), 76, "\r\n")
        . '--' . $boundary . "\r\n"
        . 'Content-Type: text/html; charset=UTF-8' . "\r\n"
        . 'Content-Transfer-Encoding: base64' . "\r\n\r\n"
        . chunk_split(base64_encode($htmlBody), 76, "\r\n")
        . '--' . $boundary . "--\r\n";
}

function kora_plain_to_html(string $plainBody): string
{
    return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"></head>'
        . '<body style="margin:0;padding:24px;font-family:Arial,Helvetica,sans-serif;color:#333652;background:#e9eaec;">'
        . '<div style="max-width:600px;margin:0 auto;background:#ffffff;padding:24px;border-radius:8px;">'
        . nl2br(kora_email_escape($plainBody), false)
        . '</div></body></html>';
}
