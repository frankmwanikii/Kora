<?php

declare(strict_types=1);

function render_brand_logo(string $variant = 'header'): void
{
    $isFooter = $variant === 'footer';
    $classNames = 'brand-logo brand-logo--' . $variant;

    if (!$isFooter) {
        $classNames .= ' site-logo';
    }
    ?>
    <a class="<?= $classNames ?>" href="<?= SITE_URL ?>" aria-label="<?= SITE_NAME ?> home">
        <span class="brand-logo__wordmark" aria-hidden="true"><?= SITE_NAME ?></span>
        <?php if ($isFooter): ?>
            <span class="brand-logo__taglines">
                <span class="brand-logo__tagline"><?= SITE_TAGLINE ?></span>
                <span class="brand-logo__tagline"><?= SITE_LOCATION ?></span>
            </span>
        <?php endif; ?>
    </a>
    <?php
}
