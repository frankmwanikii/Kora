<?php

declare(strict_types=1);

function kora_is_list_array(array $array): bool
{
    if ($array === []) {
        return true;
    }

    return array_keys($array) === range(0, count($array) - 1);
}

function kora_field_name(string $prefix, string|int ...$parts): string
{
    $name = $prefix;

    foreach ($parts as $part) {
        $name .= '[' . $part . ']';
    }

    return $name;
}

function kora_humanize_key(string $key): string
{
    return ucwords(str_replace('_', ' ', $key));
}

function kora_is_image_scalar_key(string $key): bool
{
    $key = strtolower($key);

    if (in_array($key, ['image', 'background_image', 'file'], true)) {
        return true;
    }

    return str_ends_with($key, '_image') || str_ends_with($key, '_file');
}

function kora_is_long_text_key(string $key, mixed $value): bool
{
    $key = strtolower($key);

    if (is_string($value) && strlen($value) > 120) {
        return true;
    }

    foreach (['paragraph', 'body', 'lead', 'description', 'answer', 'text', 'blurb', 'note', 'summary'] as $needle) {
        if (str_contains($key, $needle) || $key === 'a' || $key === 'q') {
            return true;
        }
    }

    return false;
}

function kora_is_image_object(array $value): bool
{
    return isset($value['file']) && is_string($value['file']);
}

function kora_render_form_label(string $name, string $key): void
{
    echo '<label class="form-label" for="' . e($name) . '">' . e(kora_humanize_key($key)) . '</label>';
}

function kora_render_scalar_field(string $prefix, string $key, mixed $value): void
{
    $name = kora_field_name($prefix, $key);
    $stringValue = is_scalar($value) ? (string) $value : '';

    if (in_array($key, ['width', 'height'], true)) {
        kora_render_form_label($name, $key);
        echo '<input class="form-control" type="number" id="' . e($name) . '" name="' . e($name) . '" value="' . e($stringValue) . '" min="0" step="1">';
        return;
    }

    if ($key === 'reverse' || $key === 'external') {
        echo '<label class="form-check" style="display:flex;align-items:center;gap:.5rem;margin:.35rem 0">';
        echo '<input type="checkbox" name="' . e($name) . '" value="1"' . ($value ? ' checked' : '') . '>';
        echo '<span>' . e(kora_humanize_key($key)) . '</span>';
        echo '</label>';
        return;
    }

    if (kora_is_image_scalar_key($key)) {
        kora_render_image_field($prefix, $key, $stringValue);
        return;
    }

    kora_render_form_label($name, $key);

    if (kora_is_long_text_key($key, $stringValue)) {
        echo '<textarea class="form-control" id="' . e($name) . '" name="' . e($name) . '" rows="4">' . e($stringValue) . '</textarea>';
        return;
    }

    echo '<input class="form-control" type="text" id="' . e($name) . '" name="' . e($name) . '" value="' . e($stringValue) . '">';
}

function kora_render_image_field(string $prefix, string $key, string $value): void
{
    $name = kora_field_name($prefix, $key);
    $fieldId = 'img-' . substr(sha1($name), 0, 12);

    echo '<div class="image-field" data-image-field data-image-field-id="' . e($fieldId) . '">';
    kora_render_form_label($name, $key);

    echo '<div class="image-field__preview-wrap">';
    echo '<img class="image-field__preview" data-image-preview alt="" hidden>';
    echo '<span class="image-field__empty" data-image-empty><i class="fa-regular fa-image" aria-hidden="true"></i> No image selected</span>';
    echo '</div>';

    echo '<div class="image-field__actions">';
    echo '<button type="button" class="btn btn-secondary btn-sm" data-image-upload>';
    echo '<i class="fa-solid fa-cloud-arrow-up" aria-hidden="true"></i> Upload';
    echo '</button>';
    echo '<button type="button" class="btn btn-secondary btn-sm" data-gallery-pick data-gallery-target="#' . e($fieldId) . '-input">';
    echo '<i class="fa-solid fa-images" aria-hidden="true"></i> Pick from gallery';
    echo '</button>';
    echo '<button type="button" class="btn btn-ghost btn-sm" data-image-clear title="Clear image">';
    echo '<i class="fa-solid fa-xmark" aria-hidden="true"></i> Clear';
    echo '</button>';
    echo '</div>';

    echo '<input type="file" accept="image/*" data-image-file hidden aria-hidden="true" tabindex="-1">';

    echo '<label class="form-label image-field__path-label" for="' . e($fieldId) . '-input">Image path or URL</label>';
    echo '<input class="form-control" type="text" id="' . e($fieldId) . '-input" name="' . e($name) . '" value="' . e($value) . '" data-image-input data-site-base="/assets/images" placeholder="Paste path or full image URL…">';
    echo '<p class="muted image-field__hint">Paste a URL, upload a file, or pick from the gallery. Paths are relative to <code>assets/images/</code>.</p>';
    echo '<p class="image-field__status muted" data-image-status hidden></p>';
    echo '</div>';
}

function kora_render_image_object(string $prefix, string $key, array $value): void
{
    $groupPrefix = kora_field_name($prefix, $key);
    $file = isset($value['file']) && is_scalar($value['file']) ? (string) $value['file'] : '';

    echo '<div class="image-object">';
    echo '<div class="image-object__head"><h3 class="image-object__title">' . e(kora_humanize_key($key)) . '</h3></div>';
    echo '<div class="image-object__body">';

    kora_render_image_field($groupPrefix, 'file', $file);

    echo '<div class="image-object__meta">';
    foreach ($value as $subKey => $subValue) {
        if ((string) $subKey === 'file') {
            continue;
        }

        echo '<div class="form-group" style="margin:0">';
        kora_render_scalar_field($groupPrefix, (string) $subKey, $subValue);
        echo '</div>';
    }
    echo '</div>';

    echo '</div></div>';
}

function kora_render_string_list(string $prefix, string $key, array $items): void
{
    $label = kora_humanize_key($key);
    $listName = kora_field_name($prefix, $key);

    echo '<div class="repeat-list" data-repeat-list data-repeat-min="0" data-repeat-max="99" data-repeat-prefix="' . e($listName) . '">';
    echo '<div class="repeat-list__head"><strong>' . e($label) . '</strong></div>';
    echo '<div class="repeat-list__items" data-repeat-items>';

    if ($items === []) {
        $items = [''];
    }

    foreach ($items as $index => $item) {
        $itemName = kora_field_name($prefix, $key, (int) $index);
        echo '<div class="repeat-list__item" data-repeat-item>';
        echo '<div class="repeat-list__head">';
        echo '<strong data-repeat-label>Item ' . ((int) $index + 1) . '</strong>';
        echo '<button type="button" class="btn btn-ghost btn-sm" data-repeat-remove><i class="fa-solid fa-trash-can" aria-hidden="true"></i> Remove</button>';
        echo '</div>';
        echo '<div class="form-group" style="margin:0">';
        if (kora_is_long_text_key($key, (string) $item)) {
            echo '<textarea class="form-control" name="' . e($itemName) . '" rows="3">' . e((string) $item) . '</textarea>';
        } else {
            echo '<input class="form-control" type="text" name="' . e($itemName) . '" value="' . e((string) $item) . '">';
        }
        echo '</div></div>';
    }

    echo '</div>';
    echo '<button type="button" class="btn btn-secondary repeat-list__add" data-repeat-add><i class="fa-solid fa-plus" aria-hidden="true"></i> Add ' . e(rtrim($label, 's')) . '</button>';
    echo '</div>';
}

function kora_render_object_list(string $prefix, string $key, array $items, array $template): void
{
    $label = kora_humanize_key($key);
    $listName = kora_field_name($prefix, $key);

    echo '<div class="repeat-list" data-repeat-list data-repeat-min="0" data-repeat-max="99" data-repeat-prefix="' . e($listName) . '">';
    echo '<div class="repeat-list__head"><strong>' . e($label) . '</strong></div>';
    echo '<div class="repeat-list__items" data-repeat-items>';

    if ($items === []) {
        $items = [$template];
    }

    foreach ($items as $index => $item) {
        if (!is_array($item)) {
            continue;
        }

        $itemPrefix = kora_field_name($prefix, $key, (int) $index);
        echo '<div class="repeat-list__item" data-repeat-item>';
        echo '<div class="repeat-list__head">';
        echo '<strong data-repeat-label>Item ' . ((int) $index + 1) . '</strong>';
        echo '<button type="button" class="btn btn-ghost btn-sm" data-repeat-remove><i class="fa-solid fa-trash-can" aria-hidden="true"></i> Remove</button>';
        echo '</div>';
        echo '<div style="display:grid;gap:.85rem">';

        foreach ($item as $subKey => $subValue) {
            echo '<div class="form-group" style="margin:0">';

            if (is_array($subValue) && kora_is_image_object($subValue)) {
                kora_render_image_object($itemPrefix, (string) $subKey, $subValue);
            } elseif (is_array($subValue) && kora_is_list_array($subValue) && ($subValue === [] || is_string($subValue[0] ?? null))) {
                kora_render_string_list($itemPrefix, (string) $subKey, $subValue);
            } elseif (is_array($subValue) && kora_is_list_array($subValue)) {
                $subTemplate = $subValue[0] ?? ($template[(string) $subKey][0] ?? []);
                if (is_array($subTemplate)) {
                    kora_render_object_list($itemPrefix, (string) $subKey, $subValue, $subTemplate);
                }
            } elseif (is_array($subValue)) {
                echo '<div class="panel" style="margin:0">';
                echo '<div class="panel__head"><h4 class="panel__title">' . e(kora_humanize_key((string) $subKey)) . '</h4></div>';
                echo '<div class="panel__body" style="display:grid;gap:.75rem">';
                foreach ($subValue as $nestedKey => $nestedValue) {
                    echo '<div class="form-group" style="margin:0">';
                    kora_render_scalar_field(kora_field_name($itemPrefix, (string) $subKey), (string) $nestedKey, $nestedValue);
                    echo '</div>';
                }
                echo '</div></div>';
            } else {
                kora_render_scalar_field($itemPrefix, (string) $subKey, $subValue);
            }

            echo '</div>';
        }

        echo '</div></div>';
    }

    echo '</div>';
    echo '<button type="button" class="btn btn-secondary repeat-list__add" data-repeat-add><i class="fa-solid fa-plus" aria-hidden="true"></i> Add item</button>';
    echo '</div>';
}

function kora_render_assoc_group(string $prefix, string $key, array $value): void
{
    echo '<section class="panel">';
    echo '<div class="panel__head"><h2 class="panel__title">' . e(kora_humanize_key($key)) . '</h2></div>';
    echo '<div class="panel__body" style="display:grid;gap:.85rem">';
    $groupPrefix = kora_field_name($prefix, $key);

    foreach ($value as $subKey => $subValue) {
        echo '<div class="form-group" style="margin:0">';

        if (is_array($subValue) && kora_is_image_object($subValue)) {
            kora_render_image_object($groupPrefix, (string) $subKey, $subValue);
        } elseif (is_array($subValue) && kora_is_list_array($subValue)) {
            if ($subValue === [] || is_string($subValue[0] ?? null)) {
                kora_render_string_list($groupPrefix, (string) $subKey, $subValue);
            } else {
                $template = $subValue[0] ?? [];
                kora_render_object_list($groupPrefix, (string) $subKey, $subValue, is_array($template) ? $template : []);
            }
        } elseif (is_array($subValue)) {
            kora_render_assoc_group($groupPrefix, (string) $subKey, $subValue);
        } else {
            kora_render_scalar_field($groupPrefix, (string) $subKey, $subValue);
        }

        echo '</div>';
    }

    echo '</div></section>';
}

function kora_render_editor_fields(array $content, string $prefix = 'content'): void
{
    foreach ($content as $key => $value) {
        echo '<div class="form-group">';

        if (is_array($value) && kora_is_image_object($value)) {
            kora_render_image_object($prefix, (string) $key, $value);
        } elseif (is_array($value) && kora_is_list_array($value)) {
            if ($value === [] || is_string($value[0] ?? null)) {
                kora_render_string_list($prefix, (string) $key, $value);
            } else {
                $template = $value[0] ?? [];
                kora_render_object_list($prefix, (string) $key, $value, is_array($template) ? $template : []);
            }
        } elseif (is_array($value)) {
            kora_render_assoc_group($prefix, (string) $key, $value);
        } else {
            kora_render_scalar_field($prefix, (string) $key, $value);
        }

        echo '</div>';
    }
}

function kora_coerce_scalar(string $key, mixed $value, mixed $default): mixed
{
    if (in_array($key, ['width', 'height'], true)) {
        return max(0, (int) $value);
    }

    if ($key === 'reverse' || $key === 'external') {
        return $value === '1' || $value === 1 || $value === true || $value === 'on';
    }

    if (is_scalar($value)) {
        return trim((string) $value);
    }

    return $default;
}

function kora_merge_content(array $posted, array $template): array
{
    $result = [];
    $keys = array_unique(array_merge(array_keys($template), array_keys($posted)));

    foreach ($keys as $key) {
        $defaultVal = $template[$key] ?? null;
        $hasPosted = array_key_exists($key, $posted);
        $postedVal = $hasPosted ? $posted[$key] : null;

        if (!$hasPosted) {
            if (in_array($key, ['reverse', 'external'], true)) {
                $result[$key] = false;
            } else {
                $result[$key] = $defaultVal;
            }
            continue;
        }

        if (!is_array($defaultVal)) {
            $result[$key] = kora_coerce_scalar((string) $key, $postedVal, $defaultVal);
            continue;
        }

        if (!is_array($postedVal)) {
            $result[$key] = $defaultVal;
            continue;
        }

        if (kora_is_list_array($defaultVal)) {
            if ($defaultVal === [] || is_string($defaultVal[0] ?? null)) {
                $items = [];
                foreach ($postedVal as $item) {
                    if (!is_scalar($item)) {
                        continue;
                    }
                    $trimmed = trim((string) $item);
                    if ($trimmed !== '') {
                        $items[] = $trimmed;
                    }
                }
                $result[$key] = $items;
                continue;
            }

            $itemTemplate = is_array($defaultVal[0] ?? null) ? $defaultVal[0] : [];
            $items = [];
            foreach ($postedVal as $item) {
                if (!is_array($item)) {
                    continue;
                }
                $items[] = kora_merge_content($item, $itemTemplate);
            }
            $result[$key] = $items;
            continue;
        }

        $result[$key] = kora_merge_content($postedVal, $defaultVal);
    }

    return $result;
}

function kora_parse_editor_post(?array $posted, array $defaults): array
{
    if (!is_array($posted)) {
        return $defaults;
    }

    return kora_merge_content($posted, $defaults);
}
