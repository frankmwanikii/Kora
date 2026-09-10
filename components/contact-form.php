<section class="contact-section section" id="contact" aria-labelledby="contact-title">
    <div class="container contact-section__grid">
        <div class="contact-section__intro reveal">
            <h2 id="contact-title">Request a Custom Quotation</h2>
            <p class="lead-italic">Tell us what you have in mind. We’ll review your brief and recommend suitable options based on your event, quantity, materials, and budget.</p>
        </div>
        <div class="contact-section__form reveal">
            <form class="quote-form" id="quote-form" action="/process-quote" method="post" enctype="multipart/form-data" novalidate>
                <div class="form-row">
                    <div class="form-field">
                        <label for="name">Your name</label>
                        <input type="text" id="name" name="name" required autocomplete="name" placeholder="Jane Doe" data-validate="required">
                        <p class="form-field__error" id="name-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="organization">Organization or company</label>
                        <input type="text" id="organization" name="organization" autocomplete="organization" placeholder="Company or school name" data-validate="optional">
                        <p class="form-field__error" id="organization-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="phone">Phone or WhatsApp</label>
                        <input type="tel" id="phone" name="phone" required autocomplete="tel" placeholder="+254 790 355 707" data-validate="phone">
                        <p class="form-field__error" id="phone-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required autocomplete="email" placeholder="you@example.com" data-validate="email">
                        <p class="form-field__error" id="email-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="event_type">Event type</label>
                        <input type="text" id="event_type" name="event_type" required placeholder="Corporate awards, sports day…" data-validate="required">
                        <p class="form-field__error" id="event_type-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="product">Product required</label>
                        <input type="text" id="product" name="product" required placeholder="Trophies, medals, plaques…" data-validate="required">
                        <p class="form-field__error" id="product-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="quantity">Estimated quantity</label>
                        <input type="number" id="quantity" name="quantity" required min="1" step="1" inputmode="numeric" placeholder="50" data-validate="quantity">
                        <p class="form-field__error" id="quantity-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="event_date">Event date</label>
                        <input type="date" id="event_date" name="event_date" required min="<?= date('Y-m-d') ?>" data-validate="date">
                        <p class="form-field__error" id="event_date-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-field">
                    <label for="message">Message or design brief</label>
                    <textarea id="message" name="message" required placeholder="Share your idea, logo details, wording, materials, size, and delivery location." data-validate="required"></textarea>
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
        <div class="contact-section__channels btn-group btn-group--row reveal">
            <a class="btn btn--light" href="https://wa.me/254790355707" target="_blank" rel="noopener noreferrer">Whatsapp KORA</a>
            <a class="btn btn--light" href="mailto:<?= SITE_EMAIL ?>">Email KORA</a>
        </div>
    </div>
</section>
