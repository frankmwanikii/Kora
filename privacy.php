<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$current_page = 'privacy';
$page_title = page_title('Privacy');
$page_description = 'Privacy policy for KORA custom awards studio in Nanyuki, Laikipia.';

require __DIR__ . '/includes/header.php';
?>
<section class="page-simple section">
    <div class="container">
        <h1>Privacy</h1>
        <p class="text-body">KORA respects the information you share when requesting a quotation or contacting our studio. We use your details only to respond to enquiries, prepare quotes, and deliver your order.</p>
        <p class="text-body">Information collected through our contact form may include your name, organisation, phone number, email address, event details, and design brief. We do not sell or share this information with third parties except where required to complete your order or comply with law.</p>
        <p class="text-body">If you have questions about how we handle your data, contact us at <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a> or call <a href="tel:<?= SITE_PHONE_LINK ?>"><?= SITE_PHONE ?></a>.</p>
        <p><a class="btn btn--ghost" href="/">Back to home</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
