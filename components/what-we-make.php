<section class="what-we-make section" id="products" aria-labelledby="products-title">
    <div class="container">
        <div class="what-we-make__showcase">
            <header class="what-we-make__header reveal">
                <h2 id="products-title" class="what-we-make__title">What We Make</h2>
            </header>

            <article class="what-we-make__card what-we-make__card--awards reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('award-trophy.jpg') ?>"
                        alt="Wooden award trophy crafted in the KORA studio"
                        width="900"
                        height="1350"
                        loading="lazy"
                    >
                </div>
                <h3 class="what-we-make__label">Awards &amp; Trophies</h3>
            </article>

            <article class="what-we-make__card what-we-make__card--medals reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('medals-collection.jpg') ?>"
                        alt="Collection of medals and race awards"
                        width="800"
                        height="533"
                        loading="lazy"
                    >
                </div>
                <h3 class="what-we-make__label">Medals</h3>
            </article>

            <article class="what-we-make__card what-we-make__card--souvenirs reveal">
                <div class="what-we-make__pill">
                    <img
                        src="<?= img('souvenir-tumbler.jpg') ?>"
                        alt="Branded insulated tumbler souvenir"
                        width="800"
                        height="1200"
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
                        src="<?= img('process-craft.jpg') ?>"
                        alt="Craftsman working with wood in the KORA workshop"
                        width="900"
                        height="600"
                        loading="lazy"
                    >
                    <img
                        class="process-stack__front"
                        src="<?= img('process-wood.jpg') ?>"
                        alt="Wood shavings from hand shaping in the workshop"
                        width="900"
                        height="600"
                        loading="lazy"
                    >
                </div>
            </div>

            <div class="what-we-make__materials-wrap reveal">
                <div class="materials" aria-label="Materials">
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('material-wood.jpg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">Wood</span>
                    </div>
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('material-mdf.jpg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">MDF</span>
                    </div>
                    <div class="material">
                        <div class="material__thumb">
                            <img src="<?= img('acrylic-color.jpg') ?>" alt="" width="108" height="108" loading="lazy">
                        </div>
                        <span class="material__name">Acrylic</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
