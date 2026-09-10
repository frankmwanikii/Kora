<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Pages';
$pageSubtitle = 'Website content sections';
$activeNav = 'pages';

$grouped = [];
foreach (kora_sections_catalog() as $section) {
    $group = (string) ($section['group'] ?? 'Other');
    $grouped[$group][] = $section;
}

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Website CMS</p>
            <h2 class="pages-hub__title">Pages &amp; sections</h2>
            <p class="pages-hub__lead">Edit homepage blocks, product pages, and global content. Changes export to the live site JSON automatically.</p>
        </div>
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
                                $editHref = $slug === 'settings'
                                    ? admin_base_path() . '/settings.php'
                                    : admin_base_path() . '/section-edit.php?slug=' . rawurlencode($slug);
                                ?>
                                <tr>
                                    <td class="cell-strong"><?= e((string) $section['title']) ?></td>
                                    <td class="cell-muted"><?= e((string) $section['description']) ?></td>
                                    <td class="cell-actions">
                                        <a class="btn btn-secondary btn-sm" href="<?= e($editHref) ?>">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i> Edit
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
