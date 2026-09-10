<?php

declare(strict_types=1);

/**
 * @param array{
 *     id: string,
 *     tag: string,
 *     title: string,
 *     lead: string,
 *     body: string,
 *     features: array<int, string>,
 *     materials: string,
 *     ideal_for: string,
 *     hero: array{file: string, alt: string, width: int, height: int},
 *     images: array<int, array{file: string, alt: string, width: int, height: int}>,
 *     reverse?: bool
 * } $product
 */
function render_product_detail(array $product): void
{
    $modifier = htmlspecialchars($product['id']);
    $reverseClass = !empty($product['reverse']) ? ' product-detail--reverse' : '';
    ?>
    <article class="product-detail product-detail--<?= $modifier ?><?= $reverseClass ?> reveal" id="<?= $modifier ?>">
        <div class="container">
            <div class="product-detail__grid">
                <div class="product-detail__media">
                    <figure class="product-detail__hero-image">
                        <img
                            src="<?= img($product['hero']['file']) ?>"
                            alt="<?= htmlspecialchars($product['hero']['alt']) ?>"
                            width="<?= (int) $product['hero']['width'] ?>"
                            height="<?= (int) $product['hero']['height'] ?>"
                            loading="lazy"
                        >
                    </figure>
                    <div class="product-detail__gallery">
                        <?php foreach ($product['images'] as $image): ?>
                            <figure class="product-detail__thumb">
                                <img
                                    src="<?= img($image['file']) ?>"
                                    alt="<?= htmlspecialchars($image['alt']) ?>"
                                    width="<?= (int) $image['width'] ?>"
                                    height="<?= (int) $image['height'] ?>"
                                    loading="lazy"
                                >
                            </figure>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="product-detail__content">
                    <p class="product-detail__tag"><?= htmlspecialchars($product['tag']) ?></p>
                    <h2 class="product-detail__title"><?= htmlspecialchars($product['title']) ?></h2>
                    <p class="product-detail__lead lead-italic"><?= htmlspecialchars($product['lead']) ?></p>
                    <p class="product-detail__body text-body"><?= htmlspecialchars($product['body']) ?></p>

                    <ul class="product-detail__features">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li><?= htmlspecialchars($feature) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <dl class="product-detail__specs">
                        <div class="product-detail__spec">
                            <dt>Materials</dt>
                            <dd><?= htmlspecialchars($product['materials']) ?></dd>
                        </div>
                        <div class="product-detail__spec">
                            <dt>Ideal for</dt>
                            <dd><?= htmlspecialchars($product['ideal_for']) ?></dd>
                        </div>
                    </dl>

                    <div class="product-detail__actions">
                        <a class="btn btn--ghost" href="/work-samples.php">View samples</a>
                        <a class="btn btn--solid" href="/request-quote.php">Request a quote</a>
                    </div>
                </div>
            </div>
        </div>
    </article>
    <?php
}

$productsCms = cms_section('products');
$productsDefault = [
    [
        'id' => 'medals',
        'tag' => 'Event recognition',
        'title' => 'Medals',
        'lead' => 'Custom medals that give every participant something tangible to keep long after the finish line.',
        'body' => 'We design and produce medals for marathons, school sports days, corporate wellness challenges, and community events. Each medal can be cut into almost any shape, layered for depth, and finished with engraving, colour fill, ribbon attachment, or presentation packaging.',
        'features' => [
            'Single or multi-layered construction for a premium feel',
            'Custom shapes, logos, dates, and event branding',
            'Ribbon colours and lengths matched to your brief',
            'Options for wood, MDF, plywood, and acrylic',
            'Bulk production for large participant numbers',
        ],
        'materials' => 'Premium MDF, solid wood, plywood, and acrylic',
        'ideal_for' => 'Marathons, fun runs, school sports, corporate challenges, and charity walks',
        'hero' => ['file' => 'medals/hero.webp', 'alt' => 'Custom KORA medals laid out on wood', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'medals/marathon.webp', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
            ['file' => 'medals/bike.webp', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
            ['file' => 'medals/football.webp', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
        ],
    ],
    [
        'id' => 'awards',
        'tag' => 'Winner recognition',
        'title' => 'Awards & Trophies',
        'lead' => 'Physical awards that turn achievement into something worth displaying.',
        'body' => 'From elegant wooden trophies to layered acrylic awards, we build pieces that feel substantial and personal. Awards can be shaped to reflect your brand, event, or institution, with engraving, inset logos, painted accents, and hand-finished details that elevate the presentation moment.',
        'features' => [
            'Standing trophies, desktop awards, and sculpted forms',
            'Engraved text, logos, and sponsor recognition',
            'Layered acrylic, wood, and mixed-material builds',
            'Custom heights, bases, and display profiles',
            'Suitable for ceremonies, galas, and internal recognition',
        ],
        'materials' => 'Solid wood, MDF, plywood, acrylic, and mixed finishes',
        'ideal_for' => 'Corporate awards, sports championships, school prizegivings, and NGO recognition',
        'hero' => ['file' => 'awards/Award_hero.webp', 'alt' => 'Handcrafted business award by KORA', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'awards/Aw5.webp', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
            ['file' => 'awards/Padel Award.webp', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
            ['file' => 'awards/Golf_award.webp', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
        ],
        'reverse' => true,
    ],
    [
        'id' => 'souvenirs',
        'tag' => 'Everyday brand presence',
        'title' => 'Souvenirs & Keepsakes',
        'lead' => 'Branded giveaways that keep your organisation present in everyday life.',
        'body' => 'Beyond awards, we produce souvenirs and keepsakes that extend the reach of your event or brand. From tumblers and fridge magnets to badge pins and custom gift items, each piece is made to feel considered rather than generic — useful, memorable, and aligned with your visual identity.',
        'features' => [
            'Tumblers, magnets, badge pins, and branded gift items',
            'Logo application, colour matching, and custom packaging',
            'Practical items people keep and use after an event',
            'Flexible quantities for conferences, launches, and campaigns',
            'Great for delegates, volunteers, sponsors, and guests',
        ],
        'materials' => 'Mixed materials depending on product — wood accents, acrylic, and branded merchandise bases',
        'ideal_for' => 'Conferences, corporate events, tourism campaigns, and branded giveaways',
        'hero' => ['file' => 'souvenir/souvenir_hero.webp', 'alt' => 'Branded travel souvenir gift set by KORA', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'souvenir/travel.webp', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
            ['file' => 'souvenir/souvenir_hero.webp', 'alt' => 'Open souvenir gift set with engraved tumbler', 'width' => 2752, 'height' => 1536],
            ['file' => 'medals/workshop_hero.webp', 'alt' => 'Souvenir gift set displayed with awards and medals', 'width' => 2752, 'height' => 1536],
        ],
    ],
];

$products = cms_list('products', 'categories', $productsDefault);
if ($products === []) {
    $products = $productsDefault;
}

// Keep first occurrence per category id (guards against corrupted CMS duplicates)
$seenProductIds = [];
$productsDeduped = [];
foreach ($products as $product) {
    if (!is_array($product)) {
        continue;
    }
    $productId = (string) ($product['id'] ?? '');
    if ($productId === '' || isset($seenProductIds[$productId])) {
        continue;
    }
    $seenProductIds[$productId] = true;
    $productsDeduped[] = $product;
}
$products = $productsDeduped !== [] ? $productsDeduped : $productsDefault;

$productsHero = is_array($productsCms['hero'] ?? null) ? $productsCms['hero'] : [];
$productsHeroImage = is_array($productsHero['image'] ?? null) ? $productsHero['image'] : [];
$productsHeroFile = (string) ($productsHeroImage['file'] ?? 'awards/Hero Section.webp');
$productsHeroAlt = (string) ($productsHeroImage['alt'] ?? 'Collection of custom KORA awards and trophies');
$productsHeroTitle = (string) ($productsHero['title'] ?? 'Our Products');

$productsIntro = is_array($productsCms['intro'] ?? null) ? $productsCms['intro'] : [];
$productsIntroTitle = (string) ($productsIntro['title'] ?? 'What we make');
$productsIntroText = (string) ($productsIntro['text'] ?? 'KORA creates recognition products by hand for organisations, NGOs, corporates, schools, and sports teams. Every item starts with your brief — event, brand, quantity, and budget — and is shaped in our Nanyuki workshop using premium materials, precise engraving, and finishes built to last.');
$productsIntroNav = is_array($productsIntro['nav'] ?? null) ? $productsIntro['nav'] : [
    ['label' => 'Medals', 'href' => '#medals'],
    ['label' => 'Awards', 'href' => '#awards'],
    ['label' => 'Souvenirs', 'href' => '#souvenirs'],
];

$productsMaterials = is_array($productsCms['materials_section'] ?? null)
    ? $productsCms['materials_section']
    : (is_array($productsCms['materials_block'] ?? null) ? $productsCms['materials_block'] : []);
$productsMaterialsTitle = (string) ($productsMaterials['title'] ?? 'Materials we work with');
$productsMaterialsText = (string) ($productsMaterials['text'] ?? 'We select materials based on the look, weight, and durability your product needs. Whether you want the warmth of natural wood, the precision of layered acrylic, or the versatility of MDF and plywood, we advise on the best combination for your event or brand.');
$productsMaterialsItems = is_array($productsMaterials['items'] ?? null) ? $productsMaterials['items'] : [
    ['name' => 'Wood', 'image' => 'WOOD.webp', 'alt' => 'Solid wood material sample', 'text' => 'Rich, natural finishes ideal for trophies and premium medals.'],
    ['name' => 'MDF & Plywood', 'image' => 'MDF.webp', 'alt' => 'MDF material sample', 'text' => 'Reliable bases for shaped medals, layered builds, and detailed engraving.'],
    ['name' => 'Acrylic', 'image' => 'ACRYLIC.webp', 'alt' => 'Acrylic material sample', 'text' => 'Clean, modern awards with colour, depth, and sharp branded detail.'],
];

$productsCta = is_array($productsCms['cta'] ?? null) ? $productsCms['cta'] : [];
$productsCtaTitle = (string) ($productsCta['title'] ?? 'Ready to brief your order?');
$productsCtaText = (string) ($productsCta['text'] ?? 'Share your event, quantities, and design ideas — we will guide you from concept to finished pieces.');
$productsCtaPrimaryHref = (string) ($productsCta['primary_href'] ?? '/request-quote.php');
$productsCtaPrimaryLabel = (string) ($productsCta['primary_label'] ?? 'Request a Quotation');
$productsCtaSecondaryHref = (string) ($productsCta['secondary_href'] ?? '/work-samples.php');
$productsCtaSecondaryLabel = (string) ($productsCta['secondary_label'] ?? 'Browse our work');
?>
<section class="products-hero" aria-labelledby="products-hero-title">
    <img
        class="products-hero__image reveal"
        src="<?= img($productsHeroFile) ?>"
        alt="<?= htmlspecialchars($productsHeroAlt) ?>"
        width="<?= (int) ($productsHeroImage['width'] ?? 4558) ?>"
        height="<?= (int) ($productsHeroImage['height'] ?? 3376) ?>"
        loading="eager"
    >
    <div class="products-hero__overlay">
        <div class="products-hero__content reveal">
            <h1 id="products-hero-title" class="products-hero__title"><?= htmlspecialchars($productsHeroTitle) ?></h1>
        </div>
    </div>
</section>

<section class="products-intro section" aria-labelledby="products-intro-title">
    <div class="container">
        <div class="products-intro__inner reveal">
            <h2 id="products-intro-title" class="products-intro__title"><?= htmlspecialchars($productsIntroTitle) ?></h2>
            <p class="products-intro__text text-body"><?= htmlspecialchars($productsIntroText) ?></p>
            <div class="products-intro__nav">
                <?php foreach ($productsIntroNav as $navItem): ?>
                    <?php if (!is_array($navItem) || (string) ($navItem['label'] ?? '') === '') {
                        continue;
                    } ?>
                    <a class="products-intro__link" href="<?= htmlspecialchars((string) ($navItem['href'] ?? '#')) ?>"><?= htmlspecialchars((string) $navItem['label']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="products-catalog" aria-label="Product categories">
    <?php foreach ($products as $product): ?>
        <?php render_product_detail($product); ?>
    <?php endforeach; ?>
</section>

<section class="products-materials section" aria-labelledby="products-materials-title">
    <div class="container">
        <div class="products-materials__inner reveal">
            <div class="products-materials__copy">
                <h2 id="products-materials-title" class="products-materials__title"><?= htmlspecialchars($productsMaterialsTitle) ?></h2>
                <p class="text-body"><?= htmlspecialchars($productsMaterialsText) ?></p>
            </div>
            <div class="products-materials__grid">
                <?php foreach ($productsMaterialsItems as $materialItem): ?>
                    <?php
                    if (!is_array($materialItem)) {
                        continue;
                    }
                    $mName = (string) ($materialItem['name'] ?? '');
                    $mImage = (string) ($materialItem['image'] ?? '');
                    if ($mName === '' || $mImage === '') {
                        continue;
                    }
                    ?>
                    <article class="products-materials__item">
                        <img src="<?= img($mImage) ?>" alt="<?= htmlspecialchars((string) ($materialItem['alt'] ?? $mName)) ?>" width="1200" height="1200" loading="lazy">
                        <h3><?= htmlspecialchars($mName) ?></h3>
                        <p><?= htmlspecialchars((string) ($materialItem['text'] ?? $materialItem['description'] ?? '')) ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="products-cta section" aria-labelledby="products-cta-title">
    <div class="container">
        <div class="products-cta__inner reveal">
            <h2 id="products-cta-title" class="products-cta__title"><?= htmlspecialchars($productsCtaTitle) ?></h2>
            <p class="products-cta__text lead-italic"><?= htmlspecialchars($productsCtaText) ?></p>
            <div class="btn-group">
                <a class="btn btn--solid" href="<?= htmlspecialchars($productsCtaPrimaryHref) ?>"><?= htmlspecialchars($productsCtaPrimaryLabel) ?></a>
                <a class="btn btn--ghost" href="<?= htmlspecialchars($productsCtaSecondaryHref) ?>"><?= htmlspecialchars($productsCtaSecondaryLabel) ?></a>
            </div>
        </div>
    </div>
</section>
<?php
if (function_exists('seo_render_json_ld')) {
    $productListItems = [];
    $position = 1;
    foreach ($products as $product) {
        if (!is_array($product)) {
            continue;
        }
        $pid = (string) ($product['id'] ?? '');
        $ptitle = (string) ($product['title'] ?? '');
        if ($pid === '' || $ptitle === '') {
            continue;
        }
        $heroFile = (string) (($product['hero']['file'] ?? '') ?: '');
        $productListItems[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $ptitle,
            'url' => seo_url('/products') . '#' . rawurlencode($pid),
            'image' => $heroFile !== '' ? seo_image_url($heroFile) : seo_default_image(),
            'description' => (string) ($product['lead'] ?? $product['body'] ?? ''),
        ];
        $position++;
    }

    if ($productListItems !== []) {
        seo_render_json_ld([
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'KORA Products',
            'url' => seo_url('/products'),
            'description' => $productsIntroText,
            'mainEntity' => [
                '@type' => 'ItemList',
                'itemListElement' => $productListItems,
                'numberOfItems' => count($productListItems),
            ],
        ]);
    }
}
?>
