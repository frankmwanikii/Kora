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
                <img
                    src="<?= img($image['file']) ?>"
                    alt="<?= htmlspecialchars($image['alt']) ?>"
                    width="<?= (int) $image['width'] ?>"
                    height="<?= (int) $image['height'] ?>"
                    loading="lazy"
                    draggable="false"
                >
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
    ['file' => 'sample-marathon.jpg', 'alt' => 'Custom marathon medal sample', 'width' => 600, 'height' => 351],
    ['file' => 'sample-sports.jpg', 'alt' => 'Race medal on ribbon sample', 'width' => 600, 'height' => 400],
    ['file' => 'medals-collection.jpg', 'alt' => 'Wooden engraved medal sample', 'width' => 800, 'height' => 533],
    ['file' => 'sample-team.jpg', 'alt' => 'Event medal collection sample', 'width' => 600, 'height' => 400],
    ['file' => 'process-wood.jpg', 'alt' => 'Wood medal crafting in the KORA workshop', 'width' => 900, 'height' => 600],
    ['file' => 'hero-laser.jpg', 'alt' => 'Laser engraving detail on a custom medal', 'width' => 1200, 'height' => 800],
    ['file' => 'sample-office.jpg', 'alt' => 'Corporate event medal presentation', 'width' => 600, 'height' => 400],
];

$award_samples = [
    ['file' => 'award-trophy.jpg', 'alt' => 'Wooden trophy award sample', 'width' => 900, 'height' => 1350],
    ['file' => 'sample-award-2.jpg', 'alt' => 'Engraved acrylic award sample', 'width' => 600, 'height' => 394],
    ['file' => 'acrylic-color.jpg', 'alt' => 'Layered acrylic award detail', 'width' => 800, 'height' => 533],
    ['file' => 'process-craft.jpg', 'alt' => 'Hand finished trophy in workshop', 'width' => 900, 'height' => 600],
    ['file' => 'material-wood.jpg', 'alt' => 'Premium wood award material sample', 'width' => 800, 'height' => 533],
    ['file' => 'material-mdf.jpg', 'alt' => 'MDF award base material sample', 'width' => 800, 'height' => 533],
    ['file' => 'about-workshop.jpg', 'alt' => 'Awards being prepared in the KORA workshop', 'width' => 1600, 'height' => 1067],
];

$souvenir_samples = [
    ['file' => 'souvenir-tumbler.jpg', 'alt' => 'Branded tumbler souvenir sample', 'width' => 800, 'height' => 1200],
    ['file' => 'sample-pin.jpg', 'alt' => 'Custom pin badge souvenir sample', 'width' => 600, 'height' => 400],
    ['file' => 'souvenir-magnet.jpg', 'alt' => 'Fridge magnet souvenir sample', 'width' => 600, 'height' => 400],
    ['file' => 'sample-gift.jpg', 'alt' => 'Branded corporate giveaway sample', 'width' => 600, 'height' => 450],
    ['file' => 'sample-office.jpg', 'alt' => 'Branded souvenirs for a corporate team', 'width' => 600, 'height' => 400],
    ['file' => 'process-craft.jpg', 'alt' => 'Custom souvenir finishing detail', 'width' => 900, 'height' => 600],
    ['file' => 'about-workshop.jpg', 'alt' => 'Souvenir production in the KORA workshop', 'width' => 1600, 'height' => 1067],
];
?>
<section class="work-samples-hero" aria-labelledby="samples-title">
    <img
        class="work-samples-hero__image reveal"
        src="<?= img('medals-collection.jpg') ?>"
        alt="Collection of custom KORA medals and awards"
        width="1600"
        height="1067"
        loading="eager"
    >
    <div class="work-samples-hero__overlay">
        <h1 id="samples-title" class="work-samples-hero__title reveal">Our Work Samples</h1>
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
