/**
 * KORA gallery picker — choose an existing image into an image field.
 * Buttons: [data-gallery-pick][data-gallery-target="#input-id"]
 */
(function () {
  'use strict';

  var modal = null;
  var activeTarget = null;
  var searchTimer = null;
  var listUrl = 'api/media-list.php';

  function csrfToken() {
    var el = document.querySelector('input[name="csrf_token"]');
    return el ? el.value : '';
  }

  function ensureModal() {
    if (modal) return modal;

    modal = document.createElement('div');
    modal.className = 'kora-gallery-picker';
    modal.id = 'kora-gallery-picker';
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    modal.innerHTML =
      '<div class="kora-gallery-picker__backdrop" data-gallery-close></div>' +
      '<div class="kora-gallery-picker__dialog" role="dialog" aria-modal="true" aria-labelledby="kora-gallery-picker-title">' +
        '<div class="kora-gallery-picker__head">' +
          '<div>' +
            '<h2 class="kora-gallery-picker__title" id="kora-gallery-picker-title">Choose from gallery</h2>' +
            '<p class="kora-gallery-picker__sub">Select an image to use in this field</p>' +
          '</div>' +
          '<button type="button" class="kora-gallery-picker__close" data-gallery-close aria-label="Close">' +
            '<i class="fa-solid fa-xmark" aria-hidden="true"></i>' +
          '</button>' +
        '</div>' +
        '<div class="kora-gallery-picker__toolbar">' +
          '<label class="kora-gallery-picker__search">' +
            '<i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>' +
            '<input type="search" data-gallery-search placeholder="Search images…" autocomplete="off">' +
          '</label>' +
        '</div>' +
        '<div class="kora-gallery-picker__body" data-gallery-grid>' +
          '<p class="kora-gallery-picker__status" data-gallery-status>Loading…</p>' +
        '</div>' +
      '</div>';

    document.body.appendChild(modal);

    modal.addEventListener('click', function (e) {
      if (e.target.closest('[data-gallery-close]')) {
        closePicker();
        return;
      }
      var item = e.target.closest('[data-gallery-item]');
      if (item) {
        selectItem(item.getAttribute('data-gallery-path') || '');
      }
    });

    var search = modal.querySelector('[data-gallery-search]');
    if (search) {
      search.addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function () {
          loadItems(search.value.trim());
        }, 220);
      });
    }

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && modal && !modal.hidden) {
        closePicker();
      }
    });

    return modal;
  }

  function resolveTarget(selector, trigger) {
    if (!selector) return null;
    var scope = trigger ? (trigger.closest('[data-image-field], [data-repeat-item], form') || document) : document;
    try {
      return scope.querySelector(selector) || document.querySelector(selector);
    } catch (err) {
      return null;
    }
  }

  function openPicker(trigger) {
    // Prefer the image input in the same field (works after repeat-list clones)
    var field = trigger ? trigger.closest('[data-image-field]') : null;
    if (field) {
      activeTarget = field.querySelector('[data-image-input]');
    } else {
      var targetSel = trigger ? (trigger.getAttribute('data-gallery-target') || '') : '';
      activeTarget = resolveTarget(targetSel, trigger);
    }

    if (!activeTarget) {
      console.warn('Gallery picker: target not found');
      return;
    }

    var el = ensureModal();
    el.hidden = false;
    el.setAttribute('aria-hidden', 'false');
    document.body.classList.add('kora-gallery-picker-open');

    var search = el.querySelector('[data-gallery-search]');
    if (search) {
      search.value = '';
      setTimeout(function () { search.focus(); }, 40);
    }
    loadItems('');
  }

  function closePicker() {
    if (!modal) return;
    modal.hidden = true;
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('kora-gallery-picker-open');
    activeTarget = null;
  }

  function selectItem(path) {
    if (!path || !activeTarget) {
      closePicker();
      return;
    }
    activeTarget.value = path;
    activeTarget.dispatchEvent(new Event('input', { bubbles: true }));
    activeTarget.dispatchEvent(new Event('change', { bubbles: true }));
    closePicker();
  }

  function escapeHtml(s) {
    return String(s)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function escapeAttr(s) {
    return escapeHtml(s).replace(/'/g, '&#39;');
  }

  function loadItems(q) {
    var el = ensureModal();
    var grid = el.querySelector('[data-gallery-grid]');
    if (!grid) return;

    grid.innerHTML = '<p class="kora-gallery-picker__status" data-gallery-status>Loading…</p>';

    var url = listUrl + '?limit=240';
    if (q) url += '&q=' + encodeURIComponent(q);

    fetch(url, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (!data || !data.ok) {
          grid.innerHTML = '<p class="kora-gallery-picker__status">Could not load gallery.</p>';
          return;
        }

        var items = data.items || [];
        if (!items.length) {
          grid.innerHTML = '<p class="kora-gallery-picker__status">' +
            (q ? 'No images match “' + escapeHtml(q) + '”.' : 'No images found in the gallery.') +
            '</p>';
          return;
        }

        var html = '<div class="kora-gallery-picker__grid">';
        items.forEach(function (item) {
          var preview = item.preview || item.url || '';
          var name = item.filename || item.path || 'Image';
          html +=
            '<button type="button" class="kora-gallery-picker__item" data-gallery-item data-gallery-path="' +
            escapeAttr(item.path || '') + '" title="' + escapeAttr(item.path || name) + '">' +
            '<span class="kora-gallery-picker__thumb">' +
              (preview
                ? '<img src="' + escapeAttr(preview) + '" alt="" loading="lazy">'
                : '<i class="fa-solid fa-image" aria-hidden="true"></i>') +
            '</span>' +
            '<span class="kora-gallery-picker__name">' + escapeHtml(name) + '</span>' +
            '</button>';
        });
        html += '</div>';
        grid.innerHTML = html;
      })
      .catch(function () {
        grid.innerHTML = '<p class="kora-gallery-picker__status">Could not load gallery.</p>';
      });
  }

  function bind(root) {
    (root || document).querySelectorAll('[data-gallery-pick]').forEach(function (btn) {
      if (btn.dataset.galleryBound === '1') return;
      btn.dataset.galleryBound = '1';
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        openPicker(btn);
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    bind(document);
  });

  document.addEventListener('kora:fields-added', function (e) {
    bind((e && e.target) || document);
  });

  window.KoraGalleryPicker = { open: openPicker, bind: bind, close: closePicker };
})();
