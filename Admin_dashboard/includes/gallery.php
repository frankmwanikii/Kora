<?php

declare(strict_types=1);

function kora_images_root(): string
{
    return site_root() . '/assets/images';
}

function kora_allowed_image_extensions(): array
{
    return ['jpg', 'jpeg', 'png', 'webp', 'gif'];
}

function kora_is_allowed_image_extension(string $ext): bool
{
    return in_array(strtolower($ext), kora_allowed_image_extensions(), true);
}

function kora_media_url(string $relPath): string
{
    $parts = explode('/', ltrim(str_replace('\\', '/', $relPath), '/'));
    $encoded = implode('/', array_map('rawurlencode', $parts));

    return '/assets/images/' . $encoded;
}

function kora_normalize_media_path(string $path): string
{
    $path = str_replace('\\', '/', $path);
    $path = ltrim($path, '/');

    if (str_contains($path, '..')) {
        throw new InvalidArgumentException('Invalid media path.');
    }

    return $path;
}

function kora_gallery_list(?string $q = null, ?string $folder = null): array
{
    $items = [];
    $seen = [];
    $root = kora_images_root();
    $allowed = kora_allowed_image_extensions();
    $query = $q !== null ? strtolower(trim($q)) : '';
    $folderFilter = $folder !== null ? trim(str_replace('\\', '/', $folder), '/') : '';

    if (is_dir($root)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $fileInfo) {
            if (!$fileInfo->isFile()) {
                continue;
            }

            $ext = strtolower($fileInfo->getExtension());

            if (!in_array($ext, $allowed, true)) {
                continue;
            }

            $fullPath = $fileInfo->getPathname();
            $relPath = ltrim(str_replace($root . DIRECTORY_SEPARATOR, '', $fullPath), '/\\');
            $relPath = str_replace('\\', '/', $relPath);
            $itemFolder = dirname($relPath);
            $itemFolder = $itemFolder === '.' ? '' : $itemFolder;

            if ($folderFilter !== '' && $itemFolder !== $folderFilter && !str_starts_with($itemFolder, $folderFilter . '/')) {
                continue;
            }

            $filename = basename($relPath);

            if ($query !== '' && !str_contains(strtolower($filename), $query) && !str_contains(strtolower($relPath), $query)) {
                continue;
            }

            $seen[$relPath] = true;
            $items[] = [
                'path' => $relPath,
                'filename' => $filename,
                'folder' => $itemFolder,
                'url' => kora_media_url($relPath),
                'size' => (int) $fileInfo->getSize(),
                'mtime' => (int) $fileInfo->getMTime(),
                'mime' => kora_guess_mime_type($ext),
                'can_delete' => str_starts_with($relPath, 'uploads/'),
            ];
        }
    }

    try {
        $pdo = Database::connect();
        $stmt = $pdo->query('SELECT path, filename, folder, file_size, mime_type, created_at FROM media_library ORDER BY created_at DESC');

        while ($row = $stmt->fetch()) {
            $relPath = kora_normalize_media_path((string) $row['path']);

            if (isset($seen[$relPath])) {
                continue;
            }

            if ($folderFilter !== '' && (string) $row['folder'] !== $folderFilter) {
                continue;
            }

            if ($query !== '' && !str_contains(strtolower((string) $row['filename']), $query) && !str_contains(strtolower($relPath), $query)) {
                continue;
            }

            $fullPath = $root . '/' . $relPath;
            $mtime = is_file($fullPath)
                ? (int) filemtime($fullPath)
                : (strtotime((string) $row['created_at']) ?: time());

            $items[] = [
                'path' => $relPath,
                'filename' => (string) $row['filename'],
                'folder' => (string) ($row['folder'] ?? ''),
                'url' => kora_media_url($relPath),
                'size' => is_file($fullPath) ? (int) filesize($fullPath) : (int) $row['file_size'],
                'mtime' => $mtime,
                'mime' => (string) $row['mime_type'],
                'can_delete' => str_starts_with($relPath, 'uploads/'),
            ];
        }
    } catch (Throwable) {
        // Database may not be ready during setup.
    }

    usort($items, static fn(array $a, array $b): int => strcmp($a['path'], $b['path']));

    return $items;
}

function kora_guess_mime_type(string $ext): string
{
    return match (strtolower($ext)) {
        'jpg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
        default => 'application/octet-stream',
    };
}

function kora_safe_upload_filename(string $originalName): string
{
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $base = pathinfo($originalName, PATHINFO_FILENAME);
    $base = preg_replace('/[^a-zA-Z0-9_-]+/', '-', $base) ?? 'upload';
    $base = trim($base, '-');

    if ($base === '') {
        $base = 'upload';
    }

    if (!kora_is_allowed_image_extension($ext)) {
        throw new InvalidArgumentException('Unsupported image type.');
    }

    return $base . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
}

function kora_store_upload(PDO $pdo, array $file, ?string $folder = null): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Upload failed.'];
    }

    $tmpName = (string) ($file['tmp_name'] ?? '');
    $originalName = (string) ($file['name'] ?? 'upload.jpg');

    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
        return ['ok' => false, 'error' => 'Invalid upload.'];
    }

    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!kora_is_allowed_image_extension($ext)) {
        return ['ok' => false, 'error' => 'Only JPG, PNG, WebP, and GIF images are allowed.'];
    }

    $year = (new DateTimeImmutable('now', new DateTimeZone('Africa/Nairobi')))->format('Y');
    $folder = trim(str_replace('\\', '/', (string) ($folder ?? '')), '/');

    if ($folder !== '' && (str_contains($folder, '..') || str_starts_with($folder, '/'))) {
        return ['ok' => false, 'error' => 'Invalid upload folder.'];
    }

    $subdir = 'uploads/' . $year . ($folder !== '' ? '/' . $folder : '');
    $targetDir = kora_images_root() . '/' . $subdir;

    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
        return ['ok' => false, 'error' => 'Unable to create upload directory.'];
    }

    $filename = kora_safe_upload_filename($originalName);
    $targetPath = $targetDir . '/' . $filename;
    $relPath = $subdir . '/' . $filename;
    $mime = (string) ($file['type'] ?? kora_guess_mime_type($ext));

    if (!move_uploaded_file($tmpName, $targetPath)) {
        return ['ok' => false, 'error' => 'Unable to save uploaded file.'];
    }

    if (in_array($ext, ['jpg', 'jpeg', 'png'], true) && function_exists('imagewebp')) {
        $webpPath = preg_replace('/\.[^.]+$/', '.webp', $targetPath);

        if ($webpPath !== null && kora_convert_to_webp($targetPath, $webpPath, $ext)) {
            unlink($targetPath);
            $targetPath = $webpPath;
            $filename = basename($webpPath);
            $relPath = $subdir . '/' . $filename;
            $mime = 'image/webp';
        }
    }

    $size = (int) filesize($targetPath);

    $stmt = $pdo->prepare(
        'INSERT INTO media_library (filename, original_name, mime_type, file_size, path, folder, created_at)
         VALUES (:filename, :original_name, :mime_type, :file_size, :path, :folder, :created_at)'
    );
    $stmt->execute([
        'filename' => $filename,
        'original_name' => $originalName,
        'mime_type' => $mime,
        'file_size' => $size,
        'path' => $relPath,
        'folder' => $folder !== '' ? $folder : $year,
        'created_at' => now(),
    ]);

    return [
        'ok' => true,
        'path' => $relPath,
        'url' => kora_media_url($relPath),
    ];
}

function kora_convert_to_webp(string $source, string $destination, string $ext): bool
{
    $image = match ($ext) {
        'jpg', 'jpeg' => @imagecreatefromjpeg($source),
        'png' => @imagecreatefrompng($source),
        default => false,
    };

    if ($image === false) {
        return false;
    }

    if ($ext === 'png') {
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
    }

    $saved = imagewebp($image, $destination, 85);
    imagedestroy($image);

    return $saved;
}

function kora_delete_media(PDO $pdo, string $path): array
{
    try {
        $relPath = kora_normalize_media_path($path);
    } catch (InvalidArgumentException $e) {
        return ['ok' => false, 'error' => $e->getMessage()];
    }

    if (!str_starts_with($relPath, 'uploads/')) {
        return ['ok' => false, 'error' => 'Only uploaded media can be deleted.'];
    }

    $root = realpath(kora_images_root());
    $fullPath = realpath(kora_images_root() . '/' . $relPath);

    if ($root === false || $fullPath === false || !str_starts_with($fullPath, $root . DIRECTORY_SEPARATOR)) {
        return ['ok' => false, 'error' => 'File not found.'];
    }

    $stmt = $pdo->prepare('SELECT id FROM media_library WHERE path = :path LIMIT 1');
    $stmt->execute(['path' => $relPath]);
    $row = $stmt->fetch();

    if (!$row && !is_file($fullPath)) {
        return ['ok' => false, 'error' => 'File not found.'];
    }

    if (is_file($fullPath) && !unlink($fullPath)) {
        return ['ok' => false, 'error' => 'Unable to delete file.'];
    }

    $delete = $pdo->prepare('DELETE FROM media_library WHERE path = :path');
    $delete->execute(['path' => $relPath]);

    return ['ok' => true];
}
