<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$privacy = cms_section('privacy');
$privacy_title = (string) ($privacy['title'] ?? 'Privacy');
$privacy_paragraphs = cms_list('privacy', 'paragraphs', [
    'KORA respects the information you share when requesting a quotation or contacting our studio. We use your details only to respond to enquiries, prepare quotes, and deliver your order.',
    'Information collected through our contact form may include your name, organisation, phone number, email address, event details, and design brief. We do not sell or share this information with third parties except where required to complete your order or comply with law.',
    'If you have questions about how we handle your data, contact us at ' . SITE_EMAIL . ' or call ' . SITE_PHONE . '.',
]);

$current_page = 'privacy';
$page_title = page_title($privacy_title !== '' ? $privacy_title : 'Privacy');
$page_description = 'Privacy policy for KORA custom awards studio in Nanyuki, Laikipia.';

require __DIR__ . '/includes/header.php';
?>
<section class="page-simple section">
    <div class="container">
        <h1><?= htmlspecialchars($privacy_title) ?></h1>
        <?php foreach ($privacy_paragraphs as $index => $paragraph): ?>
            <?php
            if (!is_string($paragraph) || trim($paragraph) === '') {
                continue;
            }
            // Last paragraph: auto-link contact details when using default contact line
            if ($index === count($privacy_paragraphs) - 1 && str_contains($paragraph, SITE_EMAIL)) {
                $safe = htmlspecialchars($paragraph);
                $safe = str_replace(
                    htmlspecialchars(SITE_EMAIL),
                    '<a href="mailto:' . htmlspecialchars(SITE_EMAIL) . '">' . htmlspecialchars(SITE_EMAIL) . '</a>',
                    $safe
                );
                $safe = str_replace(
                    htmlspecialchars(SITE_PHONE),
                    '<a href="tel:' . htmlspecialchars(SITE_PHONE_LINK) . '">' . htmlspecialchars(SITE_PHONE) . '</a>',
                    $safe
                );
                echo '<p class="text-body">' . $safe . '</p>';
                continue;
            }
            ?>
            <p class="text-body"><?= htmlspecialchars($paragraph) ?></p>
        <?php endforeach; ?>
        <p><a class="btn btn--ghost" href="/">Back to home</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
