    </main>
    <?php
    $newsletter_status = $_GET['newsletter'] ?? '';
    $newsletter_ok = $newsletter_status === '1';
    $newsletter_err = $newsletter_status === 'error';

    $footer = cms_section('footer');
    $footer_newsletter = is_array($footer['newsletter'] ?? null) ? $footer['newsletter'] : [];
    $footer_newsletter_image = is_array($footer_newsletter['image'] ?? null) ? $footer_newsletter['image'] : [];
    $footer_newsletter_file = (string) ($footer_newsletter_image['file'] ?? 'awards/Golf_Award.webp');
    $footer_newsletter_alt = (string) ($footer_newsletter_image['alt'] ?? 'Custom KORA golf Most Valuable Player award');
    $footer_newsletter_title = (string) ($footer_newsletter['title'] ?? 'Subscribe to our newsletter to get updates on our latest collections');
    $footer_newsletter_text = (string) ($footer_newsletter['text'] ?? 'Be first to see new awards, medals, souvenirs plus seasonal offers from the KORA workshop.');
    $footer_privacy_href = (string) ($footer_newsletter['privacy_href'] ?? '/privacy.php');
    $footer_blurb = (string) ($footer['blurb'] ?? 'Custom awards, medals and souvenirs designed and made for organisations, schools, corporates, and sports teams.');
    $footer_explore = cms_list('footer', 'explore_nav', [
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Our work', 'href' => '/work-samples.php'],
        ['label' => 'Products', 'href' => '/products.php'],
        ['label' => 'How it works', 'href' => '/how-it-works.php'],
    ]);
    $footer_products = cms_list('footer', 'products_nav', [
        ['label' => 'Medals', 'href' => '/products.php#medals'],
        ['label' => 'Awards & Trophies', 'href' => '/products.php#awards'],
        ['label' => 'Souvenirs', 'href' => '/products.php#souvenirs'],
    ]);
    $footer_credit = is_array($footer['credit'] ?? null) ? $footer['credit'] : ['label' => 'Designed by Fraittech', 'href' => 'https://fraittech.co.ke'];
    ?>
    <footer class="site-footer">
        <div class="container site-footer__inner">
            <aside class="site-footer__newsletter" aria-labelledby="footer-newsletter-title">
                <div class="site-footer__newsletter-media">
                    <img
                        src="<?= img($footer_newsletter_file) ?>"
                        alt="<?= htmlspecialchars($footer_newsletter_alt) ?>"
                        width="<?= (int) ($footer_newsletter_image['width'] ?? 3376) ?>"
                        height="<?= (int) ($footer_newsletter_image['height'] ?? 4476) ?>"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
                <div class="site-footer__newsletter-content">
                    <h2 id="footer-newsletter-title" class="site-footer__newsletter-title"><?= htmlspecialchars($footer_newsletter_title) ?></h2>
                    <p class="site-footer__newsletter-text"><?= htmlspecialchars($footer_newsletter_text) ?></p>

                    <form class="site-footer__newsletter-form" action="/process-newsletter.php" method="post" novalidate>
                        <label class="visually-hidden" for="newsletter-email">Email address</label>
                        <div class="site-footer__newsletter-field">
                            <span class="site-footer__newsletter-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                            </span>
                            <input
                                type="email"
                                id="newsletter-email"
                                name="email"
                                required
                                autocomplete="email"
                                placeholder="Enter your email"
                            >
                            <button type="submit" class="site-footer__newsletter-btn">Subscribe</button>
                        </div>
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="visually-hidden" aria-hidden="true">
                    </form>

                    <?php if ($newsletter_ok): ?>
                        <p class="site-footer__newsletter-success" id="newsletter-feedback" role="status" data-auto-dismiss="6000">Thanks for subscribing — a confirmation email is on its way.</p>
                    <?php elseif ($newsletter_err): ?>
                        <p class="site-footer__newsletter-error" id="newsletter-feedback" role="alert" data-auto-dismiss="8000">Please enter a valid email address and try again.</p>
                    <?php endif; ?>

                    <p class="site-footer__newsletter-note">You will be able to unsubscribe at any time. Read our <a href="<?= htmlspecialchars($footer_privacy_href) ?>">privacy policy here</a>.</p>
                </div>
            </aside>

            <div class="site-footer__panel">
                <div class="site-footer__grid">
                    <div class="site-footer__col site-footer__brand">
                        <?php render_brand_logo('footer'); ?>
                        <p class="site-footer__blurb"><?= htmlspecialchars($footer_blurb) ?></p>
                    </div>

                    <div class="site-footer__accordions">
                        <details class="site-footer__accordion">
                            <summary class="site-footer__accordion-trigger">
                                <span>Explore</span>
                                <svg class="site-footer__accordion-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </summary>
                            <div class="site-footer__accordion-panel">
                                <nav aria-label="Explore">
                                    <ul class="site-footer__nav-list">
                                        <?php foreach ($footer_explore as $link): ?>
                                            <?php if (!is_array($link) || (string) ($link['label'] ?? '') === '') {
                                                continue;
                                            } ?>
                                            <li><a class="site-footer__nav-link" href="<?= htmlspecialchars((string) ($link['href'] ?? '#')) ?>"><?= htmlspecialchars((string) $link['label']) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                        </details>

                        <details class="site-footer__accordion">
                            <summary class="site-footer__accordion-trigger">
                                <span>Products</span>
                                <svg class="site-footer__accordion-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </summary>
                            <div class="site-footer__accordion-panel">
                                <nav aria-label="Products">
                                    <ul class="site-footer__nav-list">
                                        <?php foreach ($footer_products as $link): ?>
                                            <?php if (!is_array($link) || (string) ($link['label'] ?? '') === '') {
                                                continue;
                                            } ?>
                                            <li><a class="site-footer__nav-link" href="<?= htmlspecialchars((string) ($link['href'] ?? '#')) ?>"><?= htmlspecialchars((string) $link['label']) ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </nav>
                            </div>
                        </details>

                        <details class="site-footer__accordion">
                            <summary class="site-footer__accordion-trigger">
                                <span>Contact Us</span>
                                <svg class="site-footer__accordion-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path d="M6 9l6 6 6-6"/>
                                </svg>
                            </summary>
                            <div class="site-footer__accordion-panel">
                                <ul class="site-footer__contact-list">
                                    <li class="site-footer__contact-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                        <span><?= SITE_ADDRESS ?></span>
                                    </li>
                                    <li class="site-footer__contact-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M6.5 4h3l1.5 5-2 1.5a13 13 0 0 0 5 5l1.5-2 5 1.5v3A2.5 2.5 0 0 1 17 19C10 19 5 14 5 7a2.5 2.5 0 0 1 2.5-3Z"/></svg>
                                        <a href="tel:<?= SITE_PHONE_LINK ?>"><?= SITE_PHONE ?></a>
                                    </li>
                                    <li class="site-footer__contact-item">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                                        <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a>
                                    </li>
                                </ul>
                            </div>
                        </details>
                    </div>
                </div>

                <ul class="site-footer__social" aria-label="Social media">
                    <li>
                        <a class="site-footer__social-link" href="<?= htmlspecialchars(SITE_INSTAGRAM) ?>" target="_blank" rel="noopener noreferrer" aria-label="KORA on Instagram">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                        </a>
                    </li>
                    <li>
                        <a class="site-footer__social-link" href="<?= htmlspecialchars(SITE_TIKTOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="KORA on TikTok">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.5 3c.4 2.3 1.8 3.8 4 4.2v2.4c-1.4.1-2.7-.3-4-1.1v5.7c0 3.5-2.6 6.3-6.2 6.3S4 17.7 4 14.2s2.7-6.3 6.3-6.3c.3 0 .7 0 1 .1v2.6c-.3-.1-.6-.2-1-.2-2 0-3.6 1.7-3.6 3.8S8.3 18 10.3 18s3.6-1.7 3.6-3.8V3h2.6z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a class="site-footer__social-link" href="<?= htmlspecialchars(SITE_FACEBOOK) ?>" target="_blank" rel="noopener noreferrer" aria-label="KORA on Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v7h3v-7h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>
                        </a>
                    </li>
                    <li>
                        <a class="site-footer__social-link" href="<?= htmlspecialchars(SITE_YOUTUBE) ?>" target="_blank" rel="noopener noreferrer" aria-label="KORA on YouTube">
                            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M21.6 7.2a2.7 2.7 0 0 0-1.9-1.9C18 5 12 5 12 5s-6 0-7.7.3A2.7 2.7 0 0 0 2.4 7.2 28.6 28.6 0 0 0 2 12a28.6 28.6 0 0 0 .4 4.8 2.7 2.7 0 0 0 1.9 1.9C6 19 12 19 12 19s6 0 7.7-.3a2.7 2.7 0 0 0 1.9-1.9A28.6 28.6 0 0 0 22 12a28.6 28.6 0 0 0-.4-4.8zM10 15.2V8.8L15.5 12 10 15.2z"/></svg>
                        </a>
                    </li>
                </ul>

                <div class="site-footer__bar">
                    <div class="site-footer__bar-start">
                        <span class="site-footer__bar-copy">&copy; <?= SITE_YEAR ?> <?= SITE_NAME ?>. All rights reserved.</span>
                        <span class="site-footer__bar-credit"><a class="site-footer__bar-link" href="<?= htmlspecialchars((string) ($footer_credit['href'] ?? 'https://fraittech.co.ke')) ?>" target="_blank" rel="noopener noreferrer"><?= htmlspecialchars((string) ($footer_credit['label'] ?? 'Designed by Fraittech')) ?></a>.</span>
                    </div>
                    <p class="site-footer__bar-tagline"><?= htmlspecialchars(SITE_FOOTER_TAGLINE) ?></p>
                </div>
            </div>
        </div>
    </footer>

    <a
        class="whatsapp-float"
        href="<?= htmlspecialchars(SITE_WHATSAPP) ?>"
        target="_blank"
        rel="noopener noreferrer"
        aria-label="Chat with KORA on WhatsApp"
    >
        <svg class="whatsapp-float__icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path fill="currentColor" d="M12.04 2C6.58 2 2.15 6.4 2.15 11.83c0 2.08.55 4.1 1.6 5.89L2 22l4.43-1.16a10.05 10.05 0 0 0 5.61 1.66h.01c5.46 0 9.89-4.4 9.89-9.83C21.94 6.4 17.5 2 12.04 2zm5.76 13.99c-.24.68-1.4 1.25-1.94 1.33-.5.07-1.13.1-1.83-.11-.42-.13-.96-.31-1.65-.61-2.9-1.25-4.79-4.17-4.93-4.36-.14-.19-1.17-1.55-1.17-2.96 0-1.4.73-2.09 1-2.37.26-.28.57-.35.76-.35h.55c.17 0 .41-.07.64.49.24.58.81 2 .88 2.14.07.14.12.3.02.49-.1.19-.14.3-.28.47-.14.16-.29.36-.42.49-.14.14-.28.29-.12.56.16.28.71 1.17 1.52 1.9 1.05.93 1.93 1.22 2.2 1.36.28.14.44.12.6-.07.17-.19.7-.81.89-1.09.19-.28.38-.23.64-.14.26.1 1.66.78 1.95.93.28.14.47.21.54.33.07.12.07.68-.17 1.36z"/>
        </svg>
        <span class="whatsapp-float__label">WhatsApp</span>
    </a>

    <script src="<?= asset('assets/js/main.js') ?>" defer></script>
    <script src="<?= asset('assets/js/form-validation.js') ?>" defer></script>
    <script src="<?= asset('assets/js/animations.js') ?>" defer></script>
</body>
</html>
