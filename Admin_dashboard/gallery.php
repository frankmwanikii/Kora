<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/init.php';

$pageTitle = 'Gallery';
$pageSubtitle = 'Media library';
$activeNav = 'gallery';

$folders = [
    '' => 'All folders',
    'awards' => 'Awards',
    'medals' => 'Medals',
    'souvenir' => 'Souvenir',
    'logos' => 'Logos',
    'uploads' => 'Uploads',
    'root' => 'Root',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'delete' || $action === 'bulk_delete') {
        if (!verify_csrf($_POST['csrf_token'] ?? '')) {
            flash('error', 'Invalid session. Please try again.');
            redirect('/gallery.php');
        }

        $paths = [];
        if ($action === 'bulk_delete') {
            $raw = $_POST['paths'] ?? [];
            if (is_array($raw)) {
                foreach ($raw as $path) {
                    $path = trim((string) $path);
                    if ($path !== '') {
                        $paths[] = $path;
                    }
                }
            }
            $paths = array_values(array_unique($paths));
        } else {
            $path = trim((string) ($_POST['path'] ?? ''));
            if ($path !== '') {
                $paths[] = $path;
            }
        }

        if ($paths === []) {
            flash('error', $action === 'bulk_delete' ? 'Select at least one item to delete.' : 'Missing media path.');
            redirect('/gallery.php');
        }

        $ok = 0;
        $fail = 0;
        foreach ($paths as $path) {
            $result = kora_delete_media($pdo, $path);
            if (!empty($result['ok'])) {
                $ok++;
            } else {
                $fail++;
            }
        }

        if ($ok > 0 && $fail === 0) {
            flash('success', count($paths) === 1 ? 'Media deleted.' : $ok . ' items deleted.');
        } elseif ($ok > 0) {
            flash('error', "Deleted {$ok} item(s), but {$fail} could not be deleted.");
        } else {
            flash('error', 'Could not delete the selected item(s). Only uploads can be removed.');
        }

        $back = trim((string) ($_POST['return'] ?? ''));
        if ($back !== '' && str_starts_with($back, 'gallery.php')) {
            redirect('/' . ltrim($back, '/'));
        }
        redirect('/gallery.php');
    }
}

$q = trim((string) ($_GET['q'] ?? ''));
$view = strtolower(trim((string) ($_GET['view'] ?? 'grid')));
if ($view !== 'list') {
    $view = 'grid';
}

$folderKey = trim((string) ($_GET['folder'] ?? ''));
$folderFilter = $folderKey === 'root' ? '' : $folderKey;
if ($folderKey === 'root') {
    $all = kora_gallery_list($q !== '' ? $q : null, null);
    $all = array_values(array_filter($all, static fn(array $item): bool => ($item['folder'] ?? '') === ''));
} else {
    $all = kora_gallery_list($q !== '' ? $q : null, $folderFilter !== '' ? $folderFilter : null);
}

$perPage = 24;
$page = max(1, (int) ($_GET['paged'] ?? 1));
$total = count($all);
$totalPages = max(1, (int) ceil($total / $perPage));
if ($page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;
$files = array_slice($all, $offset, $perPage);
$from = $total === 0 ? 0 : $offset + 1;
$to = min($total, $offset + count($files));

$buildUrl = static function (array $overrides = []) use ($q, $view, $page, $folderKey): string {
    $nextQ = array_key_exists('q', $overrides) ? trim((string) $overrides['q']) : $q;
    $nextView = array_key_exists('view', $overrides) ? (string) $overrides['view'] : $view;
    if ($nextView !== 'list') {
        $nextView = 'grid';
    }
    $nextPage = array_key_exists('paged', $overrides) ? (int) $overrides['paged'] : $page;
    $nextFolder = array_key_exists('folder', $overrides) ? (string) $overrides['folder'] : $folderKey;

    $params = [];
    if ($nextQ !== '') {
        $params['q'] = $nextQ;
    }
    if ($nextFolder !== '') {
        $params['folder'] = $nextFolder;
    }
    if ($nextView === 'list') {
        $params['view'] = 'list';
    }
    if ($nextPage > 1) {
        $params['paged'] = $nextPage;
    }

    $qs = http_build_query($params);

    return 'gallery.php' . ($qs !== '' ? '?' . $qs : '');
};

$returnTo = $buildUrl([]);

require __DIR__ . '/includes/layout.php';
?>

<div class="pages-hub gallery-page">
    <header class="pages-hub__intro pages-hub__intro--compact">
        <div>
            <p class="pages-hub__eyebrow">Media library</p>
            <h2 class="pages-hub__title">Gallery</h2>
            <p class="pages-hub__lead">Browse website images, copy paths into CMS fields, or delete uploaded files.</p>
        </div>
        <a href="<?= e(admin_base_path()) ?>/gallery-upload.php" class="btn btn-primary">
            <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> Upload
        </a>
    </header>

    <div class="toolbar gallery-toolbar">
        <form method="get" class="gallery-search toolbar-search">
            <?php if ($view === 'list'): ?>
                <input type="hidden" name="view" value="list">
            <?php endif; ?>
            <?php if ($folderKey !== ''): ?>
                <input type="hidden" name="folder" value="<?= e($folderKey) ?>">
            <?php endif; ?>
            <input class="form-control" type="search" name="q" value="<?= e($q) ?>" placeholder="Search media…" aria-label="Search media">
            <button class="btn btn-ghost" type="submit">Search</button>
            <?php if ($q !== ''): ?>
                <a class="btn btn-ghost" href="<?= e($buildUrl(['q' => '', 'paged' => 1])) ?>">Clear</a>
            <?php endif; ?>
        </form>

        <div class="gallery-toolbar__right toolbar__side">
            <form method="get" class="toolbar__side">
                <?php if ($q !== ''): ?>
                    <input type="hidden" name="q" value="<?= e($q) ?>">
                <?php endif; ?>
                <?php if ($view === 'list'): ?>
                    <input type="hidden" name="view" value="list">
                <?php endif; ?>
                <label class="form-label" for="folder-filter" style="margin:0;font-size:.8125rem">Folder</label>
                <select class="form-control" id="folder-filter" name="folder" onchange="this.form.submit()" style="min-width:140px">
                    <?php foreach ($folders as $value => $label): ?>
                        <option value="<?= e($value) ?>"<?= $folderKey === $value ? ' selected' : '' ?>><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>

            <span class="media-count">
                <?php if ($total === 0): ?>
                    0 items
                <?php else: ?>
                    <?= (int) $from ?>–<?= (int) $to ?> of <?= (int) $total ?> item<?= $total === 1 ? '' : 's' ?>
                <?php endif; ?>
            </span>

            <div class="media-view-switch" role="group" aria-label="View mode">
                <a class="media-view-switch__btn<?= $view === 'list' ? ' is-active' : '' ?>" href="<?= e($buildUrl(['view' => 'list', 'paged' => 1])) ?>" title="List view" aria-label="List view">
                    <i class="fa-solid fa-list" aria-hidden="true"></i>
                </a>
                <a class="media-view-switch__btn<?= $view === 'grid' ? ' is-active' : '' ?>" href="<?= e($buildUrl(['view' => 'grid', 'paged' => 1])) ?>" title="Grid view" aria-label="Grid view">
                    <i class="fa-solid fa-border-all" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>

    <section class="panel">
        <div class="panel__head gallery-library-head">
            <h2 class="panel__title">Library</h2>
            <?php if ($totalPages > 1): ?>
                <nav class="media-pager media-pager--compact" aria-label="Library pagination">
                    <?php if ($page > 1): ?>
                        <a class="btn btn-ghost btn-sm" href="<?= e($buildUrl(['paged' => $page - 1])) ?>"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Prev</a>
                    <?php endif; ?>
                    <span class="media-pager__status">Page <?= (int) $page ?> of <?= (int) $totalPages ?></span>
                    <?php if ($page < $totalPages): ?>
                        <a class="btn btn-ghost btn-sm" href="<?= e($buildUrl(['paged' => $page + 1])) ?>">Next <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
        <div class="panel__body">
            <?php if ($files === []): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-images" aria-hidden="true"></i>
                    <p><?= $q !== '' ? 'No media match your search.' : 'No media found in this folder.' ?></p>
                    <a href="<?= e(admin_base_path()) ?>/gallery-upload.php" class="btn btn-primary" style="margin-top:.85rem">
                        <i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> Upload media
                    </a>
                </div>
            <?php else: ?>
                <form id="media-bulk-form" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="action" value="bulk_delete">
                    <input type="hidden" name="return" value="<?= e($returnTo) ?>">

                    <div class="bulk-toolbar bulk-toolbar--panel" data-bulk-toolbar data-bulk-form="media-bulk-form" data-bulk-list="#media-bulk-list" data-bulk-row-click="true">
                        <div class="bulk-toolbar__row" data-bulk-toolbar-default>
                            <label class="bulk-check bulk-check--master" title="Select all">
                                <input type="checkbox" data-bulk-select-all aria-label="Select all on this page">
                            </label>
                            <span class="bulk-toolbar__hint">Select uploads to delete in bulk</span>
                        </div>
                        <div class="bulk-toolbar__row bulk-toolbar__row--bulk" data-bulk-toolbar-bulk hidden>
                            <label class="bulk-check bulk-check--master" title="Select all">
                                <input type="checkbox" data-bulk-select-all aria-label="Select all on this page">
                            </label>
                            <span class="bulk-toolbar__count" data-bulk-selected-count>0 selected</span>
                            <button type="button" class="btn btn-danger btn-sm" data-bulk-delete data-bulk-confirm="Delete {n} selected uploads?">
                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Delete selected
                            </button>
                        </div>
                    </div>

                    <div id="media-bulk-list">
                        <?php if ($view === 'list'): ?>
                            <div class="table-wrap">
                                <table class="data-table media-list-table">
                                    <thead>
                                        <tr>
                                            <th scope="col"><span class="visually-hidden">Select</span></th>
                                            <th scope="col">File</th>
                                            <th scope="col">Folder</th>
                                            <th scope="col">Size</th>
                                            <th scope="col" class="cell-actions">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $file): ?>
                                            <?php
                                            $path = (string) ($file['path'] ?? '');
                                            $url = (string) ($file['url'] ?? kora_media_url($path));
                                            $canDelete = !empty($file['can_delete']);
                                            $confirmMsg = 'Delete this upload permanently?';
                                            ?>
                                            <tr class="media-list-row" data-bulk-row>
                                                <td>
                                                    <?php if ($canDelete): ?>
                                                        <label class="bulk-check">
                                                            <input type="checkbox" name="paths[]" value="<?= e($path) ?>" data-bulk-check aria-label="Select <?= e((string) ($file['filename'] ?? $path)) ?>">
                                                        </label>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="media-list-file">
                                                        <div class="media-list-file__thumb">
                                                            <div class="media-preview">
                                                                <img src="<?= e($url) ?>" alt="" loading="lazy">
                                                            </div>
                                                        </div>
                                                        <div class="media-list-file__meta">
                                                            <strong title="<?= e((string) ($file['filename'] ?? '')) ?>"><?= e((string) ($file['filename'] ?? basename($path))) ?></strong>
                                                            <span class="muted"><?= e($path) ?></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td><?= e((string) ($file['folder'] ?? '—')) ?></td>
                                                <td><?= e(format_bytes((int) ($file['size'] ?? 0))) ?></td>
                                                <td class="cell-actions">
                                                    <button type="button" class="btn btn-ghost btn-sm" data-copy="<?= e($path) ?>" title="Copy path">
                                                        <i class="fa-regular fa-copy" aria-hidden="true"></i>
                                                    </button>
                                                    <a class="btn btn-ghost btn-sm" href="<?= e($url) ?>" target="_blank" rel="noopener" title="Open">
                                                        <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                                                    </a>
                                                    <?php if ($canDelete): ?>
                                                        <form method="post" style="display:inline">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="action" value="delete">
                                                            <input type="hidden" name="path" value="<?= e($path) ?>">
                                                            <input type="hidden" name="return" value="<?= e($returnTo) ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" data-confirm="<?= e($confirmMsg) ?>">
                                                                <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="media-grid">
                                <?php foreach ($files as $file): ?>
                                    <?php
                                    $path = (string) ($file['path'] ?? '');
                                    $url = (string) ($file['url'] ?? kora_media_url($path));
                                    $canDelete = !empty($file['can_delete']);
                                    $confirmMsg = 'Delete this upload permanently?';
                                    ?>
                                    <div class="media-item" data-bulk-row>
                                        <?php if ($canDelete): ?>
                                            <label class="media-item__check bulk-check">
                                                <input type="checkbox" name="paths[]" value="<?= e($path) ?>" data-bulk-check aria-label="Select <?= e((string) ($file['filename'] ?? $path)) ?>">
                                            </label>
                                        <?php endif; ?>
                                        <div class="media-preview">
                                            <img src="<?= e($url) ?>" alt="<?= e((string) ($file['filename'] ?? '')) ?>" loading="lazy">
                                        </div>
                                        <div class="media-item__overlay">
                                            <div class="media-item__overlay-actions">
                                                <a class="btn btn-primary btn-sm" href="<?= e($url) ?>" target="_blank" rel="noopener">
                                                    <i class="fa-solid fa-expand" aria-hidden="true"></i> View
                                                </a>
                                                <button type="button" class="btn btn-ghost btn-sm" data-copy="<?= e($path) ?>">
                                                    <i class="fa-regular fa-copy" aria-hidden="true"></i> Copy path
                                                </button>
                                                <?php if ($canDelete): ?>
                                                    <form method="post" class="media-delete-form">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="path" value="<?= e($path) ?>">
                                                        <input type="hidden" name="return" value="<?= e($returnTo) ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm" data-confirm="<?= e($confirmMsg) ?>">
                                                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="media-item__meta">
                                            <strong title="<?= e((string) ($file['filename'] ?? $path)) ?>"><?= e((string) ($file['filename'] ?? basename($path))) ?></strong>
                                            <span><?= e(format_bytes((int) ($file['size'] ?? 0))) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if ($totalPages > 1): ?>
                    <nav class="media-pager" aria-label="Library pages" style="margin-top:1.25rem">
                        <?php if ($page > 1): ?>
                            <a class="btn btn-ghost btn-sm" href="<?= e($buildUrl(['paged' => $page - 1])) ?>"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Previous</a>
                        <?php endif; ?>
                        <span class="media-pager__status">Page <?= (int) $page ?> of <?= (int) $totalPages ?></span>
                        <?php if ($page < $totalPages): ?>
                            <a class="btn btn-ghost btn-sm" href="<?= e($buildUrl(['paged' => $page + 1])) ?>">Next <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
