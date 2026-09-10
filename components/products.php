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

$products = [
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
        'hero' => ['file' => 'medals/hero.jpeg', 'alt' => 'Custom KORA medals laid out on wood', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'medals/marathon.jpg', 'alt' => 'Custom Steps of Hope marathon medal', 'width' => 3376, 'height' => 4199],
            ['file' => 'medals/bike.jpg', 'alt' => 'Custom bike challenge medal', 'width' => 2926, 'height' => 3455],
            ['file' => 'medals/football.jpg', 'alt' => 'Custom football tournament medal', 'width' => 3740, 'height' => 2538],
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
        'hero' => ['file' => 'awards/Award_hero.jpeg', 'alt' => 'Handcrafted business award by KORA', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'awards/Aw5.jpg', 'alt' => 'Mountain bike award with acrylic detail', 'width' => 2796, 'height' => 3123],
            ['file' => 'awards/Padel Award.jpg', 'alt' => 'Nairobi Padel Open champion award', 'width' => 3614, 'height' => 3016],
            ['file' => 'awards/Golf_award.jpg', 'alt' => 'Custom golf award', 'width' => 3376, 'height' => 4667],
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
        'hero' => ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Branded travel souvenir gift set by KORA', 'width' => 2752, 'height' => 1536],
        'images' => [
            ['file' => 'souvenir/travel.png', 'alt' => 'Wanderlust travel souvenir box with tumbler and keepsakes', 'width' => 1402, 'height' => 1122],
            ['file' => 'souvenir/souvenir_hero.jpeg', 'alt' => 'Open souvenir gift set with engraved tumbler', 'width' => 2752, 'height' => 1536],
            ['file' => 'medals/workshop_hero.jpeg', 'alt' => 'Souvenir gift set displayed with awards and medals', 'width' => 2752, 'height' => 1536],
        ],
    ],
];
?>
<section class="products-hero" aria-labelledby="products-hero-title">
    <img
        class="products-hero__image reveal"
        src="<?= img('awards/Hero Section.jpg') ?>"
        alt="Collection of custom KORA awards and trophies"
        width="4558"
        height="3376"
        loading="eager"
    >
    <div class="products-hero__overlay">
        <div class="products-hero__content reveal">
            <h1 id="products-hero-title" class="products-hero__title">Our Products</h1>
        </div>
    </div>
</section>

<section class="products-intro section" aria-labelledby="products-intro-title">
    <div class="container">
        <div class="products-intro__inner reveal">
            <h2 id="products-intro-title" class="products-intro__title">What we make</h2>
            <p class="products-intro__text text-body">
                KORA creates recognition products by hand for organisations, NGOs, corporates, schools, and sports teams. Every item starts with your brief — event, brand, quantity, and budget — and is shaped in our Nanyuki workshop using premium materials, precise engraving, and finishes built to last.
            </p>
            <div class="products-intro__nav">
                <a class="products-intro__link" href="#medals">Medals</a>
                <a class="products-intro__link" href="#awards">Awards</a>
                <a class="products-intro__link" href="#souvenirs">Souvenirs</a>
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
                <h2 id="products-materials-title" class="products-materials__title">Materials we work with</h2>
                <p class="text-body">
                    We select materials based on the look, weight, and durability your product needs. Whether you want the warmth of natural wood, the precision of layered acrylic, or the versatility of MDF and plywood, we advise on the best combination for your event or brand.
                </p>
            </div>
            <div class="products-materials__grid">
                <article class="products-materials__item">
                    <img src="<?= img('WOOD.jpg') ?>" alt="Solid wood material sample" width="6061" height="4329" loading="lazy">
                    <h3>Wood</h3>
                    <p>Rich, natural finishes ideal for trophies and premium medals.</p>
                </article>
                <article class="products-materials__item">
                    <img src="<?= img('MDF.jpg') ?>" alt="MDF material sample" width="2000" height="2000" loading="lazy">
                    <h3>MDF &amp; Plywood</h3>
                    <p>Reliable bases for shaped medals, layered builds, and detailed engraving.</p>
                </article>
                <article class="products-materials__item">
                    <img src="<?= img('ACRYLIC.png') ?>" alt="Acrylic material sample" width="1080" height="1080" loading="lazy">
                    <h3>Acrylic</h3>
                    <p>Clean, modern awards with colour, depth, and sharp branded detail.</p>
                </article>
            </div>
        </div>
    </div>
</section>

<section class="products-cta section" aria-labelledby="products-cta-title">
    <div class="container">
        <div class="products-cta__inner reveal">
            <h2 id="products-cta-title" class="products-cta__title">Ready to brief your order?</h2>
            <p class="products-cta__text lead-italic">Share your event, quantities, and design ideas — we will guide you from concept to finished pieces.</p>
            <div class="btn-group">
                <a class="btn btn--solid" href="/request-quote.php">Request a Quotation</a>
                <a class="btn btn--ghost" href="/work-samples.php">Browse our work</a>
            </div>
        </div>
    </div>
</section>
