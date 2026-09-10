<?php

declare(strict_types=1);

$contact_submitted = ($_GET['submitted'] ?? '') === '1';
$page = cms_section('contact_us');

$hero = is_array($page['hero'] ?? null) ? $page['hero'] : [];
$heroImage = is_array($hero['image'] ?? null) ? $hero['image'] : [];
$heroFile = (string) ($heroImage['file'] ?? 'workshop_hero.jpeg');
$heroAlt = (string) ($heroImage['alt'] ?? 'KORA workshop in Nanyuki');
$heroTitle = (string) ($hero['title'] ?? 'Contact Us');
$heroLead = (string) ($hero['lead'] ?? '');

$introTitle = (string) ($page['intro_title'] ?? 'Reach the workshop');
$introText = (string) ($page['intro_text'] ?? 'Whether you have a quick question or want to talk through an idea before requesting a quotation, send us a message or use the channels below. We reply within one business day.');

$channelsDefault = [
    [
        'label' => 'WhatsApp',
        'value' => 'Chat with KORA',
        'href' => SITE_WHATSAPP,
        'external' => true,
        'icon' => 'whatsapp',
    ],
    [
        'label' => 'Phone',
        'value' => SITE_PHONE,
        'href' => 'tel:' . SITE_PHONE_LINK,
        'external' => false,
        'icon' => 'phone',
    ],
    [
        'label' => 'Email',
        'value' => SITE_EMAIL,
        'href' => 'mailto:' . SITE_EMAIL,
        'external' => false,
        'icon' => 'email',
    ],
    [
        'label' => 'Visit',
        'value' => 'Kio Plaza, Nanyuki',
        'href' => '',
        'external' => false,
        'icon' => 'location',
    ],
    [
        'label' => 'Hours',
        'value' => SITE_HOURS,
        'href' => '',
        'external' => false,
        'icon' => 'hours',
    ],
];
$channels = cms_list('contact_us', 'channels', $channelsDefault);
if ($channels === []) {
    $channels = $channelsDefault;
}

$form = is_array($page['form'] ?? null) ? $page['form'] : [];
$formTitle = (string) ($form['title'] ?? 'Send a message');
$formNote = (string) ($form['note'] ?? 'Fields marked * are required. We will get back to you by email or phone.');
$formAction = (string) ($form['action'] ?? '/process-contact.php');
$formSubmit = (string) ($form['submit_label'] ?? 'Send message');

$aside = is_array($page['aside'] ?? null) ? $page['aside'] : [];
$asideTitle = (string) ($aside['title'] ?? 'Need a formal quotation?');
$asideText = (string) ($aside['text'] ?? 'Share your event details, quantity, and materials for a priced quote within 24 hours.');
$asideCtaLabel = (string) ($aside['cta_label'] ?? 'Request a Quotation');
$asideCtaHref = (string) ($aside['cta_href'] ?? '/request-quote');

$channelIcons = [
    'whatsapp' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.5 11.5a8.5 8.5 0 0 1-12.7 7.4L3.5 20l1.2-4.1A8.5 8.5 0 1 1 20.5 11.5z"/><path d="M9.2 9.8c.3-.5.6-.5.9-.5h.3c.2 0 .4 0 .5.4l.7 1.7c.1.3 0 .5-.2.7l-.4.4c-.2.2-.2.4 0 .7.4.6 1 1.2 1.6 1.6.3.2.5.2.7 0l.4-.4c.2-.2.4-.3.7-.2l1.7.7c.3.1.4.3.4.5v.3c0 .3 0 .6-.5.9-.5.3-1.1.5-1.8.4-1.8-.2-3.5-1.1-4.8-2.4-1.3-1.3-2.2-3-2.4-4.8-.1-.7.1-1.3.4-1.8z"/></svg>',
    'phone' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.1-8.7A2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.6a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.5-1.2a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.7a2 2 0 0 1 1.7 1.9z"/></svg>',
    'email' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 7 9-7"/></svg>',
    'location' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>',
    'hours' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
];
?>
<section class="contact-hero quote-hero" aria-labelledby="contact-hero-title">
    <img
        class="quote-hero__image"
        src="<?= img($heroFile) ?>"
        alt="<?= htmlspecialchars($heroAlt) ?>"
        width="<?= (int) ($heroImage['width'] ?? 1200) ?>"
        height="<?= (int) ($heroImage['height'] ?? 800) ?>"
        fetchpriority="high"
        draggable="false"
    >
    <div class="quote-hero__overlay">
        <div class="quote-hero__content contact-hero__content">
            <h1 class="quote-hero__title" id="contact-hero-title"><?= htmlspecialchars($heroTitle) ?></h1>
            <?php if ($heroLead !== ''): ?>
                <p class="quote-hero__subtitle"><?= htmlspecialchars($heroLead) ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="contact-page section" id="contact-form-section" aria-labelledby="contact-intro-title">
    <div class="container contact-page__grid">
        <div class="contact-page__info reveal">
            <h2 id="contact-intro-title"><?= htmlspecialchars($introTitle) ?></h2>
            <p class="contact-page__lead"><?= htmlspecialchars($introText) ?></p>

            <ul class="contact-channels" role="list">
                <?php foreach ($channels as $channel): ?>
                    <?php
                    if (!is_array($channel)) {
                        continue;
                    }
                    $label = (string) ($channel['label'] ?? '');
                    $value = (string) ($channel['value'] ?? '');
                    $href = (string) ($channel['href'] ?? '');
                    $iconKey = (string) ($channel['icon'] ?? 'email');
                    if ($label === '' || $value === '') {
                        continue;
                    }
                    $external = !empty($channel['external']) || str_starts_with($href, 'http');
                    $iconSvg = $channelIcons[$iconKey] ?? $channelIcons['email'];
                    ?>
                    <li class="contact-channel">
                        <span class="contact-channel__icon" aria-hidden="true"><?= $iconSvg ?></span>
                        <div class="contact-channel__body">
                            <p class="contact-channel__label"><?= htmlspecialchars($label) ?></p>
                            <?php if ($href !== ''): ?>
                                <a
                                    class="contact-channel__value"
                                    href="<?= htmlspecialchars($href) ?>"
                                    <?= $external ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
                                ><?= htmlspecialchars($value) ?></a>
                            <?php else: ?>
                                <p class="contact-channel__value contact-channel__value--plain"><?= htmlspecialchars($value) ?></p>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>

            <aside class="contact-page__aside">
                <h3 class="contact-page__aside-title"><?= htmlspecialchars($asideTitle) ?></h3>
                <p class="contact-page__aside-text"><?= htmlspecialchars($asideText) ?></p>
                <a class="btn btn--solid" href="<?= htmlspecialchars($asideCtaHref) ?>"><?= htmlspecialchars($asideCtaLabel) ?></a>
            </aside>
        </div>

        <div class="contact-page__form reveal">
            <form
                class="quote-form contact-form"
                id="contact-form"
                action="<?= htmlspecialchars($formAction) ?>"
                method="post"
                novalidate
                data-whatsapp="<?= htmlspecialchars(SITE_WHATSAPP) ?>"
            >
                <div class="quote-form__header">
                    <h2><?= htmlspecialchars($formTitle) ?></h2>
                    <p class="quote-form__note"><?= htmlspecialchars($formNote) ?></p>
                </div>

                <div class="form-field form-field--hp" aria-hidden="true">
                    <label for="contact_website">Website</label>
                    <input type="text" id="contact_website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="contact_name">Your name <span class="req" aria-hidden="true">*</span></label>
                        <input type="text" id="contact_name" name="name" required autocomplete="name" placeholder="Jane Doe" data-validate="required">
                        <p class="form-field__error" id="contact_name-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="contact_organization">Organization</label>
                        <input type="text" id="contact_organization" name="organization" autocomplete="organization" placeholder="Company or school" data-validate="optional">
                        <p class="form-field__error" id="contact_organization-error" role="alert"></p>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-field">
                        <label for="contact_email">Email <span class="req" aria-hidden="true">*</span></label>
                        <input type="email" id="contact_email" name="email" required autocomplete="email" placeholder="you@example.com" data-validate="email">
                        <p class="form-field__error" id="contact_email-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="contact_phone">Phone or WhatsApp <span class="req" aria-hidden="true">*</span></label>
                        <input type="tel" id="contact_phone" name="phone" required autocomplete="tel" placeholder="+254 790 355 707" data-validate="phone">
                        <p class="form-field__error" id="contact_phone-error" role="alert"></p>
                    </div>
                </div>

                <div class="form-field">
                    <label for="contact_subject">Subject <span class="req" aria-hidden="true">*</span></label>
                    <input type="text" id="contact_subject" name="subject" required placeholder="Question about medals, delivery, materials…" data-validate="required">
                    <p class="form-field__error" id="contact_subject-error" role="alert"></p>
                </div>

                <div class="form-field">
                    <label for="contact_message">Message <span class="req" aria-hidden="true">*</span></label>
                    <textarea id="contact_message" name="message" rows="6" required placeholder="How can we help?" data-validate="required"></textarea>
                    <p class="form-field__error" id="contact_message-error" role="alert"></p>
                </div>

                <button type="submit" class="btn btn--solid"><?= htmlspecialchars($formSubmit) ?></button>
                <p class="form-field__error" id="contact-form-status" role="status"></p>
            </form>

            <div
                class="quote-success quote-success--below<?= $contact_submitted ? ' is-visible' : '' ?>"
                id="contact-success"
                role="status"
                <?= $contact_submitted ? '' : 'hidden' ?>
                aria-live="polite"
            >
                <span class="quote-success__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
                </span>
                <div>
                    <h2 class="quote-success__title">Message sent — thank you!</h2>
                    <p class="quote-success__text">We have received your message and will reply within one business day. Need a quicker answer? <a href="<?= htmlspecialchars(SITE_WHATSAPP) ?>" target="_blank" rel="noopener noreferrer" data-contact-whatsapp>Message us on WhatsApp</a>.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$map = is_array($page['map'] ?? null) ? $page['map'] : [];
$mapEyebrow = (string) ($map['eyebrow'] ?? 'Workshop');
$mapTitle = (string) ($map['title'] ?? 'Kio Plaza, Nanyuki');
$mapText = (string) ($map['text'] ?? 'Visit Kora Laser Craft at Kio Plaza to collect finished pieces from the studio, or arrange delivery when you confirm your order.');
$mapEmbed = (string) ($map['embed_url'] ?? 'https://www.google.com/maps?q=Kora+Laser+Craft,+Kio+Plaza,+Nanyuki&z=16&hl=en&output=embed');
?>
<section class="contact-map" aria-labelledby="contact-map-title">
    <div class="contact-map__canvas">
        <iframe
            class="contact-map__iframe"
            title="Map showing Kora Laser Craft at Kio Plaza, Nanyuki"
            src="<?= htmlspecialchars($mapEmbed) ?>"
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            allowfullscreen
        ></iframe>
        <div class="contact-map__grain" aria-hidden="true"></div>
    </div>

    <div class="container contact-map__rail">
        <div class="contact-map__panel reveal">
            <p class="contact-map__eyebrow"><?= htmlspecialchars($mapEyebrow) ?></p>
            <h2 class="contact-map__title" id="contact-map-title"><?= htmlspecialchars($mapTitle) ?></h2>
            <p class="contact-map__text"><?= htmlspecialchars($mapText) ?></p>
        </div>
    </div>
</section>
