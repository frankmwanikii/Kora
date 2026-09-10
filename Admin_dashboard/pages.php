<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Pages';
$pageSubtitle = 'Website content sections';
$activeNav = 'pages';

$previewMap = [
    'header' => '/',
    'hero' => '/',
    'about' => '/#about-title',
    'what_we_make' => '/#products',
    'how_to_order' => '/#how-it-works',
    'workshop' => '/',
    'faq' => '/#faqs',
    'contact' => '/#contact',
    'products' => '/products',
    'work_samples' => '/work-samples',
    'how_it_works' => '/how-it-works',
    'request_quote' => '/request-quote',
    'contact_us' => '/contact-us',
    'footer' => '/#footer-newsletter-title',
    'privacy' => '/privacy.php',
];

$groupOrder = ['Homepage', 'Pages', 'General', 'Other'];
$grouped = [];
foreach (kora_sections_catalog() as $section) {
    $slug = (string) ($section['slug'] ?? '');
    if ($slug === 'settings') {
        continue;
    }
    $group = (string) ($section['group'] ?? 'Other');
    $grouped[$group][] = $section;
}

uksort($grouped, static function (string $a, string $b) use ($groupOrder): int {
    $ai = array_search($a, $groupOrder, true);
    $bi = array_search($b, $groupOrder, true);
    $ai = $ai === false ? 99 : $ai;
    $bi = $bi === false ? 99 : $bi;

    return $ai <=> $bi;
});

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Website CMS</p>
            <h2 class="pages-hub__title">Edit website content</h2>
            <p class="pages-hub__lead">
                Click <strong>Edit</strong> on any section below. Saving updates the live site immediately.
                Homepage blocks are listed first — hero, about, products strip, order steps, workshop image, FAQs, and the contact form.
            </p>
        </div>
        <a class="btn btn-secondary" href="/" target="_blank" rel="noopener">
            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i> Open homepage
        </a>
    </header>

    <?php foreach ($grouped as $groupName => $sections): ?>
        <section class="panel">
            <div class="panel__head">
                <h2 class="panel__title"><?= e($groupName) ?></h2>
            </div>
            <div class="panel__body" style="padding:0">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th scope="col">Section</th>
                                <th scope="col">Description</th>
                                <th scope="col" class="cell-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sections as $section): ?>
                                <?php
                                $slug = (string) $section['slug'];
                                $editHref = admin_base_path() . '/section-edit.php?slug=' . rawurlencode($slug);
                                $previewHref = $previewMap[$slug] ?? '/';
                                ?>
                                <tr>
                                    <td class="cell-strong"><?= e((string) $section['title']) ?></td>
                                    <td class="cell-muted"><?= e((string) $section['description']) ?></td>
                                    <td class="cell-actions">
                                        <a class="btn btn-primary btn-sm" href="<?= e($editHref) ?>">
                                            <i class="fa-solid fa-pen" aria-hidden="true"></i> Edit
                                        </a>
                                        <a class="btn btn-ghost btn-sm" href="<?= e($previewHref) ?>" target="_blank" rel="noopener" title="Preview live">
                                            <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                                            <span class="visually-hidden">Preview live</span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    <?php endforeach; ?>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
