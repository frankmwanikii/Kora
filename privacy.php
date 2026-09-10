<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';

$privacy = cms_section('privacy');
$privacy_title = (string) ($privacy['title'] ?? 'Privacy Policy');
$privacy_updated = (string) ($privacy['updated'] ?? '10 September 2026');
$privacy_intro = (string) ($privacy['intro'] ?? 'This Privacy Policy explains how KORA (“we”, “us”, or “our”) collects, uses, stores, and protects personal information when you visit our website, request a quotation, contact our studio, subscribe to our newsletter, or otherwise communicate with us.');

$privacy_sections_default = [
    [
        'heading' => '1. Who we are',
        'paragraphs' => [
            'KORA is a custom awards studio based in Nanyuki, Laikipia, Kenya. We design and produce medals, awards, trophies, plaques, souvenirs, and related keepsakes for organisations, NGOs, corporates, schools, and sports teams.',
            'This website is operated by KORA. For privacy questions, please contact us using the details in the “How to contact us” section below.',
        ],
    ],
    [
        'heading' => '2. Scope of this policy',
        'paragraphs' => [
            'This policy applies to personal information collected through our website (including quotation and contact forms, newsletter signup, and WhatsApp links), by phone or email, and through files or design briefs you send us in connection with an enquiry or order.',
            'It does not cover third-party websites or apps that we may link to (for example Instagram, TikTok, Facebook, YouTube, or WhatsApp). Those services have their own privacy practices.',
        ],
    ],
    [
        'heading' => '3. Information we collect',
        'paragraphs' => [
            'We collect information you choose to provide, and limited technical information needed to operate the website securely.',
        ],
    ],
    [
        'heading' => '3.1 Information you provide',
        'paragraphs' => [
            'Quotation requests may include your name, organisation, phone or WhatsApp number, email address, event type, product interest, quantity, event date, project brief or message, and optional inspiration files (such as PDF, PNG, WebP, or JPG).',
            'Contact messages may include your name, organisation, email address, phone or WhatsApp number, subject, and message content.',
            'Newsletter signup collects your email address so we can send updates about collections, offers, and workshop news.',
            'If you message us on WhatsApp, phone, email, or social media, we process the details and conversation content you share so we can respond and manage your enquiry or order.',
            'For orders, we may also hold delivery or collection details, artwork, logos, engraving text, branding guidelines, and payment or invoicing references needed to fulfil your project.',
        ],
    ],
    [
        'heading' => '3.2 Information collected automatically',
        'paragraphs' => [
            'Like most websites, our hosting environment may record standard server logs such as IP address, browser type, device information, pages requested, referring URL, and date/time of access. We use this information for security, troubleshooting, and basic service operation.',
            'We do not currently use advertising cookies or third-party analytics trackers on the public website. If that changes, we will update this policy and, where required, provide appropriate notice or choices.',
        ],
    ],
    [
        'heading' => '4. How we use your information',
        'paragraphs' => [
            'We use personal information to:',
            'Respond to enquiries, prepare quotations, and discuss design options, materials, quantities, timelines, and pricing.',
            'Produce and deliver your medals, awards, souvenirs, or other ordered items, including using logos, names, and artwork you supply.',
            'Communicate with you about your brief, proofs, production status, delivery or collection, invoices, and after-sales support.',
            'Send newsletter updates if you have subscribed, and process unsubscribe requests.',
            'Protect our website, studio, and customers against spam, fraud, and misuse (including honeypot and basic validation checks on forms).',
            'Keep business records required for accounting, tax, warranty, dispute resolution, and legal compliance.',
            'Improve our products, service quality, and website experience based on the types of enquiries we receive.',
        ],
    ],
    [
        'heading' => '5. Legal bases and legitimate purposes',
        'paragraphs' => [
            'We process personal information where it is necessary to take steps at your request before entering a contract, to perform a contract with you, to pursue our legitimate business interests (such as responding to enquiries and operating a secure website), to send marketing you have opted into, or where we must comply with a legal obligation.',
            'You may choose not to provide certain information, but that may limit our ability to quote accurately or complete your order.',
        ],
    ],
    [
        'heading' => '6. How we share information',
        'paragraphs' => [
            'We do not sell your personal information.',
            'We may share information with trusted service providers who help us operate our business, such as website hosting, email delivery, domain or IT support, couriers, and payment or accounting tools — only as needed to provide those services and under appropriate confidentiality expectations.',
            'If you contact us through WhatsApp or social platforms, your message is also processed under those platforms’ terms and privacy policies.',
            'We may disclose information where required by law, regulation, court order, or to protect the rights, safety, or property of KORA, our customers, or others.',
            'If our studio is involved in a business transfer, reorganisation, or similar event, personal information may be transferred as part of that process, subject to continued privacy protections where reasonably possible.',
        ],
    ],
    [
        'heading' => '7. File uploads and creative materials',
        'paragraphs' => [
            'Inspiration files, logos, brand assets, and artwork you upload or send are used only to understand your brief, prepare designs or quotations, and produce your order.',
            'Please ensure you have the right to share any logos, images, names, or brand materials you provide. You are responsible for obtaining any permissions needed for us to use those materials in your project.',
            'We may retain production files and proofs as part of your project record so we can fulfil reorders, resolve quality queries, and keep an accurate workshop history.',
        ],
    ],
    [
        'heading' => '8. Storage, retention, and security',
        'paragraphs' => [
            'Enquiry and order information may be stored in our email systems, workshop records, and secure server storage used to operate this website (for example quotation and contact submissions kept so we can follow up even if email delivery is delayed).',
            'We retain personal information only for as long as needed for the purposes described in this policy, including responding to enquiries, completing orders, supporting reorders, and meeting legal, accounting, or dispute-related requirements. Newsletter addresses are kept until you unsubscribe or ask us to remove them.',
            'We take reasonable technical and organisational measures to protect personal information against unauthorised access, loss, misuse, or alteration. No method of transmission or storage is completely secure, so we cannot guarantee absolute security.',
        ],
    ],
    [
        'heading' => '9. International processing',
        'paragraphs' => [
            'KORA is based in Kenya. Some service providers (for example hosting or email infrastructure) may process data on servers located in other countries. Where that happens, we take reasonable steps to work with reputable providers and protect information appropriately.',
        ],
    ],
    [
        'heading' => '10. Your choices and rights',
        'paragraphs' => [
            'Depending on applicable law, you may request access to the personal information we hold about you, ask us to correct inaccurate details, request deletion where we no longer need the information, object to or restrict certain processing, or withdraw consent for newsletter marketing.',
            'To unsubscribe from the newsletter, use the unsubscribe method provided in our emails or contact us directly.',
            'To exercise a privacy request, email or call us using the contact details below. We may need to verify your identity before completing certain requests.',
            'If you believe your privacy rights have been infringed, you may also raise the matter with the relevant data protection authority in Kenya or in your place of residence where applicable.',
        ],
    ],
    [
        'heading' => '11. Children',
        'paragraphs' => [
            'Our website and services are directed to organisations and adults arranging awards, events, and branded items. We do not knowingly collect personal information from children for marketing purposes. If you believe a child has provided personal information to us inappropriately, please contact us and we will take reasonable steps to delete it.',
        ],
    ],
    [
        'heading' => '12. Third-party links',
        'paragraphs' => [
            'Our website may include links to social media profiles, messaging apps, or other external sites. We are not responsible for the privacy practices or content of those third parties. We encourage you to review their policies before sharing personal information with them.',
        ],
    ],
    [
        'heading' => '13. Changes to this policy',
        'paragraphs' => [
            'We may update this Privacy Policy from time to time to reflect changes in our practices, services, or legal requirements. The “Last updated” date at the top of this page will be revised when changes are published. Continued use of our website or services after an update means you should review the revised policy.',
        ],
    ],
    [
        'heading' => '14. How to contact us',
        'paragraphs' => [
            'If you have questions about this Privacy Policy or how KORA handles personal information, contact us at ' . SITE_EMAIL . ' or call ' . SITE_PHONE . '. You can also message us on WhatsApp via the contact options on our website. Our workshop is based in ' . SITE_ADDRESS . '.',
        ],
    ],
];

$privacy_sections = cms_list('privacy', 'sections', $privacy_sections_default);
if ($privacy_sections === []) {
    // Backward compatibility with older flat paragraph lists
    $legacy = cms_list('privacy', 'paragraphs', []);
    if ($legacy !== []) {
        $privacy_sections = [[
            'heading' => '',
            'paragraphs' => $legacy,
        ]];
    } else {
        $privacy_sections = $privacy_sections_default;
    }
}

if ($privacy_intro === '' && isset($privacy_sections_default[0])) {
    $privacy_intro = (string) ($privacy['intro'] ?? '');
}

$current_page = 'privacy';
$page_title = page_title($privacy_title !== '' ? $privacy_title : 'Privacy Policy');
$page_description = 'Privacy policy for KORA — how we collect, use, and protect personal information for quotation requests, contact messages, newsletter signup, and orders in Nanyuki, Laikipia.';

/**
 * Autolink email / phone / WhatsApp mentions in the final contact paragraph.
 */
function kora_privacy_linkify(string $paragraph): string
{
    $safe = htmlspecialchars($paragraph, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

    $safe = str_replace(
        htmlspecialchars(SITE_EMAIL, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
        '<a href="mailto:' . htmlspecialchars(SITE_EMAIL, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">' . htmlspecialchars(SITE_EMAIL, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</a>',
        $safe
    );

    $safe = str_replace(
        htmlspecialchars(SITE_PHONE, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
        '<a href="tel:' . htmlspecialchars(SITE_PHONE_LINK, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">' . htmlspecialchars(SITE_PHONE, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</a>',
        $safe
    );

    return $safe;
}

require __DIR__ . '/includes/header.php';
?>
<section class="page-simple section">
    <div class="container">
        <h1><?= htmlspecialchars($privacy_title !== '' ? $privacy_title : 'Privacy Policy') ?></h1>

        <?php if ($privacy_updated !== ''): ?>
            <p class="page-simple__meta">Last updated: <?= htmlspecialchars($privacy_updated) ?></p>
        <?php endif; ?>

        <?php if (trim($privacy_intro) !== ''): ?>
            <p class="text-body page-simple__intro"><?= htmlspecialchars($privacy_intro) ?></p>
        <?php endif; ?>

        <?php foreach ($privacy_sections as $section): ?>
            <?php
            if (!is_array($section)) {
                continue;
            }
            $heading = trim((string) ($section['heading'] ?? ''));
            $paragraphs = $section['paragraphs'] ?? [];
            if (!is_array($paragraphs)) {
                $body = trim((string) ($section['body'] ?? ''));
                $paragraphs = $body !== '' ? [$body] : [];
            }
            ?>
            <?php if ($heading !== ''): ?>
                <h2 class="page-simple__heading"><?= htmlspecialchars($heading) ?></h2>
            <?php endif; ?>
            <?php foreach ($paragraphs as $paragraph): ?>
                <?php
                if (!is_string($paragraph) || trim($paragraph) === '') {
                    continue;
                }
                $isContactLine = str_contains($paragraph, SITE_EMAIL) || str_contains($paragraph, SITE_PHONE);
                ?>
                <p class="text-body"><?= $isContactLine ? kora_privacy_linkify($paragraph) : htmlspecialchars($paragraph) ?></p>
            <?php endforeach; ?>
        <?php endforeach; ?>

        <p class="page-simple__actions"><a class="btn btn--ghost" href="/">Back to home</a></p>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
