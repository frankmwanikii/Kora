<?php

declare(strict_types=1);

$wwm = cms_section('what_we_make');
$wwm_title = (string) ($wwm['title'] ?? 'What We Make');
$wwm_cards = cms_list('what_we_make', 'cards', [
    [
        'id' => 'awards',
        'label' => 'Awards & Trophies',
        'image' => ['file' => 'awards/WOODEN AWARD.webp', 'alt' => 'Custom layered wooden cycling award by KORA', 'width' => 1207, 'height' => 1303],
    ],
    [
        'id' => 'medals',
        'label' => 'Medals',
        'image' => ['file' => 'medals/marathon.webp', 'alt' => 'Custom layered wooden marathon medal', 'width' => 3376, 'height' => 4199],
    ],
    [
        'id' => 'souvenirs',
        'label' => 'Souvenirs & Keepsakes',
        'image' => ['file' => 'souvenir/souvenir_hero.webp', 'alt' => 'Branded travel souvenir gift set with tumbler and keepsakes', 'width' => 2752, 'height' => 1536],
    ],
]);
$wwm_actions = cms_list('what_we_make', 'actions', [
    ['label' => 'All Products', 'href' => '/products.php'],
    ['label' => 'Studio Samples', 'href' => '/work-samples.php'],
    ['label' => 'Request a Quote', 'href' => '/request-quote.php'],
]);
$wwm_process = cms_list('what_we_make', 'process_images', [
    ['file' => 'laser_1.webp', 'alt' => 'Close-up of the laser engraving a custom design into wood', 'width' => 6000, 'height' => 3376, 'role' => 'back'],
    ['file' => 'awards/Aw1.webp', 'alt' => 'Close-up of laser engraving a custom design onto wood', 'width' => 3376, 'height' => 3593, 'role' => 'front'],
]);
$wwm_materials = cms_list('what_we_make', 'materials', [
    ['name' => 'Wood', 'image' => 'WOOD.webp'],
    ['name' => 'MDF', 'image' => 'MDF.webp'],
    ['name' => 'Acrylic', 'image' => 'ACRYLIC.webp'],
]);

$processBack = null;
$processFront = null;
foreach ($wwm_process as $imgItem) {
    if (!is_array($imgItem)) {
        continue;
    }
    $role = (string) ($imgItem['role'] ?? '');
    if ($role === 'back' || $processBack === null) {
        $processBack = $imgItem;
    }
    if ($role === 'front') {
        $processFront = $imgItem;
    }
}
if ($processFront === null && isset($wwm_process[1]) && is_array($wwm_process[1])) {
    $processFront = $wwm_process[1];
}
?>
<section class="what-we-make section" id="products" aria-labelledby="products-title">
    <div class="container">
        <div class="what-we-make__showcase">
            <header class="what-we-make__header reveal">
                <h2 id="products-title" class="what-we-make__title"><?= htmlspecialchars($wwm_title) ?></h2>
            </header>

            <?php foreach ($wwm_cards as $card): ?>
                <?php
                if (!is_array($card)) {
                    continue;
                }
                $cardId = (string) ($card['id'] ?? 'item');
                $cardLabel = (string) ($card['label'] ?? '');
                $cardImage = is_array($card['image'] ?? null) ? $card['image'] : [];
                $cardFile = (string) ($cardImage['file'] ?? '');
                if ($cardFile === '') {
                    continue;
                }
                ?>
                <article class="what-we-make__card what-we-make__card--<?= htmlspecialchars(preg_replace('/[^a-z0-9\-]/', '', strtolower($cardId)) ?: 'item') ?> reveal">
                    <div class="what-we-make__pill">
                        <img
                            src="<?= img($cardFile) ?>"
                            alt="<?= htmlspecialchars((string) ($cardImage['alt'] ?? $cardLabel)) ?>"
                            width="<?= (int) ($cardImage['width'] ?? 1200) ?>"
                            height="<?= (int) ($cardImage['height'] ?? 1200) ?>"
                            loading="lazy"
                        >
                    </div>
                    <h3 class="what-we-make__label"><?= htmlspecialchars($cardLabel) ?></h3>
                </article>
            <?php endforeach; ?>

            <div class="what-we-make__actions reveal">
                <?php foreach ($wwm_actions as $action): ?>
                    <?php
                    if (!is_array($action)) {
                        continue;
                    }
                    $label = (string) ($action['label'] ?? '');
                    $href = (string) ($action['href'] ?? '#');
                    if ($label === '') {
                        continue;
                    }
                    ?>
                    <a class="btn btn--light" href="<?= htmlspecialchars($href) ?>"><?= htmlspecialchars($label) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="what-we-make__lower">
            <div class="what-we-make__process reveal">
                <div class="process-stack">
                    <?php if (is_array($processBack)): ?>
                        <img
                            class="process-stack__back"
                            src="<?= img((string) ($processBack['file'] ?? 'laser_1.webp')) ?>"
                            alt="<?= htmlspecialchars((string) ($processBack['alt'] ?? '')) ?>"
                            width="<?= (int) ($processBack['width'] ?? 6000) ?>"
                            height="<?= (int) ($processBack['height'] ?? 3376) ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>
                    <?php if (is_array($processFront)): ?>
                        <img
                            class="process-stack__front"
                            src="<?= img((string) ($processFront['file'] ?? 'awards/Aw1.webp')) ?>"
                            alt="<?= htmlspecialchars((string) ($processFront['alt'] ?? '')) ?>"
                            width="<?= (int) ($processFront['width'] ?? 3376) ?>"
                            height="<?= (int) ($processFront['height'] ?? 3593) ?>"
                            loading="lazy"
                        >
                    <?php endif; ?>
                </div>
            </div>

            <div class="what-we-make__materials-wrap reveal">
                <div class="materials" aria-label="Materials">
                    <?php foreach ($wwm_materials as $material): ?>
                        <?php
                        if (!is_array($material)) {
                            continue;
                        }
                        $name = (string) ($material['name'] ?? '');
                        $image = (string) ($material['image'] ?? '');
                        if ($name === '' || $image === '') {
                            continue;
                        }
                        ?>
                        <div class="material">
                            <div class="material__thumb">
                                <img src="<?= img($image) ?>" alt="" width="1200" height="1200" loading="lazy">
                            </div>
                            <span class="material__name"><?= htmlspecialchars($name) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
