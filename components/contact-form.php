<section class="contact-section section" id="contact" aria-labelledby="contact-title">
    <div class="container contact-section__grid">
        <div class="contact-section__intro reveal">
            <h2 id="contact-title">Tell us what</h2>
            <p class="lead-italic">Send us your product idea, quantity, logo, wording, preferred material, approximate size, event date, and delivery or collection location. We will recommend suitable options and prepare a quotation.</p>
            <div class="btn-group btn-group--row">
                <a class="btn btn--light" href="https://wa.me/254790355707" target="_blank" rel="noopener noreferrer">Whatsapp KORA</a>
                <a class="btn btn--light" href="mailto:<?= SITE_EMAIL ?>">Email KORA</a>
            </div>
        </div>
        <div class="reveal">
            <form class="quote-form" id="quote-form" action="/process-quote.php" method="post" enctype="multipart/form-data" novalidate>
                <div class="form-row">
                    <div class="form-field">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" required autocomplete="name" data-validate="required">
                        <p class="form-field__error" id="name-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="organization">Organization or company</label>
                        <input type="text" id="organization" name="organization" autocomplete="organization" data-validate="optional">
                        <p class="form-field__error" id="organization-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="phone">Phone or WhatsApp</label>
                        <input type="tel" id="phone" name="phone" required autocomplete="tel" data-validate="phone">
                        <p class="form-field__error" id="phone-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required autocomplete="email" data-validate="email">
                        <p class="form-field__error" id="email-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="event_type">Event type</label>
                        <input type="text" id="event_type" name="event_type" required data-validate="required">
                        <p class="form-field__error" id="event_type-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="product">Product required</label>
                        <input type="text" id="product" name="product" required data-validate="required">
                        <p class="form-field__error" id="product-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-field">
                        <label for="quantity">Estimated quantity</label>
                        <input type="text" id="quantity" name="quantity" required data-validate="required">
                        <p class="form-field__error" id="quantity-error" role="alert"></p>
                    </div>
                    <div class="form-field">
                        <label for="event_date">Event date</label>
                        <input type="date" id="event_date" name="event_date" required data-validate="required">
                        <p class="form-field__error" id="event_date-error" role="alert"></p>
                    </div>
                </div>
                <div class="form-field">
                    <label for="message">Message or design brief</label>
                    <textarea id="message" name="message" required data-validate="required"></textarea>
                    <p class="form-field__error" id="message-error" role="alert"></p>
                </div>
                <div class="form-field">
                    <label for="inspo_files">Inspiration files</label>
                    <input type="file" id="inspo_files" name="inspo_files[]" accept=".pdf,.png,.webp,.jpg,.jpeg,application/pdf,image/png,image/webp,image/jpeg" multiple data-validate="inspo-files">
                    <p class="form-field__hint">Optional. PDF, PNG, WebP, or JPG — up to 20MB per file.</p>
                    <p class="form-field__error" id="inspo_files-error" role="alert"></p>
                </div>
                <button type="submit" class="btn btn--solid">Request a Quotation</button>
                <p class="form-field__error" id="form-status" role="status"></p>
            </form>
        </div>
    </div>
</section>
