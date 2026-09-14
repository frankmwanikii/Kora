<?php

declare(strict_types=1);

$hero = cms_section('hero');

$hero_content = [
    'kicker' => (string) ($hero['kicker'] ?? 'Welcome to Kora Laser Craft'),
    'title_accent' => (string) ($hero['title_accent'] ?? 'Recognition,'),
    'title_rest' => (string) ($hero['title_rest'] ?? 'Made Personal'),
    'subtitle' => (string) ($hero['subtitle'] ?? 'Crafting Custom awards, medals and souvenirs.'),
];

$hero_slides = cms_list('hero', 'slides', [
    [
        'file' => 'awards/Award_hero.webp',
        'alt' => 'Collection of custom KORA awards and trophies',
        'width' => 4558,
        'height' => 3376,
    ],
    [
        'file' => 'medals/hero.webp',
        'alt' => 'Custom KORA medals laid out on wood',
        'width' => 2752,
        'height' => 1536,
    ],
    [
        'file' => 'souvenir/souvenir_hero.webp',
        'alt' => 'Custom KORA souvenirs and keepsakes',
        'width' => 2752,
        'height' => 1536,
    ],
    [
        'file' => 'workshop_hero.webp',
        'alt' => 'Laser engraving detail on wood in the KORA workshop',
        'width' => 1200,
        'height' => 800,
    ],
    [
        'file' => 'laser_1.webp',
        'alt' => 'Close-up of the laser engraving a custom design into wood',
        'width' => 6000,
        'height' => 3376,
    ],
]);

$hero_actions = cms_list('hero', 'actions', [
    ['label' => 'View Our Work', 'href' => '/work-samples.php', 'style' => 'solid'],
    ['label' => 'Request a Quotation', 'href' => '/request-quote.php', 'style' => 'light'],
]);

if ($hero_actions === []) {
    $hero_actions = [
        ['label' => 'View Our Work', 'href' => '/work-samples.php', 'style' => 'solid'],
        ['label' => 'Request a Quotation', 'href' => '/request-quote.php', 'style' => 'light'],
    ];
}
?>
<section class="hero hero-slider" aria-roledescription="carousel" aria-labelledby="hero-title" data-hero-slider>
    <div class="hero-slider__viewport">
        <div class="hero-slider__track">
            <?php foreach ($hero_slides as $index => $slide): ?>
                <?php
                if (!is_array($slide)) {
                    continue;
                }
                $file = (string) ($slide['file'] ?? '');
                if ($file === '') {
                    continue;
                }
                ?>
                <div
                    class="hero-slider__slide<?= $index === 0 ? ' is-active' : '' ?>"
                    aria-hidden="<?= $index === 0 ? 'false' : 'true' ?>"
                    data-slide-index="<?= $index ?>"
                >
                    <img
                        src="<?= img($file) ?>"
                        alt="<?= htmlspecialchars((string) ($slide['alt'] ?? '')) ?>"
                        width="<?= (int) ($slide['width'] ?? 1200) ?>"
                        height="<?= (int) ($slide['height'] ?? 800) ?>"
                        loading="eager"
                        <?= $index === 0 ? 'fetchpriority="high"' : '' ?>
                    >
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="hero-slider__overlay">
        <div class="hero-slider__content" data-hero-typewriter>
            <p
                class="hero-slider__kicker is-complete"
                data-typewriter
                data-typewriter-text="<?= htmlspecialchars($hero_content['kicker'], ENT_QUOTES) ?>"
            >
                <span class="hero-slider__typed"><?= htmlspecialchars($hero_content['kicker']) ?></span><span class="hero-slider__caret" aria-hidden="true"></span>
            </p>
            <h1 id="hero-title" class="hero-slider__title">
                <span
                    class="hero-slider__title-accent"
                    data-typewriter
                    data-typewriter-text="<?= htmlspecialchars($hero_content['title_accent'], ENT_QUOTES) ?>"
                ><span class="hero-slider__typed"><?= htmlspecialchars($hero_content['title_accent']) ?></span></span><span
                    data-typewriter
                    data-typewriter-text="<?= htmlspecialchars(' ' . ltrim($hero_content['title_rest']), ENT_QUOTES) ?>"
                ><span class="hero-slider__typed"><?= htmlspecialchars(' ' . ltrim($hero_content['title_rest'])) ?></span></span><span class="hero-slider__caret hero-slider__caret--title" aria-hidden="true"></span>
            </h1>
            <p
                class="hero-slider__subtitle"
                data-typewriter
                data-typewriter-text="<?= htmlspecialchars($hero_content['subtitle'], ENT_QUOTES) ?>"
            >
                <span class="hero-slider__typed"><?= htmlspecialchars($hero_content['subtitle']) ?></span><span class="hero-slider__caret" aria-hidden="true"></span>
            </p>
            <div class="btn-group hero-slider__actions is-visible">                <?php foreach ($hero_actions as $actionIndex => $action): ?>
                    <?php
                    if (!is_array($action)) {
                        continue;
                    }
                    $label = (string) ($action['label'] ?? '');
                    $href = (string) ($action['href'] ?? '#');
                    $style = (string) ($action['style'] ?? ($actionIndex === 0 ? 'solid' : 'light'));
                    if ($label === '') {
                        continue;
                    }
                    $btnClass = $style === 'light' ? 'btn btn--light' : 'btn btn--solid';
                    ?>
                    <a class="<?= $btnClass ?>" href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($label) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="hero-slider__chrome">
        <div class="hero-slider__dots" role="tablist" aria-label="Choose a slide">
            <?php foreach ($hero_slides as $index => $slide): ?>
                <?php if (!is_array($slide) || (string) ($slide['file'] ?? '') === '') {
                    continue;
                } ?>
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
    </div>
</section>
