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

$how = cms_section('how_it_works');

$howHero = is_array($how['hero'] ?? null) ? $how['hero'] : [];
$howHeroImage = is_array($howHero['image'] ?? null) ? $howHero['image'] : [];
$howHeroFile = (string) ($howHeroImage['file'] ?? 'laser_1.jpg');
$howHeroAlt = (string) ($howHeroImage['alt'] ?? 'Laser engraving a custom design in the KORA workshop');
$howHeroTitle = (string) ($howHero['title'] ?? 'How It Works');

$howIntro = is_array($how['intro'] ?? null) ? $how['intro'] : [];
$howIntroTitle = (string) ($howIntro['title'] ?? 'Simple process, careful craft');
$howIntroText = (string) ($howIntro['text'] ?? 'Ordering from KORA is straightforward. You share your event and design needs, we shape the product with you, and our workshop handles production locally in Nanyuki. The result is recognition that feels personal, durable, and ready for the moment it matters.');

$stepsSummaryDefault = [
    ['number' => '01', 'title' => 'Tell us', 'description' => 'The event & product'],
    ['number' => '02', 'title' => 'Send Details', 'description' => 'Logo, names, dates'],
    ['number' => '03', 'title' => 'Get a Quote', 'description' => 'Design + price'],
    ['number' => '04', 'title' => 'Approve', 'description' => 'Confirm & deposit'],
    ['number' => '05', 'title' => 'Collection or Delivery', 'description' => 'Pieces ready'],
];
$steps_summary = cms_list('how_it_works', 'steps_summary', $stepsSummaryDefault);
if ($steps_summary === []) {
    $steps_summary = $stepsSummaryDefault;
}

$stepDetailsDefault = [
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
        'image' => ['file' => 'medals/hero.jpeg', 'alt' => 'Custom KORA medals for events and challenges', 'width' => 2752, 'height' => 1536],
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
        'image' => ['file' => 'awards/Aw1.jpg', 'alt' => 'Laser engraving a custom design in the KORA workshop', 'width' => 3376, 'height' => 3593],
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
        'image' => ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Finished award sample prepared for client review', 'width' => 2752, 'height' => 1536],
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
        'image' => ['file' => 'laser_1.jpg', 'alt' => 'Close-up of the laser engraving a custom design into wood', 'width' => 6000, 'height' => 3376],
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
        'image' => ['file' => 'medals/workshop_hero.jpeg', 'alt' => 'Finished KORA awards, medals, and souvenirs ready for handover', 'width' => 2752, 'height' => 1536],
    ],
];

$step_details = cms_list('how_it_works', 'step_details', $stepDetailsDefault);
if ($step_details === []) {
    $step_details = $stepDetailsDefault;
}

$prepareDefault = [
    ['title' => 'Event details', 'text' => 'Date, venue, and when you need the order ready'],
    ['title' => 'Product type', 'text' => 'Medals, trophies, souvenirs, or a combination'],
    ['title' => 'Quantities', 'text' => 'How many pieces you need for each category'],
    ['title' => 'Branding assets', 'text' => 'Logo files, colours, and any reference artwork'],
    ['title' => 'Copy & names', 'text' => 'Wording, recipient names, or category labels'],
    ['title' => 'Budget range', 'text' => 'Helps us recommend the best material and finish'],
];

$howPrepare = is_array($how['prepare'] ?? null) ? $how['prepare'] : [];
$howPrepareTitle = (string) ($howPrepare['title'] ?? 'What to prepare');
$howPrepareText = (string) ($howPrepare['text'] ?? 'Having these details ready helps us respond faster with an accurate quote and design direction.');
$prepare_items = is_array($howPrepare['items'] ?? null) ? $howPrepare['items'] : $prepareDefault;
if ($prepare_items === []) {
    $prepare_items = $prepareDefault;
}

$howNotes = is_array($how['notes'] ?? null) ? $how['notes'] : [];
$howNotesTitle = (string) ($howNotes['title'] ?? 'Good to know');
$howNotesItemsDefault = [
    ['title' => 'Made in-house', 'text' => 'Every piece is produced in our Nanyuki workshop — not outsourced — so quality and timelines stay in our hands.'],
    ['title' => 'Lead times vary', 'text' => 'Timing depends on quantity, material, and detail. Share your event date early so we can schedule production properly.'],
    ['title' => 'We guide the design', 'text' => 'No finished artwork? Share your logo and brief — we will help shape a layout that works for your product and budget.'],
];
$howNotesItems = is_array($howNotes['items'] ?? null) ? $howNotes['items'] : $howNotesItemsDefault;
if ($howNotesItems === []) {
    $howNotesItems = $howNotesItemsDefault;
}

$howCta = is_array($how['cta'] ?? null) ? $how['cta'] : [];
$howCtaTitle = (string) ($howCta['title'] ?? 'Ready to start your order?');
$howCtaText = (string) ($howCta['text'] ?? 'Tell us about your event and we will take it from there.');
$howCtaPrimaryHref = (string) ($howCta['primary_href'] ?? '/request-quote.php');
$howCtaPrimaryLabel = (string) ($howCta['primary_label'] ?? 'Request a Quotation');
$howCtaSecondaryHref = (string) ($howCta['secondary_href'] ?? '/products.php');
$howCtaSecondaryLabel = (string) ($howCta['secondary_label'] ?? 'View our products');
?>
<section class="how-hero" aria-labelledby="how-hero-title">
    <img
        class="how-hero__image reveal"
        src="<?= img($howHeroFile) ?>"
        alt="<?= htmlspecialchars($howHeroAlt) ?>"
        width="<?= (int) ($howHeroImage['width'] ?? 6000) ?>"
        height="<?= (int) ($howHeroImage['height'] ?? 3376) ?>"
        loading="eager"
    >
    <div class="how-hero__overlay">
        <div class="how-hero__content reveal">
            <h1 id="how-hero-title" class="how-hero__title"><?= htmlspecialchars($howHeroTitle) ?></h1>
        </div>
    </div>
</section>

<section class="how-intro section" aria-labelledby="how-intro-title">
    <div class="container">
        <div class="how-intro__inner reveal">
            <h2 id="how-intro-title" class="how-intro__title"><?= htmlspecialchars($howIntroTitle) ?></h2>
            <p class="how-intro__text text-body"><?= htmlspecialchars($howIntroText) ?></p>
            <div class="how-intro__nav">
                <?php foreach ($step_details as $step): ?>
                    <?php
                    if (!is_array($step) || (string) ($step['id'] ?? '') === '') {
                        continue;
                    }
                    ?>
                    <a class="how-intro__link" href="#<?= htmlspecialchars((string) $step['id']) ?>">Step <?= htmlspecialchars((string) ($step['number'] ?? '')) ?></a>
                <?php endforeach; ?>
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
            <?php
            $stepIcons = [
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4-.8L3 20l1.8-4A8.96 8.96 0 0 1 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/></svg>',
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/></svg>',
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6M8 13h8M8 17h5"/></svg>',
                '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 6 9 17l-5-5"/></svg>',
                '<svg class="order-step__icon-svg--truck" viewBox="0 0 24 24" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd" aria-hidden="true"><path d="M3.25 5.5h9.75c.69 0 1.25.56 1.25 1.25v6.75H3.25V5.5zm10.75 1.35h4.65l2.35 2.05v4.35H14V6.85zM3 13.35h17.75v1.15c0 .58-.47 1.05-1.05 1.05H3.3c-.58 0-1.05-.47-1.05-1.05v-1.15zM7 14.35a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zm10 0a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zM15.15 8.15h3.35v3.05h-3.35V8.15zM7 15.55a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5zm10 0a.75.75 0 1 0 0 1.5.75.75 0 0 0 0-1.5z"/></svg>',
            ];
            foreach ($steps_summary as $index => $step):
                if (!is_array($step)) {
                    continue;
                }
                $isLast = $index === count($steps_summary) - 1;
                $icon = $stepIcons[$index] ?? $stepIcons[0];
            ?>
            <li class="order-step">
                <span class="order-step__number" aria-hidden="true"><?= htmlspecialchars((string) ($step['number'] ?? '')) ?></span>
                <div class="order-step__icon" aria-hidden="true"><?= $icon ?></div>
                <h3 class="order-step__title"><?= htmlspecialchars((string) ($step['title'] ?? '')) ?></h3>
                <p class="order-step__desc"><?= htmlspecialchars((string) ($step['description'] ?? '')) ?></p>
                <?php if (!$isLast): ?>
                <span class="order-step__arrow" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </span>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
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
                <h2 id="how-prepare-title" class="how-prepare__title"><?= htmlspecialchars($howPrepareTitle) ?></h2>
                <p class="text-body"><?= htmlspecialchars($howPrepareText) ?></p>
            </div>
            <div class="how-prepare__grid">
                <?php foreach ($prepare_items as $item): ?>
                    <?php if (!is_array($item)) {
                        continue;
                    } ?>
                    <article class="how-prepare__item">
                        <h3><?= htmlspecialchars((string) ($item['title'] ?? '')) ?></h3>
                        <p><?= htmlspecialchars((string) ($item['text'] ?? '')) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="how-notes section" aria-labelledby="how-notes-title">
    <div class="container">
        <div class="how-notes__inner reveal">
            <h2 id="how-notes-title" class="how-notes__title"><?= htmlspecialchars($howNotesTitle) ?></h2>
            <div class="how-notes__grid">
                <?php foreach ($howNotesItems as $note): ?>
                    <?php if (!is_array($note)) {
                        continue;
                    } ?>
                    <article class="how-notes__card">
                        <h3><?= htmlspecialchars((string) ($note['title'] ?? '')) ?></h3>
                        <p><?= htmlspecialchars((string) ($note['text'] ?? '')) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="how-cta section" aria-labelledby="how-cta-title">
    <div class="container">
        <div class="how-cta__inner reveal">
            <h2 id="how-cta-title" class="how-cta__title"><?= htmlspecialchars($howCtaTitle) ?></h2>
            <p class="how-cta__text lead-italic"><?= htmlspecialchars($howCtaText) ?></p>
            <div class="btn-group">
                <a class="btn btn--solid" href="<?= htmlspecialchars($howCtaPrimaryHref) ?>"><?= htmlspecialchars($howCtaPrimaryLabel) ?></a>
                <a class="btn btn--ghost" href="<?= htmlspecialchars($howCtaSecondaryHref) ?>"><?= htmlspecialchars($howCtaSecondaryLabel) ?></a>
            </div>
        </div>
    </div>
</section>
