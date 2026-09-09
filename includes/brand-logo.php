<?php

declare(strict_types=1);

function render_brand_logo(string $variant = 'header'): void
{
    $isFooter = $variant === 'footer';
    $classNames = 'brand-logo brand-logo--' . $variant;

    if (!$isFooter) {
        $classNames .= ' site-logo';
    }

    $logoSrc = asset('assets/images/logos/kora_logo1.png');
    ?>
    <a class="<?= $classNames ?>" href="<?= SITE_URL ?>" aria-label="<?= SITE_NAME ?> home">
        <img
            class="brand-logo__image"
            src="<?= htmlspecialchars($logoSrc) ?>"
            alt=""
            width="500"
            height="500"
            decoding="async"
            <?php if (!$isFooter): ?>
                fetchpriority="high"
            <?php else: ?>
                loading="lazy"
            <?php endif; ?>
        >
        <?php if ($isFooter): ?>
            <span class="brand-logo__taglines">
                <span class="brand-logo__tagline"><?= SITE_TAGLINE ?></span>
                <span class="brand-logo__tagline"><?= SITE_LOCATION ?></span>
            </span>
        <?php endif; ?>
    </a>
    <?php
}
