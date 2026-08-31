    </main>
    <footer class="site-footer">
        <div class="container">
            <div class="site-footer__grid">
                <div class="site-footer__col site-footer__brand">
                    <?php render_brand_logo('footer'); ?>
                </div>

                <div class="site-footer__accordions">
                    <details class="site-footer__accordion">
                        <summary class="site-footer__accordion-trigger">
                            <span>Quick Links</span>
                            <svg class="site-footer__accordion-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M6 9l6 6 6-6"/>
                            </svg>
                        </summary>
                        <div class="site-footer__accordion-panel">
                            <nav aria-label="Footer">
                                <ul class="site-footer__nav-list">
                                    <li><a class="site-footer__nav-link" href="/work-samples.php">Our work</a></li>
                                    <li><a class="site-footer__nav-link" href="/products.php">Products</a></li>
                                    <li><a class="site-footer__nav-link" href="/how-it-works.php">How it works</a></li>
                                    <li><a class="site-footer__nav-link" href="/#contact">Contact</a></li>
                                    <li><a class="site-footer__nav-link" href="/privacy.php">Privacy</a></li>
                                </ul>
                            </nav>
                        </div>
                    </details>

                    <details class="site-footer__accordion">
                        <summary class="site-footer__accordion-trigger">
                            <span>Contact</span>
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
            <div class="site-footer__bar">
                <div class="site-footer__bar-start">
                    <span class="site-footer__bar-copy">&copy; <?= SITE_YEAR ?> <?= SITE_NAME ?>. CRAFTED IN LAIKIPIA.</span>
                    <span class="site-footer__bar-credit">DESIGNED BY <a class="site-footer__bar-link" href="https://fraittech.co.ke" target="_blank" rel="noopener noreferrer">Fraittech</a>.</span>
                </div>
                <span class="site-footer__bar-tagline">RECOGNITION, MADE PERSONAL.</span>
            </div>
        </div>
    </footer>
    <script src="<?= asset('assets/js/main.js') ?>" defer></script>
    <script src="<?= asset('assets/js/form-validation.js') ?>" defer></script>
    <script src="<?= asset('assets/js/animations.js') ?>" defer></script>
</body>
</html>
