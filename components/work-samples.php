<?php

declare(strict_types=1);

/**
 * @param array<int, array{file?: string, alt?: string, width?: int|string, height?: int|string}> $images
 */
function render_sample_gallery(string $label, array $images): void
{
    ?>
    <div class="sample-gallery-wrap">
        <div class="sample-gallery" tabindex="0" role="region" aria-label="<?= htmlspecialchars($label) ?> sample gallery">
            <?php foreach ($images as $image): ?>
                <?php
                if (!is_array($image)) {
                    continue;
                }
                $file = (string) ($image['file'] ?? '');
                if ($file === '') {
                    continue;
                }
                ?>
                <button
                    type="button"
                    class="sample-gallery__item"
                    aria-label="View larger: <?= htmlspecialchars((string) ($image['alt'] ?? $label)) ?>"
                >
                    <img
                        src="<?= img($file) ?>"
                        alt="<?= htmlspecialchars((string) ($image['alt'] ?? '')) ?>"
                        width="<?= (int) ($image['width'] ?? 1200) ?>"
                        height="<?= (int) ($image['height'] ?? 1200) ?>"
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

$work = cms_section('work_samples');
$work_hero = is_array($work['hero'] ?? null) ? $work['hero'] : [];
$work_hero_image = is_array($work_hero['image'] ?? null) ? $work_hero['image'] : [];
$work_hero_file = (string) ($work_hero_image['file'] ?? 'workshop_hero.webp');
$work_hero_alt = (string) ($work_hero_image['alt'] ?? 'Custom KORA awards, medals, and souvenirs displayed in the workshop');
$work_hero_title = (string) ($work_hero['title'] ?? 'Our Studio Samples');

$galleries = cms_list('work_samples', 'galleries', []);
if ($galleries === []) {
    $galleries = [
        [
            'id' => 'medals',
            'title' => 'Medals',
            'text' => 'Made in premium MDF, solid wood, plywood, or acrylic, single or multi layered, and cut into any shape. Medals give every participant something to keep, building a sense of belonging and making your event more memorable.',
            'reverse' => false,
            'images' => [
                ['file' => 'medals/marathon.webp', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
                ['file' => 'medals/bike.webp', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
                ['file' => 'medals/football.webp', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
            ],
        ],
        [
            'id' => 'awards',
            'title' => 'Awards & Trophies',
            'text' => 'Made in the same range of materials, single or multi layered, and cut into any shape. Awards give recognition a physical form, motivating winners and encouraging others to perform better next time.',
            'reverse' => true,
            'images' => [
                ['file' => 'awards/Award_hero.webp', 'alt' => 'Business award trophy on a wooden base', 'width' => 2752, 'height' => 1536],
                ['file' => 'awards/Aw5.webp', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
                ['file' => 'awards/Golf_award.webp', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
                ['file' => 'awards/Padel Award.webp', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
                ['file' => 'awards/Graduation.webp', 'alt' => 'Custom graduation award', 'width' => 3682, 'height' => 3376],
                ['file' => 'awards/Football.webp', 'alt' => 'Custom football tournament award', 'width' => 3376, 'height' => 5221],
                ['file' => 'awards/teacher.webp', 'alt' => 'Custom teacher appreciation award', 'width' => 3203, 'height' => 3680],
                ['file' => 'awards/retire.webp', 'alt' => 'Custom retirement plaque', 'width' => 3448, 'height' => 3376],
                ['file' => 'awards/WOODEN AWARD.webp', 'alt' => 'Custom layered wooden cycling award', 'width' => 1207, 'height' => 1303],
                ['file' => 'awards/Aw4.webp', 'alt' => 'Multi-layer award sample', 'width' => 3185, 'height' => 3376],
                ['file' => 'awards/appreciate.webp', 'alt' => 'Appreciation award sample', 'width' => 1254, 'height' => 1254],
                ['file' => 'awards/Golf_Award.webp', 'alt' => 'Appreciation award sample', 'width' => 1254, 'height' => 1254],
            ],
        ],
        [
            'id' => 'souvenirs',
            'title' => 'Souvenirs & Keepsakes',
            'text' => 'From fridge magnets and tumblers to badge pins and other branded giveaways. Souvenirs keep your organisation present in people\'s everyday lives, extending your event\'s reach and building lasting goodwill.',
            'reverse' => false,
            'images' => [
                ['file' => 'souvenir/souvenir_hero.webp', 'alt' => 'Branded travel souvenir gift set', 'width' => 2752, 'height' => 1536],
                ['file' => 'souvenir/travel.webp', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
            ],
        ],
    ];
}
?>
<section class="work-samples-hero" aria-labelledby="samples-title">
    <img
        class="work-samples-hero__image reveal"
        src="<?= img($work_hero_file) ?>"
        alt="<?= htmlspecialchars($work_hero_alt) ?>"
        width="<?= (int) ($work_hero_image['width'] ?? 2752) ?>"
        height="<?= (int) ($work_hero_image['height'] ?? 1536) ?>"
        loading="eager"
    >
    <div class="work-samples-hero__overlay">
        <h1 id="samples-title" class="work-samples-hero__title reveal"><?= htmlspecialchars($work_hero_title) ?></h1>
    </div>
</section>

<section class="work-samples" aria-label="Work sample categories">
    <?php foreach ($galleries as $gallery): ?>
        <?php
        if (!is_array($gallery)) {
            continue;
        }
        $galleryId = (string) ($gallery['id'] ?? 'samples');
        $galleryTitle = (string) ($gallery['title'] ?? 'Samples');
        $galleryText = (string) ($gallery['text'] ?? '');
        $reverse = !empty($gallery['reverse']);
        $images = is_array($gallery['images'] ?? null) ? $gallery['images'] : [];
        $blockClass = 'sample-block sample-block--' . preg_replace('/[^a-z0-9\-]/', '', strtolower($galleryId));
        ?>
        <article class="<?= htmlspecialchars($blockClass) ?> reveal">
            <div class="container">
                <div class="sample-block__header<?= $reverse ? ' sample-block__header--reverse' : '' ?>">
                    <h2 class="sample-block__title"><?= htmlspecialchars($galleryTitle) ?></h2>
                    <?php if ($galleryText !== ''): ?>
                        <p class="sample-block__text lead-italic"><?= htmlspecialchars($galleryText) ?></p>
                    <?php endif; ?>
                </div>
                <?php render_sample_gallery($galleryTitle, $images); ?>
            </div>
        </article>
    <?php endforeach; ?>
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
