<?php

declare(strict_types=1);

/**
 * Local SMTP credentials for KORA outbound mail.
 * Do not commit this file.
 *
 * Note: kora.fraittech.co.ke:465 is closed on this VPS.
 * Hostinger auth currently rejects the mailbox password, so local
 * Postfix (127.0.0.1:25) is used as a working fallback.
 */
return [
    'host' => 'smtp.hostinger.com',
    'port' => 465,
    'encryption' => 'ssl',
    'username' => 'info@kora.fraittech.co.ke',
    'password' => 'Vicfirth2026!!!',
    'from_email' => 'info@kora.fraittech.co.ke',
    'from_name' => 'KORA',
    'timeout' => 30,
    'transports' => [
        [
            'host' => 'smtp.hostinger.com',
            'port' => 465,
            'encryption' => 'ssl',
            'username' => 'info@kora.fraittech.co.ke',
            'password' => 'Vicfirth2026!!!',
        ],
        [
            // Panel lists kora.fraittech.co.ke:465, but only port 25 answers here.
            'host' => '127.0.0.1',
            'port' => 25,
            'encryption' => 'none',
            'username' => '',
            'password' => '',
        ],
    ],
];
