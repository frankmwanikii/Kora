<section class="what-we-make section" id="products" aria-labelledby="products-title">
    <div class="container">
        <div class="what-we-make__showcase">
            <header class="what-we-make__header reveal">
                <h2 id="products-title" class="what-we-make__title">What We Make</h2>
            </header>

            <article class="what-we-make__card what-we-make__card--awards reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('awards/Aw5.jpg') ?>"
                        alt="Custom mountain bike award crafted in the KORA studio"
                        width="2796"
                        height="3123"
                        loading="lazy"
                    >
                </div>
                <h3 class="what-we-make__label">Awards &amp; Trophies</h3>
            </article>

            <article class="what-we-make__card what-we-make__card--medals reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('medals/marathon.jpg') ?>"
                        alt="Custom layered wooden marathon medal"
                        width="3376"
                        height="4199"
                        loading="lazy"
                    >
                </div>
                <h3 class="what-we-make__label">Medals</h3>
            </article>

            <article class="what-we-make__card what-we-make__card--souvenirs reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('souvenir/souvenir_hero.jpeg') ?>"
                        alt="Branded travel souvenir gift set with tumbler and keepsakes"
                        width="2752"
                        height="1536"
                        loading="lazy"
                    >
                </div>
                <h3 class="what-we-make__label">Souvenirs &amp; Keepsakes</h3>
            </article>

            <div class="what-we-make__actions reveal">
                <a class="btn btn--light" href="/products.php">All Products</a>
                <a class="btn btn--light" href="/work-samples.php">Studio Samples</a>
                <a class="btn btn--light" href="/request-quote.php">Request a Quote</a>
            </div>
        </div>

        <div class="what-we-make__lower">
            <div class="what-we-make__process reveal">
                <div class="process-stack">
                    <img
                        class="process-stack__back"
                        src="<?= img('finish_hero.jpeg') ?>"
                        alt="Laser engraver finishing a custom piece in the KORA workshop"
                        width="2752"
                        height="1536"
                        loading="lazy"
                    >
                    <img
                        class="process-stack__front"
                        src="<?= img('awards/Aw1.jpg') ?>"
                        alt="Close-up of laser engraving a custom design onto wood"
                        width="3376"
                        height="3593"
                        loading="lazy"
                    >
                </div>
            </div>

            <div class="what-we-make__materials-wrap reveal">
                <div class="materials" aria-label="Materials">
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('wood_material.jpeg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">Wood</span>
                    </div>
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('mdf_material.jpeg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">MDF</span>
                    </div>
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('acrylic_material.jpeg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">Acrylic</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
