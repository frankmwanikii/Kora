<?php

declare(strict_types=1);

/**
 * @param array<int, array{file: string, alt: string, width: int, height: int}> $images
 */
function render_sample_gallery(string $label, array $images): void
{
    ?>
    <div class="sample-gallery-wrap">
        <div class="sample-gallery" tabindex="0" role="region" aria-label="<?= htmlspecialchars($label) ?> sample gallery">
            <?php foreach ($images as $image): ?>
                <button
                    type="button"
                    class="sample-gallery__item"
                    aria-label="View larger: <?= htmlspecialchars($image['alt']) ?>"
                >
                    <img
                        src="<?= img($image['file']) ?>"
                        alt="<?= htmlspecialchars($image['alt']) ?>"
                        width="<?= (int) $image['width'] ?>"
                        height="<?= (int) $image['height'] ?>"
                        loading="lazy"
                        draggable="false"
                    >
                </button>
            <?php endforeach; ?>
        </div>
        <div class="sample-gallery__controls">
            <button type="button" class="sample-gallery__arrow sample-gallery__arrow--prev" aria-label="Scroll <?= htmlspecialchars($label) ?> gallery left">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14.5 6.5 9 12l5.5 5.5"/>
                </svg>
            </button>
            <button type="button" class="sample-gallery__arrow sample-gallery__arrow--next" aria-label="Scroll <?= htmlspecialchars($label) ?> gallery right">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M9.5 6.5 15 12l-5.5 5.5"/>
                </svg>
            </button>
        </div>
    </div>
    <?php
}

$medal_samples = [
    ['file' => 'medals/marathon.jpg', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
    ['file' => 'medals/bike.jpg', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
    ['file' => 'medals/football.jpg', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
    
];

$award_samples = [
    ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Business award trophy on a wooden base', 'width' => 2752, 'height' => 1536],
    ['file' => 'awards/Aw5.jpg', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
    ['file' => 'awards/Golf_award.jpg', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
    ['file' => 'awards/Padel Award.jpg', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
    ['file' => 'awards/Graduation.jpg', 'alt' => 'Custom graduation award', 'width' => 3682, 'height' => 3376],
    ['file' => 'awards/Football.jpg', 'alt' => 'Custom football tournament award', 'width' => 3376, 'height' => 5221],
    ['file' => 'awards/teacher.jpg', 'alt' => 'Custom teacher appreciation award', 'width' => 3203, 'height' => 3680],
    ['file' => 'awards/retire.jpg', 'alt' => 'Custom retirement plaque', 'width' => 3448, 'height' => 3376],
    ['file' => 'awards/WOODEN AWARD.png', 'alt' => 'Custom layered wooden cycling award', 'width' => 1207, 'height' => 1303],
    ['file' => 'awards/Aw4.jpg', 'alt' => 'Multi-layer award sample', 'width' => 3185, 'height' => 3376],
    ['file' => 'awards/appreciate.png', 'alt' => 'Appreciation award sample', 'width' => 1254, 'height' => 1254],
    ['file' => 'awards/Golf_Award.jpg', 'alt' => 'Appreciation award sample', 'width' => 1254, 'height' => 1254],

];

$souvenir_samples = [
    ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Branded travel souvenir gift set', 'width' => 2752, 'height' => 1536],
    ['file' => 'souvenir/travel.png', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
];
?>
<section class="work-samples-hero" aria-labelledby="samples-title">
    <img
        class="work-samples-hero__image reveal"
        src="<?= img('workshop_hero.jpeg') ?>"
        alt="Custom KORA awards, medals, and souvenirs displayed in the workshop"
        width="2752"
        height="1536"
        loading="eager"
    >
    <div class="work-samples-hero__overlay">
        <h1 id="samples-title" class="work-samples-hero__title reveal">Our Studio Samples</h1>
    </div>
</section>

<section class="work-samples" aria-label="Work sample categories">
    <article class="sample-block sample-block--medals reveal">
        <div class="container">
            <div class="sample-block__header">
                <h2 class="sample-block__title">Medals</h2>
                <p class="sample-block__text lead-italic">Made in premium MDF, solid wood, plywood, or acrylic, single or multi layered, and cut into any shape. Medals give every participant something to keep, building a sense of belonging and making your event more memorable.</p>
            </div>
            <?php render_sample_gallery('Medals', $medal_samples); ?>
        </div>
    </article>

    <article class="sample-block sample-block--awards reveal">
        <div class="container">
            <div class="sample-block__header sample-block__header--reverse">
                <h2 class="sample-block__title">Awards &amp; Trophies</h2>
                <p class="sample-block__text lead-italic">Made in the same range of materials, single or multi layered, and cut into any shape. Awards give recognition a physical form, motivating winners and encouraging others to perform better next time.</p>
            </div>
            <?php render_sample_gallery('Awards and trophies', $award_samples); ?>
        </div>
    </article>

    <article class="sample-block sample-block--souvenirs reveal">
        <div class="container">
            <div class="sample-block__header">
                <h2 class="sample-block__title">Souvenirs &amp; Keepsakes</h2>
                <p class="sample-block__text lead-italic">From fridge magnets and tumblers to badge pins and other branded giveaways. Souvenirs keep your organisation present in people's everyday lives, extending your event's reach and building lasting goodwill.</p>
            </div>
            <?php render_sample_gallery('Souvenirs', $souvenir_samples); ?>
        </div>
    </article>
</section>

<div class="sample-lightbox" id="sample-lightbox" hidden>
    <div class="sample-lightbox__backdrop" data-lightbox-close></div>
    <div class="sample-lightbox__dialog" role="dialog" aria-modal="true" aria-labelledby="sample-lightbox-caption">
        <button type="button" class="sample-lightbox__close" data-lightbox-close aria-label="Close image viewer">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" aria-hidden="true">
                <path d="M6 6l12 12M18 6 6 18"/>
            </svg>
        </button>
        <button type="button" class="sample-lightbox__nav sample-lightbox__nav--prev" data-lightbox-prev aria-label="Previous image">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14.5 6.5 9 12l5.5 5.5"/>
            </svg>
        </button>
        <figure class="sample-lightbox__figure">
            <img class="sample-lightbox__image" src="" alt="" width="1200" height="1200">
            <figcaption class="sample-lightbox__caption" id="sample-lightbox-caption"></figcaption>
        </figure>
        <button type="button" class="sample-lightbox__nav sample-lightbox__nav--next" data-lightbox-next aria-label="Next image">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M9.5 6.5 15 12l-5.5 5.5"/>
            </svg>
        </button>
    </div>
</div>
