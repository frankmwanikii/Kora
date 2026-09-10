<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';
require_once __DIR__ . '/includes/editor.php';

$slug = trim((string) ($_GET['slug'] ?? ''));

if ($slug === '') {
    flash('error', 'Section slug is required.');
    redirect('/pages.php');
}

$catalogSlugs = array_column(kora_sections_catalog(), 'slug');
if (!in_array($slug, $catalogSlugs, true) || $slug === 'settings') {
    flash('error', 'Unknown section.');
    redirect('/pages.php');
}

$section = kora_load_section($pdo, $slug);
$pageTitle = (string) ($section['title'] ?? 'Edit section');
$pageSubtitle = 'Content editor';
$activeNav = 'pages';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        flash('error', 'Invalid session. Please try again.');
        redirect('/section-edit.php?slug=' . rawurlencode($slug));
    }

    $defaults = kora_section_defaults($slug)['content'];
    $posted = $_POST['content'] ?? null;
    $content = kora_parse_editor_post(is_array($posted) ? $posted : null, $defaults);
    $title = trim((string) ($_POST['section_title'] ?? $pageTitle));

    if ($title === '') {
        $title = $pageTitle;
    }

    kora_save_section($pdo, $slug, $title, $content);
    flash('success', 'Section saved and site content exported.');
    redirect('/section-edit.php?slug=' . rawurlencode($slug));
}

$content = is_array($section['content'] ?? null) ? $section['content'] : [];

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Section editor</p>
            <h2 class="pages-hub__title"><?= e($pageTitle) ?></h2>
            <p class="pages-hub__lead">Edit content for <code><?= e($slug) ?></code>. Image paths are relative to <code>assets/images/</code>.</p>
        </div>
        <a href="<?= e(admin_base_path()) ?>/pages.php" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to pages
        </a>
    </header>

    <form method="post" id="section-form">
        <?= csrf_field() ?>
        <input type="hidden" name="section_title" value="<?= e($pageTitle) ?>">

        <div class="editor-grid">
            <div class="editor-grid__main">
                <?php kora_render_editor_fields($content, 'content'); ?>
            </div>
            <aside class="editor-grid__aside">
                <section class="panel">
                    <div class="panel__head"><h2 class="panel__title">Tips</h2></div>
                    <div class="panel__body">
                        <p class="muted" style="font-size:.875rem;margin:0 0 .75rem">Upload new images in the <a href="<?= e(admin_base_path()) ?>/gallery-upload.php">media library</a>, then paste the path into image fields.</p>
                        <p class="muted" style="font-size:.875rem;margin:0">Saving updates <code>data/cms/site.json</code> on the public site.</p>
                    </div>
                </section>
            </aside>
        </div>

        <div class="save-bar">
            <a href="<?= e(admin_base_path()) ?>/pages.php" class="btn btn-ghost">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> Save changes
            </button>
        </div>
    </form>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
