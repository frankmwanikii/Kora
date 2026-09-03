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

/**
 * @param array<string, string> $fields
 * @param list<string> $attachmentNames
 * @return array{subject: string, text: string, html: string}
 */
function kora_quote_admin_email(array $fields, array $attachmentNames): array
{
    $name = $fields['name'];
    $subject = 'New quotation request from ' . $name;
    $preheader = $fields['product'] . ' · qty ' . $fields['quantity'] . ' · ' . kora_email_format_date($fields['event_date']);

    return [
        'subject' => $subject,
        'text' => kora_quote_email_text($fields, $attachmentNames, true),
        'html' => kora_quote_email_html(
            'New quotation request',
            $preheader,
            'A new quotation request has arrived from ' . $name . '. Reply to this email to contact them directly.',
            $fields,
            $attachmentNames,
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
 * @param list<string> $attachmentNames
 * @return array{subject: string, text: string, html: string}
 */
function kora_quote_confirmation_email(array $fields, array $attachmentNames): array
{
    $firstName = explode(' ', trim($fields['name']), 2)[0];
    $subject = 'We received your quotation request';
    $preheader = 'Thank you, ' . $firstName . '. KORA will review your brief and reply within 24 hours.';

    return [
        'subject' => $subject,
        'text' => kora_quote_email_text($fields, $attachmentNames, false),
        'html' => kora_quote_email_html(
            'Thank you, ' . $firstName,
            $preheader,
            'We have received your quotation request and will respond within 24 hours. A copy of what you sent is below.',
            $fields,
            $attachmentNames,
            [
                [
                    'label' => 'WhatsApp KORA',
                    'href' => 'https://wa.me/254790355707',
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
 * @param array<string, string> $fields
 * @param list<string> $attachmentNames
 */
function kora_quote_email_text(array $fields, array $attachmentNames, bool $isAdmin): string
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

    $rows = kora_quote_email_rows($fields, $attachmentNames);

    foreach ($rows as $row) {
        $lines[] = $row['label'] . ': ' . $row['value'];
    }

    $lines[] = '';
    $lines[] = SITE_NAME . ' · ' . SITE_TAGLINE;
    $lines[] = SITE_ADDRESS;
    $lines[] = SITE_PHONE . ' · ' . SITE_EMAIL;
    $lines[] = SITE_URL;

    return implode("\n", $lines);
}

/**
 * @param array<string, string> $fields
 * @param list<string> $attachmentNames
 * @return list<array{label: string, value: string}>
 */
function kora_quote_email_rows(array $fields, array $attachmentNames): array
{
    $rows = [
        ['label' => 'Name', 'value' => $fields['name']],
    ];

    if (trim($fields['organization']) !== '') {
        $rows[] = ['label' => 'Organization', 'value' => $fields['organization']];
    }

    $rows[] = ['label' => 'Phone or WhatsApp', 'value' => $fields['phone']];
    $rows[] = ['label' => 'Email', 'value' => $fields['email']];
    $rows[] = ['label' => 'Event type', 'value' => $fields['event_type']];
    $rows[] = ['label' => 'Product', 'value' => $fields['product']];
    $rows[] = ['label' => 'Quantity', 'value' => $fields['quantity']];
    $rows[] = ['label' => 'Event date', 'value' => kora_email_format_date($fields['event_date'])];
    $rows[] = ['label' => 'Message', 'value' => $fields['message']];
    $rows[] = [
        'label' => 'Inspiration files',
        'value' => $attachmentNames === [] ? 'None attached' : implode(', ', $attachmentNames),
    ];

    return $rows;
}

/**
 * @param array<string, string> $fields
 * @param list<string> $attachmentNames
 * @param list<array{label: string, href: string}> $actions
 */
function kora_quote_email_html(
    string $title,
    string $preheader,
    string $intro,
    array $fields,
    array $attachmentNames,
    array $actions,
    bool $isAdmin
): string {
    $navy = '#333652';
    $copper = '#a66a3d';
    $bg = '#e9eaec';
    $white = '#ffffff';
    $muted = '#6f7388';
    $line = '#d8d9dc';

    $detailRows = '';
    foreach (kora_quote_email_rows($fields, $attachmentNames) as $row) {
        $detailRows .=
            '<tr>'
            . '<td class="email-label" style="padding:12px 0 4px;font-size:12px;line-height:1.4;letter-spacing:0.08em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">'
            . kora_email_escape($row['label'])
            . '</td>'
            . '</tr>'
            . '<tr>'
            . '<td class="email-value" style="padding:0 0 12px;font-size:16px;line-height:1.55;color:' . $navy . ';border-bottom:1px solid ' . $line . ';white-space:pre-wrap;word-break:break-word;">'
            . nl2br(kora_email_escape($row['value']), false)
            . '</td>'
            . '</tr>';
    }

    $actionHtml = '';
    foreach ($actions as $index => $action) {
        $isPrimary = $index === 0;
        $bgColor = $isPrimary ? $copper : $navy;
        $padding = $index === 0 ? '0 0 10px' : '0';
        $actionHtml .=
            '<tr>'
            . '<td class="email-btn-wrap" style="padding:' . $padding . ';">'
            . '<a href="' . kora_email_escape($action['href']) . '" style="display:block;width:100%;box-sizing:border-box;background:' . $bgColor . ';color:' . $white . ';text-decoration:none;font-size:16px;font-weight:700;line-height:1.25;padding:14px 18px;border-radius:4px;text-align:center;">'
            . kora_email_escape($action['label'])
            . '</a>'
            . '</td>'
            . '</tr>';
    }

    $eyebrow = $isAdmin ? 'KORA studio inbox' : 'Quotation confirmation';

    return '<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>' . kora_email_escape($title) . '</title>
<style type="text/css">
  html, body { margin: 0 !important; padding: 0 !important; height: 100% !important; width: 100% !important; }
  body { background-color: ' . $bg . '; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
  table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
  img { border: 0; line-height: 100%; outline: none; text-decoration: none; }
  a { color: ' . $copper . '; }
  @media only screen and (max-width: 620px) {
    .email-shell { width: 100% !important; max-width: 100% !important; }
    .email-pad { padding: 28px 20px !important; }
    .email-header { padding: 28px 20px 22px !important; }
    .email-title { font-size: 26px !important; line-height: 1.25 !important; }
    .email-intro { font-size: 16px !important; }
    .email-btn-wrap a { font-size: 16px !important; padding: 16px 18px !important; }
  }
  @media only screen and (min-width: 621px) {
    .email-shell { width: 600px !important; }
    .email-title { font-size: 32px !important; }
  }
</style>
</head>
<body style="margin:0;padding:0;background-color:' . $bg . ';width:100%;">
<div style="display:none;font-size:1px;line-height:1px;max-height:0;max-width:0;opacity:0;overflow:hidden;mso-hide:all;">
  ' . kora_email_escape($preheader) . '
</div>
<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:' . $bg . ';width:100%;margin:0;padding:0;">
  <tr>
    <td align="center" style="padding:24px 12px;">
      <!--[if mso]>
      <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" align="center"><tr><td>
      <![endif]-->
      <table role="presentation" class="email-shell" cellpadding="0" cellspacing="0" border="0" width="100%" style="width:100%;max-width:600px;background-color:' . $white . ';border-radius:8px;overflow:hidden;">
        <tr>
          <td class="email-header" style="background-color:' . $navy . ';padding:32px 40px 26px;">
            <p style="margin:0 0 8px;font-family:Georgia,\'Times New Roman\',serif;font-size:13px;letter-spacing:0.18em;text-transform:uppercase;color:' . $copper . ';font-weight:700;">' . kora_email_escape($eyebrow) . '</p>
            <p style="margin:0;font-family:Georgia,\'Times New Roman\',serif;font-size:28px;line-height:1.2;color:' . $white . ';font-weight:700;" class="email-title">' . kora_email_escape(SITE_NAME) . '</p>
            <p style="margin:8px 0 0;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:1.45;color:#d7d9e2;">' . kora_email_escape(SITE_TAGLINE) . ' · ' . kora_email_escape(SITE_LOCATION) . '</p>
          </td>
        </tr>
        <tr>
          <td style="height:4px;background-color:' . $copper . ';font-size:0;line-height:0;">&nbsp;</td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:36px 40px 20px;font-family:Arial,Helvetica,sans-serif;">
            <h1 class="email-title" style="margin:0 0 12px;font-family:Georgia,\'Times New Roman\',serif;font-size:28px;line-height:1.25;color:' . $navy . ';font-weight:700;">' . kora_email_escape($title) . '</h1>
            <p class="email-intro" style="margin:0 0 28px;font-size:17px;line-height:1.6;color:' . $navy . ';">' . kora_email_escape($intro) . '</p>
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
              ' . $detailRows . '
            </table>
          </td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:8px 40px 36px;font-family:Arial,Helvetica,sans-serif;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
              ' . $actionHtml . '
            </table>
          </td>
        </tr>
        <tr>
          <td class="email-pad" style="padding:24px 40px 28px;background-color:' . $bg . ';font-family:Arial,Helvetica,sans-serif;text-align:center;">
            <p style="margin:0 0 6px;font-size:14px;line-height:1.5;color:' . $navy . ';font-weight:700;">' . kora_email_escape(SITE_NAME) . '</p>
            <p style="margin:0 0 6px;font-size:13px;line-height:1.5;color:' . $muted . ';">' . kora_email_escape(SITE_ADDRESS) . '</p>
            <p style="margin:0;font-size:13px;line-height:1.6;color:' . $muted . ';">
              <a href="tel:' . kora_email_escape(SITE_PHONE_LINK) . '" style="color:' . $navy . ';text-decoration:none;">' . kora_email_escape(SITE_PHONE) . '</a>
              &nbsp;·&nbsp;
              <a href="mailto:' . kora_email_escape(SITE_EMAIL) . '" style="color:' . $navy . ';text-decoration:none;">' . kora_email_escape(SITE_EMAIL) . '</a>
            </p>
            <p style="margin:10px 0 0;font-size:13px;line-height:1.5;">
              <a href="' . kora_email_escape(SITE_URL) . '" style="color:' . $copper . ';text-decoration:none;font-weight:700;">' . kora_email_escape(SITE_URL) . '</a>
            </p>
          </td>
        </tr>
      </table>
      <!--[if mso]>
      </td></tr></table>
      <![endif]-->
    </td>
  </tr>
</table>
</body>
</html>';
}
