<?php

declare(strict_types=1);

$hero_content = [
    'kicker' => 'Welcome to Kora Laser Craft',
    'title_accent' => 'Recognition,',
    'title_rest' => 'Made Personal',
    'subtitle' => 'Crafting Custom awards, medals and souvenirs.',
];

$hero_slides = [
    [
        'file' => 'awards/Award_hero.jpeg',
        'alt' => 'Collection of custom KORA awards and trophies',
        'width' => 4558,
        'height' => 3376,
    ],
    [
        'file' => 'medals/hero.jpeg',
        'alt' => 'Custom KORA medals laid out on wood',
        'width' => 2752,
        'height' => 1536,
    ],
    [
        'file' => 'souvenir/souvenir_hero.jpeg',
        'alt' => 'Custom KORA souvenirs and keepsakes',
        'width' => 2752,
        'height' => 1536,
    ],
    [
        'file' => 'workshop_hero.jpeg',
        'alt' => 'Laser engraving detail on wood in the KORA workshop',
        'width' => 1200,
        'height' => 800,
    ],
    [
        'file' => 'laser_1.jpg',
        'alt' => 'Close-up of the laser engraving a custom design into wood',
        'width' => 6000,
        'height' => 3376,
    ],
];
?>
<section class="hero hero-slider" aria-roledescription="carousel" aria-labelledby="hero-title" data-hero-slider>
    <div class="hero-slider__viewport">
        <div class="hero-slider__track">
            <?php foreach ($hero_slides as $index => $slide): ?>
                <div
                    class="hero-slider__slide<?= $index === 0 ? ' is-active' : '' ?>"
                    aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>"
                    data-slide-index="<?= $index ?>"
                >
                    <img
                        src="<?= img($slide['file']) ?>"
                        alt="<?= htmlspecialchars($slide['alt']) ?>"
                        width="<?= (int) $slide['width'] ?>"
                        height="<?= (int) $slide['height'] ?>"
                        loading="eager"
                        <?= $index === 0 ? 'fetchpriority="high"' : '' ?>
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="hero-slider__overlay">
        <div class="hero-slider__content">
            <p class="hero-slider__kicker"><?= htmlspecialchars($hero_content['kicker']) ?></p>
            <h1 id="hero-title" class="hero-slider__title">
                <span class="hero-slider__title-accent"><?= htmlspecialchars($hero_content['title_accent']) ?></span>
                <?= htmlspecialchars($hero_content['title_rest']) ?>
            </h1>
            <p class="hero-slider__subtitle"><?= htmlspecialchars($hero_content['subtitle']) ?></p>
            <div class="btn-group hero-slider__actions">
                <a class="btn btn--solid" href="/work-samples.php">View Our Work</a>
                <a class="btn btn--light" href="/request-quote.php">Request a Quotation</a>
            </div>
        </div>
    </div>

    <div class="hero-slider__chrome">
        <div class="hero-slider__controls">
            <button type="button" class="hero-slider__arrow hero-slider__arrow--prev" aria-label="Previous slide">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14.5 6.5 9 12l5.5 5.5"/>
                </svg>
            </button>
            <div class="hero-slider__dots" role="tablist" aria-label="Choose a slide">
                <?php foreach ($hero_slides as $index => $slide): ?>
                    <button
                        type="button"
                        class="hero-slider__dot<?= $index === 0 ? ' is-active' : '' ?>"
                        role="tab"
                        aria-label="Show slide <?= $index + 1 ?> of <?= count($hero_slides) ?>"
                        aria-selected="<?= $index === 0 ? 'true' : 'false' ?>"
                        data-slide-to="<?= $index ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
            <button type="button" class="hero-slider__arrow hero-slider__arrow--next" aria-label="Next slide">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9.5 6.5 15 12l-5.5 5.5"/>
                </svg>
            </button>
        </div>
    </div>
</section>
