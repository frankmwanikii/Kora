<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Upload media';
$pageSubtitle = 'Add images to the library';
$activeNav = 'gallery';
$extraScripts = ['assets/js/upload.js'];

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub gallery-page gallery-upload-page">
    <div class="toolbar page-editor-toolbar">
        <a href="<?= e(admin_base_path()) ?>/gallery.php" class="btn btn-ghost">
            <i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Back to library
        </a>
    </div>

    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Media library</p>
            <h2 class="pages-hub__title">Upload</h2>
            <p class="pages-hub__lead">Add images to the shared library, then copy paths into CMS fields.</p>
        </div>
    </header>

    <div class="gallery-upload-layout" style="display:grid;gap:1.25rem;grid-template-columns:repeat(auto-fit,minmax(280px,1fr))">
        <section class="panel">
            <div class="panel__head"><h2 class="panel__title">Upload images</h2></div>
            <div class="panel__body">
                <?= csrf_field() ?>
                <div class="dropzone media-dropzone" data-upload data-upload-multiple data-upload-target="#last-upload">
                    <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i>
                    <strong>Drop images here or click to upload</strong>
                    <span data-upload-hint>JPG, PNG, WebP, or GIF — multiple files supported</span>
                    <input type="file" accept="image/*" multiple hidden>
                </div>

                <div class="form-group" style="margin-top:1rem">
                    <label class="form-label" for="upload-queue">Uploaded files</label>
                    <ul class="gallery-upload-queue" id="upload-queue" hidden style="list-style:none;padding:0;margin:0;display:grid;gap:.5rem"></ul>
                    <p class="muted gallery-upload-empty" id="upload-empty" style="font-size:.875rem;margin:.35rem 0 0">No files uploaded yet — drop or pick images above.</p>
                </div>

                <div class="form-group" style="margin-top:.75rem">
                    <label class="form-label" for="last-upload">Last uploaded path</label>
                    <div class="gallery-last-upload" style="display:flex;gap:.5rem">
                        <input class="form-control" id="last-upload" readonly placeholder="Upload to see path…">
                        <button type="button" class="btn btn-ghost btn-sm" id="copy-last-upload" title="Copy path">
                            <i class="fa-regular fa-copy" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>

                <div class="gallery-upload-actions" style="display:flex;gap:.65rem;margin-top:1.25rem;flex-wrap:wrap">
                    <a href="<?= e(admin_base_path()) ?>/gallery.php" class="btn btn-ghost">Cancel</a>
                    <a href="<?= e(admin_base_path()) ?>/gallery.php" class="btn btn-primary">Done — view library</a>
                </div>
            </div>
        </section>

        <section class="panel">
            <div class="panel__head"><h2 class="panel__title">Tips</h2></div>
            <div class="panel__body">
                <div class="media-tip" style="display:flex;gap:.65rem;align-items:flex-start;margin-bottom:.85rem">
                    <i class="fa-solid fa-image" aria-hidden="true" style="color:var(--copper);margin-top:.15rem"></i>
                    <span class="muted" style="font-size:.875rem">Images are stored under <code>assets/images/uploads/</code> and converted to WebP when possible.</span>
                </div>
                <div class="media-tip" style="display:flex;gap:.65rem;align-items:flex-start;margin-bottom:.85rem">
                    <i class="fa-solid fa-link" aria-hidden="true" style="color:var(--copper);margin-top:.15rem"></i>
                    <span class="muted" style="font-size:.875rem">Copy a path, then paste it into any image field in the section editor.</span>
                </div>
                <div class="media-tip" style="display:flex;gap:.65rem;align-items:flex-start">
                    <i class="fa-solid fa-crop-simple" aria-hidden="true" style="color:var(--copper);margin-top:.15rem"></i>
                    <span class="muted" style="font-size:.875rem">Hero images look best around 1600×900 pixels.</span>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
(function () {
    function copyText(text, btn) {
        if (!text) return;
        var done = function () {
            if (!btn) return;
            var original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i>';
            setTimeout(function () { btn.innerHTML = original; }, 1200);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(done).catch(function () {
                window.prompt('Copy path', text);
            });
        } else {
            window.prompt('Copy path', text);
        }
    }

    var last = document.getElementById('last-upload');
    var copyLast = document.getElementById('copy-last-upload');
    var queueEl = document.getElementById('upload-queue');
    var emptyEl = document.getElementById('upload-empty');

    if (copyLast && last) {
        copyLast.addEventListener('click', function () {
            copyText(last.value || '', copyLast);
        });
    }

    document.addEventListener('kora:upload-success', function () {
        if (queueEl) queueEl.hidden = false;
        if (emptyEl) emptyEl.hidden = true;
    });
})();
</script>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
