<?php

declare(strict_types=1);

$quote_submitted = ($_GET['submitted'] ?? '') === '1';
$quote_min_date = date('Y-m-d');
?>
<section class="quote-hero" aria-labelledby="quote-hero-title">
    <img
        class="quote-hero__image"
        src="<?= img('acrylic_material.jpeg') ?>"
        alt="Acrylic material used for custom KORA awards"
        width="2752"
        height="1536"
        fetchpriority="high"
        draggable="false"
    >
    <div class="quote-hero__overlay">
        <div class="quote-hero__content">
            <h1 class="quote-hero__title" id="quote-hero-title">Request a Quotation</h1>
        </div>
    </div>
</section>

<section class="quote-trust" aria-label="Why order from KORA">
    <div class="container quote-trust__grid">
        <div class="quote-trust__item reveal">
            <span class="quote-trust__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </span>
            <div>
                <h3 class="quote-trust__label">24-hour response</h3>
                <p class="quote-trust__text">Quotations prepared within one business day.</p>
            </div>
        </div>
        <div class="quote-trust__item reveal">
            <span class="quote-trust__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M5 21V8l7-5 7 5v13"/><path d="M9 21v-6h6v6"/></svg>
            </span>
            <div>
                <h3 class="quote-trust__label">Made in-house</h3>
                <p class="quote-trust__text">Designed and produced in our Nanyuki workshop.</p>
            </div>
        </div>
        <div class="quote-trust__item reveal">
            <span class="quote-trust__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
            </span>
            <div>
                <h3 class="quote-trust__label">Free design guidance</h3>
                <p class="quote-trust__text">We refine your layout and artwork before production.</p>
            </div>
        </div>
        <div class="quote-trust__item reveal">
            <span class="quote-trust__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="6" width="14" height="11" rx="1"/><path d="M15 10h4l3 3v4h-7z"/><circle cx="6" cy="19" r="1.6"/><circle cx="18" cy="19" r="1.6"/></svg>
            </span>
            <div>
                <h3 class="quote-trust__label">Delivery or collection</h3>
                <p class="quote-trust__text">Collect in Nanyuki or arrange delivery countrywide.</p>
            </div>
        </div>
    </div>
</section>

<section class="quote-section section" id="quote-form-section" aria-labelledby="quote-form-title">
    <div class="container">
        <?php if ($quote_submitted): ?>
        <div class="quote-success reveal" role="status">
            <span class="quote-success__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>
            </span>
            <div>
                <h2 class="quote-success__title">Request received — thank you!</h2>
                <p class="quote-success__text">Your quotation request has been sent, and a confirmation email is on its way. We will review your brief and respond within one business day. Need it faster? <a href="https://wa.me/254790355707" target="_blank" rel="noopener noreferrer">Message us on WhatsApp</a>.</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="quote-layout">
            <div class="quote-form-col reveal">
                <form class="quote-form" id="quote-form" action="/process-quote.php" method="post" enctype="multipart/form-data" novalidate>
                    <header class="quote-form__header">
                        <h2 id="quote-form-title">Tell us about your order</h2>
                        <p class="quote-form__note">Fields marked <span class="req" aria-hidden="true">*</span> are required. The more detail you share, the more accurate your quotation will be.</p>
                    </header>
                    <input type="hidden" name="form_origin" value="request-quote">
                    <div class="form-field form-field--hp" aria-hidden="true">
                        <label for="website">Leave this field empty</label>
                        <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="name">Your name <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="name" name="name" required maxlength="120" autocomplete="name" placeholder="Jane Doe" data-validate="required">
                            <p class="form-field__error" id="name-error" role="alert"></p>
                        </div>
                        <div class="form-field">
                            <label for="organization">Organization or company</label>
                            <input type="text" id="organization" name="organization" maxlength="160" autocomplete="organization" placeholder="Company, school, or club name" data-validate="optional">
                            <p class="form-field__error" id="organization-error" role="alert"></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="phone">Phone or WhatsApp <span class="req" aria-hidden="true">*</span></label>
                            <input type="tel" id="phone" name="phone" required autocomplete="tel" placeholder="+254 790 355 707" data-validate="phone">
                            <p class="form-field__error" id="phone-error" role="alert"></p>
                        </div>
                        <div class="form-field">
                            <label for="email">Email <span class="req" aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" required maxlength="160" autocomplete="email" placeholder="you@example.com" data-validate="email">
                            <p class="form-field__error" id="email-error" role="alert"></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="event_type">Event type <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="event_type" name="event_type" required maxlength="160" placeholder="Corporate awards, sports day, graduation…" data-validate="required">
                            <p class="form-field__error" id="event_type-error" role="alert"></p>
                        </div>
                        <div class="form-field">
                            <label for="product">Product required <span class="req" aria-hidden="true">*</span></label>
                            <input type="text" id="product" name="product" required maxlength="160" placeholder="Trophies, medals, souvenirs…" data-validate="required">
                            <p class="form-field__error" id="product-error" role="alert"></p>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label for="quantity">Estimated quantity <span class="req" aria-hidden="true">*</span></label>
                            <input type="number" id="quantity" name="quantity" required min="1" step="1" inputmode="numeric" placeholder="50" data-validate="quantity">
                            <p class="form-field__error" id="quantity-error" role="alert"></p>
                        </div>
                        <div class="form-field">
                            <label for="event_date">Event date <span class="req" aria-hidden="true">*</span></label>
                            <input type="date" id="event_date" name="event_date" required min="<?= $quote_min_date ?>" data-validate="date">
                            <p class="form-field__error" id="event_date-error" role="alert"></p>
                        </div>
                    </div>
                    <div class="form-field">
                        <label for="message">Message or design brief <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" required maxlength="4000" placeholder="Share your idea, logo details, wording, preferred materials, approximate size, and delivery or collection location." data-validate="required"></textarea>
                        <p class="form-field__error" id="message-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="inspo_files">Inspiration files</label>
                        <input type="file" id="inspo_files" name="inspo_files[]" accept=".pdf,.png,.webp,.jpg,.jpeg,application/pdf,image/png,image/webp,image/jpeg" multiple data-validate="inspo-files">
                        <p class="form-field__hint">Optional. Select multiple files — PDF, PNG, WebP, or JPG, up to 20MB each.</p>
                        <ul class="inspo-file-list" id="inspo-file-list" hidden aria-live="polite" aria-label="Selected inspiration files"></ul>
                        <div class="upload-progress" id="upload-progress" hidden aria-live="polite">
                            <div class="upload-progress__track">
                                <div class="upload-progress__bar" id="upload-progress-bar"></div>
                            </div>
                            <p class="upload-progress__label" id="upload-progress-label">Uploading files…</p>
                        </div>
                        <p class="form-field__error" id="inspo_files-error" role="alert"></p>
                    </div>
                    <button type="submit" class="btn btn--solid">Request a Quotation</button>
                    <p class="form-field__error" id="form-status" role="status"></p>
                </form>
            </div>

            <aside class="quote-aside" aria-label="Ordering help">
                <div class="quote-card reveal">
                    <h3 class="quote-card__title">Prefer to talk first?</h3>
                    <p class="quote-card__text">We are happy to discuss your idea before you fill anything in.</p>
                    <ul class="quote-channels">
                        <li>
                            <a class="quote-channel" href="https://wa.me/254790355707" target="_blank" rel="noopener noreferrer">
                                <span class="quote-channel__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"/></svg>
                                </span>
                                <span>
                                    <strong>WhatsApp</strong>
                                    <small><?= SITE_PHONE ?></small>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a class="quote-channel" href="tel:<?= SITE_PHONE_LINK ?>">
                                <span class="quote-channel__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0122 16.92z"/></svg>
                                </span>
                                <span>
                                    <strong>Call us</strong>
                                    <small><?= SITE_PHONE ?></small>
                                </span>
                            </a>
                        </li>
                        <li>
                            <a class="quote-channel" href="mailto:<?= SITE_EMAIL ?>">
                                <span class="quote-channel__icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/></svg>
                                </span>
                                <span>
                                    <strong>Email</strong>
                                    <small><?= SITE_EMAIL ?></small>
                                </span>
                            </a>
                        </li>
                    </ul>
                    <p class="quote-card__meta"><?= SITE_ADDRESS ?> · Mon–Sat, 8am–6pm</p>
                </div>

                <div class="quote-card reveal">
                    <h3 class="quote-card__title">Have these ready</h3>
                    <ul class="quote-checklist">
                        <li>Event date and delivery deadline</li>
                        <li>Product type and estimated quantity</li>
                        <li>Logo files or artwork, if available</li>
                        <li>Wording, names, or engraving text</li>
                        <li>Preferred material and rough budget</li>
                    </ul>
                    <p class="quote-card__meta">No artwork yet? No problem — our team can design it with you.</p>
                </div>

                <div class="quote-card quote-card--steps reveal">
                    <h3 class="quote-card__title">What happens next</h3>
                    <ol class="quote-steps">
                        <li>
                            <strong>We review your brief</strong>
                            <span>Same day, with follow-up questions if needed.</span>
                        </li>
                        <li>
                            <strong>You receive your quotation</strong>
                            <span>Pricing plus a suggested design direction within 24 hours.</span>
                        </li>
                        <li>
                            <strong>Approve the design</strong>
                            <span>We share a proof; production starts on your approval.</span>
                        </li>
                        <li>
                            <strong>Collect or receive delivery</strong>
                            <span>Finished pieces ready ahead of your event date.</span>
                        </li>
                    </ol>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="quote-faq section" aria-labelledby="quote-faq-title">
    <div class="container quote-faq__inner">
        <h2 id="quote-faq-title" class="quote-faq__heading">Quotation FAQs</h2>
        <div class="quote-faq__list reveal">
            <details class="quote-faq__item">
                <summary class="quote-faq__question">
                    <span>How long does production take?</span>
                    <svg class="quote-faq__icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <p class="quote-faq__answer">Most orders are completed within 3–10 working days depending on quantity and complexity. Share your event date in the form and we will confirm a delivery timeline in your quotation.</p>
            </details>
            <details class="quote-faq__item">
                <summary class="quote-faq__question">
                    <span>Do I need finished artwork or a logo file?</span>
                    <svg class="quote-faq__icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <p class="quote-faq__answer">No. A rough idea, a photo for inspiration, or just your logo is enough to start. Our design team prepares the layout and shares a proof for your approval before anything is cut or engraved.</p>
            </details>
            <details class="quote-faq__item">
                <summary class="quote-faq__question">
                    <span>Is there a minimum order quantity?</span>
                    <svg class="quote-faq__icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <p class="quote-faq__answer">We handle everything from a single commemorative trophy to thousands of marathon medals. Quantity affects unit pricing, so include your best estimate and we will quote accordingly.</p>
            </details>
            <details class="quote-faq__item">
                <summary class="quote-faq__question">
                    <span>How is pricing calculated?</span>
                    <svg class="quote-faq__icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
                </summary>
                <p class="quote-faq__answer">Pricing depends on material (wood, MDF, plywood, or acrylic), size, number of layers, engraving detail, finishing, and quantity. Your quotation breaks all of this down clearly — with no hidden costs.</p>
            </details>
        </div>
    </div>
</section>
