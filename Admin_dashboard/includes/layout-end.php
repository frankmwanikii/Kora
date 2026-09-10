        </main>

        <footer class="admin-footer">
            <span>KORA Admin Dashboard</span>
            <span class="admin-footer-copy">© <?= (int) date('Y') ?> KORA Laser Craft</span>
        </footer>
    </div>

    <div class="kora-confirm" id="kora-confirm" hidden aria-hidden="true">
        <div class="kora-confirm__backdrop" data-kora-confirm-cancel></div>
        <div class="kora-confirm__dialog" role="alertdialog" aria-modal="true" aria-labelledby="kora-confirm-title" aria-describedby="kora-confirm-message">
            <div class="kora-confirm__icon" aria-hidden="true">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 class="kora-confirm__title" id="kora-confirm-title">Please confirm</h2>
            <p class="kora-confirm__message" id="kora-confirm-message"></p>
            <div class="kora-confirm__actions">
                <button type="button" class="btn btn-ghost kora-confirm__cancel" data-kora-confirm-cancel>Cancel</button>
                <button type="button" class="btn btn-primary kora-confirm__ok" data-kora-confirm-ok>OK</button>
            </div>
        </div>
    </div>

    <?php
    $adminJsPath = __DIR__ . '/../assets/js/admin.js';
    $adminJsVersion = is_file($adminJsPath) ? (int) filemtime($adminJsPath) : 1;
    ?>
    <script src="<?= e(admin_base_path()) ?>/assets/js/admin.js?v=<?= $adminJsVersion ?>" defer></script>
    <?php if (!empty($extraScripts)): ?>
        <?php if (is_array($extraScripts)): ?>
            <?php foreach ($extraScripts as $scriptSrc): ?>
                <?php
                $relative = ltrim((string) $scriptSrc, '/');
                $scriptPath = __DIR__ . '/../' . $relative;
                $scriptVersion = is_file($scriptPath) ? (int) filemtime($scriptPath) : 1;
                ?>
                <script src="<?= e(admin_base_path()) ?>/<?= e($relative) ?>?v=<?= $scriptVersion ?>" defer></script>
            <?php endforeach; ?>
        <?php else: ?>
            <?= $extraScripts ?>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
