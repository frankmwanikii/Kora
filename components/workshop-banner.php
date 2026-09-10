<?php

declare(strict_types=1);

$workshop = cms_section('workshop');
$workshopImage = is_array($workshop['image'] ?? null) ? $workshop['image'] : [];
$workshopFile = (string) ($workshopImage['file'] ?? 'medals/workshop_hero.jpeg');
$workshopAlt = (string) ($workshopImage['alt'] ?? 'Custom KORA awards, medals, and souvenirs displayed in the workshop');
?>
<section class="workshop-banner" aria-label="KORA workshop">
    <img
        class="workshop-banner__image reveal"
        src="<?= img($workshopFile) ?>"
        alt="<?= htmlspecialchars($workshopAlt) ?>"
        width="<?= (int) ($workshopImage['width'] ?? 2752) ?>"
        height="<?= (int) ($workshopImage['height'] ?? 1536) ?>"
        loading="lazy"
    >
</section>
