<?php

declare(strict_types=1);

$hero_slides = [
    [
        'file' => 'hero-laser.jpg',
        'alt' => 'Laser engraving detail on wood in the KORA workshop',
        'kicker' => 'Made in-house in Laikipia',
        'title' => 'Recognition, Made to Last',
        'subtitle' => 'Custom awards, medals & souvenirs for the people worth celebrating.',
        'width' => 1200,
        'height' => 800,
    ],
    [
        'file' => 'medals-collection.jpg',
        'alt' => 'Collection of custom KORA medals',
        'kicker' => 'Medals',
        'title' => 'Medals cut for every event',
        'subtitle' => 'Any shape, layered depth, colour fill, and ribbon — from school sports days to marathons.',
        'width' => 800,
        'height' => 533,
    ],
    [
        'file' => 'award-trophy.jpg',
        'alt' => 'Handcrafted wooden trophy by KORA',
        'kicker' => 'Awards & trophies',
        'title' => 'Trophies with presence',
        'subtitle' => 'Solid wood, MDF, plywood, or acrylic — designed, cut, and finished by hand in Nanyuki.',
        'width' => 900,
        'height' => 1350,
    ],
    [
        'file' => 'process-craft.jpg',
        'alt' => 'Hand finishing a custom piece in the KORA workshop',
        'kicker' => 'The workshop',
        'title' => 'Crafted, not catalogued',
        'subtitle' => 'Every piece is made in-house, so we can match your brief, your branding, and your event date.',
        'width' => 900,
        'height' => 600,
    ],
    [
        'file' => 'about-workshop.jpg',
        'alt' => 'KORA workshop preparing custom awards',
        'kicker' => 'Souvenirs',
        'title' => 'Keepsakes people keep',
        'subtitle' => 'Tumblers, magnets, pins, and branded giveaways that carry your event beyond the day itself.',
        'width' => 1600,
        'height' => 1067,
    ],
];
?>
<section class="hero hero-slider" aria-roledescription="carousel" aria-label="Featured KORA work" data-hero-slider>
    <div class="hero-slider__viewport">
        <div class="hero-slider__track">
            <?php foreach ($hero_slides as $index => $slide): ?>
                <article
                    class="hero-slider__slide<?= $index === 0 ? ' is-active' : '' ?>"
                    aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>"
                    data-slide-index="<?= $index ?>"
                >
                    <img
                        src="<?= img($slide['file']) ?>"
                        alt="<?= htmlspecialchars($slide['alt']) ?>"
                        width="<?= (int) $slide['width'] ?>"
                        height="<?= (int) $slide['height'] ?>"
                        <?= $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' ?>
                    >
                    <div class="hero-slider__overlay">
                        <div class="hero-slider__content">
                            <p class="hero-slider__kicker"><?= htmlspecialchars($slide['kicker']) ?></p>
                            <?php if ($index === 0): ?>
                                <h1 id="hero-title" class="hero-slider__title"><?= htmlspecialchars($slide['title']) ?></h1>
                            <?php else: ?>
                                <p class="hero-slider__title"><?= htmlspecialchars($slide['title']) ?></p>
                            <?php endif; ?>
                            <p class="hero-slider__subtitle lead-italic"><?= htmlspecialchars($slide['subtitle']) ?></p>
                            <div class="btn-group">
                                <a class="btn btn--ghost" href="/work-samples.php">View our work</a>
                                <a class="btn btn--solid" href="/request-quote.php">Request a Quotation</a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <button type="button" class="hero-slider__arrow hero-slider__arrow--prev" aria-label="Previous slide">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M14.5 6.5 9 12l5.5 5.5"/>
        </svg>
    </button>
    <button type="button" class="hero-slider__arrow hero-slider__arrow--next" aria-label="Next slide">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M9.5 6.5 15 12l-5.5 5.5"/>
        </svg>
    </button>

    <div class="hero-slider__dots" role="tablist" aria-label="Choose a slide">
        <?php foreach ($hero_slides as $index => $slide): ?>
            <button
                type="button"
                class="hero-slider__dot<?= $index === 0 ? ' is-active' : '' ?>"
                role="tab"
                aria-label="Show slide <?= $index + 1 ?>: <?= htmlspecialchars($slide['title']) ?>"
                aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                data-slide-to="<?= $index ?>"
            ></button>
        <?php endforeach; ?>
    </div>
</section>
