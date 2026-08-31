<?php

declare(strict_types=1);

/**
 * @param array{
 *     id: string,
 *     number: string,
 *     title: string,
 *     summary: string,
 *     body: string,
 *     tips: array<int, string>,
 *     image: array{file: string, alt: string, width: int, height: int},
 *     reverse?: bool
 * } $step
 */
function render_how_step_detail(array $step): void
{
    $reverseClass = !empty($step['reverse']) ? ' how-step-detail--reverse' : '';
    ?>
    <article class="how-step-detail<?= $reverseClass ?> reveal" id="<?= htmlspecialchars($step['id']) ?>">
        <div class="container">
            <div class="how-step-detail__grid">
                <div class="how-step-detail__media">
                    <figure class="how-step-detail__image">
                        <img
                            src="<?= img($step['image']['file']) ?>"
                            alt="<?= htmlspecialchars($step['image']['alt']) ?>"
                            width="<?= (int) $step['image']['width'] ?>"
                            height="<?= (int) $step['image']['height'] ?>"
                            loading="lazy"
                        >
                    </figure>
                    <span class="how-step-detail__badge" aria-hidden="true"><?= htmlspecialchars($step['number']) ?></span>
                </div>
                <div class="how-step-detail__content">
                    <p class="how-step-detail__label">Step <?= htmlspecialchars($step['number']) ?></p>
                    <h2 class="how-step-detail__title"><?= htmlspecialchars($step['title']) ?></h2>
                    <p class="how-step-detail__summary lead-italic"><?= htmlspecialchars($step['summary']) ?></p>
                    <p class="how-step-detail__body text-body"><?= htmlspecialchars($step['body']) ?></p>
                    <ul class="how-step-detail__tips">
                        <?php foreach ($step['tips'] as $tip): ?>
                            <li><?= htmlspecialchars($tip) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </article>
    <?php
}

$step_details = [
    [
        'id' => 'step-tell-us',
        'number' => '01',
        'title' => 'Tell us about your order',
        'summary' => 'Start with the event, the product, and the outcome you want people to feel.',
        'body' => 'Reach out with the basics: what you are celebrating, what you need made, how many pieces you require, and when you need them ready. Whether it is a marathon, prizegiving, corporate campaign, or branded giveaway, this first conversation helps us understand scope and advise on the best materials and formats.',
        'tips' => [
            'Share your event date and ideal delivery window',
            'Mention product type — medals, awards, souvenirs, or a mix',
            'Include approximate quantities so we can plan production',
        ],
        'image' => ['file' => 'sample-team.jpg', 'alt' => 'Team celebrating with custom KORA awards', 'width' => 600, 'height' => 400],
    ],
    [
        'id' => 'step-send-details',
        'number' => '02',
        'title' => 'Send your design details',
        'summary' => 'Give us the artwork, names, and specifications we need to shape your pieces.',
        'body' => 'Once we know the direction, send your logo files, wording, dates, colour preferences, ribbon choices, and any reference images. If you do not have a finished design yet, that is fine — we can work from a simple brief and help refine the layout before production begins.',
        'tips' => [
            'Logo files in PDF, PNG, or AI work best',
            'List names, titles, or categories if they vary per piece',
            'Note ribbon colours, sizes, or packaging preferences',
        ],
        'image' => ['file' => 'sample-office.jpg', 'alt' => 'Corporate team reviewing branded award details', 'width' => 600, 'height' => 400],
        'reverse' => true,
    ],
    [
        'id' => 'step-get-quote',
        'number' => '03',
        'title' => 'Receive your quote',
        'summary' => 'We respond with a clear design direction and pricing based on your brief.',
        'body' => 'Our team reviews your requirements and prepares a quotation covering design approach, materials, quantities, and timeline. You will know what is included before anything goes into production, with room to adjust scope if needed.',
        'tips' => [
            'Quotes reflect material choice, complexity, and volume',
            'We can suggest alternatives to match your budget',
            'Ask questions — we are happy to explain each line item',
        ],
        'image' => ['file' => 'sample-award-2.jpg', 'alt' => 'Engraved award sample prepared for client review', 'width' => 600, 'height' => 394],
    ],
    [
        'id' => 'step-approve',
        'number' => '04',
        'title' => 'Approve and confirm',
        'summary' => 'Review the direction, confirm details, and secure your production slot.',
        'body' => 'When you are happy with the quote and design approach, approve the order and confirm with a deposit where required. This locks in your specifications and schedules your job in our workshop so we can meet your event deadline with confidence.',
        'tips' => [
            'Double-check spelling, dates, and logo placement',
            'Confirm quantities before production starts',
            'Approval triggers scheduling in the KORA workshop',
        ],
        'image' => ['file' => 'process-craft.jpg', 'alt' => 'Craftsperson finishing a custom award in the workshop', 'width' => 900, 'height' => 600],
        'reverse' => true,
    ],
    [
        'id' => 'step-deliver',
        'number' => '05',
        'title' => 'Collect or receive delivery',
        'summary' => 'Your finished pieces are prepared, checked, and ready for the moment.',
        'body' => 'We produce your order in-house, inspect each piece, and prepare everything for handover. Collect from our Nanyuki workshop or arrange delivery depending on your location and schedule — so your awards, medals, or souvenirs arrive ready for the presentation.',
        'tips' => [
            'Lead times depend on quantity and complexity',
            'Collection available from our Laikipia workshop',
            'Delivery options can be discussed when you confirm',
        ],
        'image' => ['file' => 'about-workshop.jpg', 'alt' => 'Finished KORA products ready in the workshop', 'width' => 1600, 'height' => 1067],
    ],
];

$prepare_items = [
    ['title' => 'Event details', 'text' => 'Date, venue, and when you need the order ready'],
    ['title' => 'Product type', 'text' => 'Medals, trophies, souvenirs, or a combination'],
    ['title' => 'Quantities', 'text' => 'How many pieces you need for each category'],
    ['title' => 'Branding assets', 'text' => 'Logo files, colours, and any reference artwork'],
    ['title' => 'Copy & names', 'text' => 'Wording, recipient names, or category labels'],
    ['title' => 'Budget range', 'text' => 'Helps us recommend the best material and finish'],
];
?>
<section class="how-hero" aria-labelledby="how-hero-title">
    <img
        class="how-hero__image reveal"
        src="<?= img('workshop-banner.jpg') ?>"
        alt="KORA workshop where custom awards are made by hand"
        width="1600"
        height="1067"
        loading="eager"
    >
    <div class="how-hero__overlay">
        <div class="how-hero__content reveal">
            <h1 id="how-hero-title" class="how-hero__title">How It Works</h1>
            <p class="how-hero__subtitle lead-italic">A clear five-step path from first brief to finished pieces — made in-house in Laikipia.</p>
        </div>
    </div>
</section>

<section class="how-intro section" aria-labelledby="how-intro-title">
    <div class="container">
        <div class="how-intro__inner reveal">
            <h2 id="how-intro-title" class="how-intro__title">Simple process, careful craft</h2>
            <p class="how-intro__text text-body">
                Ordering from KORA is straightforward. You share your event and design needs, we shape the product with you, and our workshop handles production locally in Nanyuki. The result is recognition that feels personal, durable, and ready for the moment it matters.
            </p>
            <div class="how-intro__nav">
                <a class="how-intro__link" href="#step-tell-us">Step 01</a>
                <a class="how-intro__link" href="#step-send-details">Step 02</a>
                <a class="how-intro__link" href="#step-get-quote">Step 03</a>
                <a class="how-intro__link" href="#step-approve">Step 04</a>
                <a class="how-intro__link" href="#step-deliver">Step 05</a>
            </div>
        </div>
    </div>
</section>

<section class="how-process section" aria-labelledby="how-process-title">
    <div class="container">
        <div class="how-process__header reveal">
            <h2 id="how-process-title" class="section-label">The five steps</h2>
            <p class="lead-italic">From first message to finished pieces — here is how every KORA order moves forward.</p>
        </div>
        <ol class="order-steps reveal">
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true">01</span>
                <div class="order-step__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4-.8L3 20l1.8-4A8.96 8.96 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg>
                </div>
                <h3 class="order-step__title">Tell us</h3>
                <p class="order-step__desc">The event &amp; product</p>
                <span class="order-step__arrow" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </li>
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true">02</span>
                <div class="order-step__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>
                </div>
                <h3 class="order-step__title">Send Details</h3>
                <p class="order-step__desc">Logo, names, dates</p>
                <span class="order-step__arrow" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </li>
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true">03</span>
                <div class="order-step__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h5"/></svg>
                </div>
                <h3 class="order-step__title">Get a Quote</h3>
                <p class="order-step__desc">Design + price</p>
                <span class="order-step__arrow" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </li>
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true">04</span>
                <div class="order-step__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <h3 class="order-step__title">Approve</h3>
                <p class="order-step__desc">Confirm &amp; deposit</p>
                <span class="order-step__arrow" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
            </li>
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true">05</span>
                <div class="order-step__icon" aria-hidden="true">
                    <svg class="order-step__icon-svg--truck" viewBox="0 0 24 24" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" aria-hidden="true">
                        <path d="M3.25 5.5h9.75c.69 0 1.25.56 1.25 1.25v6.75H3.25V5.5zm10.75 1.35h4.65l2.35 2.05v4.35H14V6.85zM3 13.35h17.75v1.15c0 .58-.47 1.05-1.05 1.05H3.3c-.58 0-1.05-.47-1.05-1.05v-1.15zM7 14.35a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zm10 0a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zM15.15 8.15h3.35v3.05h-3.35V8.15zM7 15.55a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5zm10 0a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z"/>
                    </svg>
                </div>
                <h3 class="order-step__title">Collect/Deliver</h3>
                <p class="order-step__desc">Pieces ready</p>
            </li>
        </ol>
    </div>
</section>

<section class="how-steps-detail" aria-label="Detailed order steps">
    <?php foreach ($step_details as $step): ?>
        <?php render_how_step_detail($step); ?>
    <?php endforeach; ?>
</section>

<section class="how-prepare section" aria-labelledby="how-prepare-title">
    <div class="container">
        <div class="how-prepare__inner reveal">
            <div class="how-prepare__copy">
                <h2 id="how-prepare-title" class="how-prepare__title">What to prepare</h2>
                <p class="text-body">Having these details ready helps us respond faster with an accurate quote and design direction.</p>
            </div>
            <div class="how-prepare__grid">
                <?php foreach ($prepare_items as $item): ?>
                    <article class="how-prepare__item">
                        <h3><?= htmlspecialchars($item['title']) ?></h3>
                        <p><?= htmlspecialchars($item['text']) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="how-notes section" aria-labelledby="how-notes-title">
    <div class="container">
        <div class="how-notes__inner reveal">
            <h2 id="how-notes-title" class="how-notes__title">Good to know</h2>
            <div class="how-notes__grid">
                <article class="how-notes__card">
                    <h3>Made in-house</h3>
                    <p>Every piece is produced in our Nanyuki workshop — not outsourced — so quality and timelines stay in our hands.</p>
                </article>
                <article class="how-notes__card">
                    <h3>Lead times vary</h3>
                    <p>Timing depends on quantity, material, and detail. Share your event date early so we can schedule production properly.</p>
                </article>
                <article class="how-notes__card">
                    <h3>We guide the design</h3>
                    <p>No finished artwork? Share your logo and brief — we will help shape a layout that works for your product and budget.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="how-cta section" aria-labelledby="how-cta-title">
    <div class="container">
        <div class="how-cta__inner reveal">
            <h2 id="how-cta-title" class="how-cta__title">Ready to start your order?</h2>
            <p class="how-cta__text lead-italic">Tell us about your event and we will take it from there.</p>
            <div class="btn-group">
                <a class="btn btn--solid" href="/request-quote.php">Request a Quotation</a>
                <a class="btn btn--ghost" href="/products.php">View our products</a>
            </div>
        </div>
    </div>
</section>
