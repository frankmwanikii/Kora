<?php

declare(strict_types=1);

$about = cms_section('about');
$about_title = (string) ($about['title'] ?? 'Where laser meets craft');
$about_bg = (string) ($about['background_image'] ?? 'about image.webp');
$about_paragraphs = cms_list('about', 'paragraphs', [
    'KORA is a custom awards studio based in Nanyuki, Laikipia, specialising in the design and creation of premium awards, medals, plaques, and souvenirs.',
    'We blend advanced laser-cutting technology with meticulous hand-assembly and finishing to craft every piece.',
    'Committed to supporting our community, we partner closely with local timber providers to source our high-quality solid wood, plywood, and MDF, pairing them with premium acrylics.',
    'KORA works alongside organisations, NGOs, corporates, and sports teams to honour employees, participants, sponsors, and partners at moments worth remembering.',
]);
?>
<section
    class="about section"
    aria-labelledby="about-title"
    style="--about-bg: url('<?= img($about_bg) ?>')"
>
    <div class="container about__content reveal">
        <h2 id="about-title" class="about__title"><?= htmlspecialchars($about_title) ?></h2>
        <div class="about__copy">
            <?php foreach ($about_paragraphs as $paragraph): ?>
                <?php if (!is_string($paragraph) || trim($paragraph) === '') {
                    continue;
                } ?>
                <p><?= htmlspecialchars($paragraph) ?></p>
            <?php endforeach; ?>
        </div>
    </div>
</section>
