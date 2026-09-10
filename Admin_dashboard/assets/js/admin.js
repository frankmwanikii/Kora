/**
 * Kora — Admin Dashboard utilities
 */
(function () {
  'use strict';

  function initSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebar-overlay');
    var menuToggle = document.getElementById('menu-toggle');
    var sidebarClose = document.getElementById('sidebar-close');
    var collapseBtn = document.getElementById('sidebar-collapse');
    var desktopMq = window.matchMedia('(min-width: 1025px)');

    if (!sidebar) return;

    function openSidebar() {
      sidebar.classList.add('is-open');
      if (overlay) {
        overlay.hidden = false;
        requestAnimationFrame(function () {
          overlay.classList.add('is-visible');
        });
      }
      document.body.style.overflow = 'hidden';
      document.documentElement.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar.classList.remove('is-open');
      if (overlay) {
        overlay.classList.remove('is-visible');
        overlay.addEventListener('transitionend', function onEnd() {
          overlay.removeEventListener('transitionend', onEnd);
          if (!overlay.classList.contains('is-visible')) {
            overlay.hidden = true;
          }
        });
      }
      document.body.style.overflow = '';
      document.documentElement.style.overflow = '';
    }

    function setCollapsed(collapsed) {
      document.documentElement.classList.toggle('sidebar-collapsed', collapsed);
      if (collapseBtn) {
        collapseBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        collapseBtn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
        collapseBtn.setAttribute('title', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
      }
      try {
        localStorage.setItem('kora.sidebarCollapsed', collapsed ? '1' : '0');
      } catch (err) { /* ignore */ }
    }

    function syncCollapseForViewport() {
      if (!desktopMq.matches) {
        document.documentElement.classList.remove('sidebar-collapsed');
        if (collapseBtn) collapseBtn.setAttribute('aria-expanded', 'true');
        return;
      }
      var saved = false;
      try {
        saved = localStorage.getItem('kora.sidebarCollapsed') === '1';
      } catch (err) { /* ignore */ }
      setCollapsed(saved);
    }

    if (menuToggle) menuToggle.addEventListener('click', openSidebar);
    if (sidebarClose) sidebarClose.addEventListener('click', closeSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    if (collapseBtn) {
      collapseBtn.addEventListener('click', function () {
        if (!desktopMq.matches) return;
        setCollapsed(!document.documentElement.classList.contains('sidebar-collapsed'));
      });
    }

    sidebar.querySelectorAll('.nav-item').forEach(function (link) {
      link.addEventListener('click', function () {
        if (window.matchMedia('(max-width: 1024px)').matches) closeSidebar();
      });
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 1024) closeSidebar();
      syncCollapseForViewport();
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && sidebar.classList.contains('is-open')) closeSidebar();
    });

    syncCollapseForViewport();
    initNavGroups();
  }

  function initNavGroups() {
    var groups = document.querySelectorAll('[data-nav-group]');
    if (!groups.length) return;

    var stored = {};
    try {
      stored = JSON.parse(localStorage.getItem('kora.navGroups') || '{}') || {};
    } catch (err) {
      stored = {};
    }

    groups.forEach(function (group) {
      var key = group.getAttribute('data-nav-group') || '';
      var toggle = group.querySelector('.nav-group-toggle');
      if (!toggle) return;

      var collapsed = stored[key] === 1;
      if (group.querySelector('.nav-item.active')) collapsed = false;

      group.classList.toggle('is-collapsed', collapsed);
      toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');

      toggle.addEventListener('click', function () {
        var next = !group.classList.contains('is-collapsed');
        group.classList.toggle('is-collapsed', next);
        toggle.setAttribute('aria-expanded', next ? 'false' : 'true');
        try {
          stored[key] = next ? 1 : 0;
          localStorage.setItem('kora.navGroups', JSON.stringify(stored));
        } catch (err) { /* ignore */ }
      });
    });
  }

  function initDropdowns() {
    var dropdowns = document.querySelectorAll('[data-dropdown]');
    if (!dropdowns.length) return;

    function resetPanel(panel) {
      if (!panel) return;
      panel.style.position = '';
      panel.style.top = '';
      panel.style.left = '';
      panel.style.right = '';
      panel.style.bottom = '';
      panel.style.zIndex = '';
      panel.style.maxHeight = '';
      panel.style.overflowY = '';
    }

    function placePanel(toggle, panel) {
      var rect = toggle.getBoundingClientRect();
      var gap = 6;
      var margin = 8;
      panel.style.position = 'fixed';
      panel.style.right = 'auto';
      panel.style.bottom = 'auto';
      panel.style.zIndex = '400';
      panel.style.maxHeight = '';
      panel.style.overflowY = '';
      panel.style.top = '0px';
      panel.style.left = '0px';

      var pw = Math.max(panel.offsetWidth, 160);
      var ph = panel.offsetHeight;
      var spaceBelow = window.innerHeight - rect.bottom - margin;
      var spaceAbove = rect.top - margin;
      var openUp = spaceBelow < Math.min(ph, 220) && spaceAbove > spaceBelow;
      var maxH = Math.max(120, openUp ? spaceAbove - gap : spaceBelow - gap);
      if (ph > maxH) {
        panel.style.maxHeight = maxH + 'px';
        panel.style.overflowY = 'auto';
        ph = panel.offsetHeight;
      }

      var top = openUp ? rect.top - ph - gap : rect.bottom + gap;
      var left = rect.right - pw;
      if (left < margin) left = margin;
      if (left + pw > window.innerWidth - margin) {
        left = Math.max(margin, window.innerWidth - pw - margin);
      }
      if (top < margin) top = margin;
      if (top + ph > window.innerHeight - margin) {
        top = Math.max(margin, window.innerHeight - ph - margin);
      }

      panel.style.top = top + 'px';
      panel.style.left = left + 'px';
    }

    function closeAll(except) {
      dropdowns.forEach(function (dropdown) {
        if (dropdown === except) return;
        var panel = dropdown.querySelector('[data-dropdown-panel]');
        var toggle = dropdown.querySelector('[data-dropdown-toggle]');
        dropdown.classList.remove('is-open');
        if (panel) {
          panel.classList.remove('is-open');
          panel.hidden = true;
          resetPanel(panel);
        }
        if (toggle) toggle.setAttribute('aria-expanded', 'false');
      });
    }

    dropdowns.forEach(function (dropdown) {
      var toggle = dropdown.querySelector('[data-dropdown-toggle]');
      var panel = dropdown.querySelector('[data-dropdown-panel]');
      if (!toggle || !panel) return;

      toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        var isOpen = dropdown.classList.contains('is-open');
        closeAll();
        if (!isOpen) {
          dropdown.classList.add('is-open');
          panel.hidden = false;
          panel.classList.add('is-open');
          if (!dropdown.classList.contains('profile-menu')) {
            placePanel(toggle, panel);
            requestAnimationFrame(function () { placePanel(toggle, panel); });
          } else {
            resetPanel(panel);
          }
          toggle.setAttribute('aria-expanded', 'true');
        }
      });
    });

    document.addEventListener('click', function (e) {
      if (e.target.closest('[data-dropdown]')) return;
      closeAll();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeAll();
    });
    document.addEventListener('scroll', function () { closeAll(); }, true);
    window.addEventListener('resize', function () { closeAll(); });
  }

  function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
      var targetId = btn.getAttribute('data-password-toggle');
      var input = targetId ? document.getElementById(targetId) : null;
      if (!input) {
        var wrap = btn.closest('.password-field-wrap') || btn.closest('.password-field');
        if (wrap) input = wrap.querySelector('input');
      }
      if (!input && btn.previousElementSibling && btn.previousElementSibling.tagName === 'INPUT') {
        input = btn.previousElementSibling;
      }
      if (!input) return;

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        var icon = btn.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-eye', !isPassword);
          icon.classList.toggle('fa-eye-slash', isPassword);
        }
        btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        input.focus();
      });
    });
  }

  var confirmState = { open: false, resolve: null, lastFocus: null };

  function confirmRoot() {
    return document.getElementById('kora-confirm');
  }

  function setConfirmDanger(isDanger) {
    var root = confirmRoot();
    if (!root) return;
    root.classList.toggle('is-danger', !!isDanger);
    var title = document.getElementById('kora-confirm-title');
    if (title) title.textContent = isDanger ? 'Delete confirmation' : 'Please confirm';
    var ok = root.querySelector('[data-kora-confirm-ok]');
    if (ok) ok.textContent = isDanger ? 'Delete' : 'OK';
  }

  function closeConfirm(result) {
    var root = confirmRoot();
    if (!root || !confirmState.open) return;
    confirmState.open = false;
    root.classList.remove('is-open');
    root.hidden = true;
    root.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('kora-confirm-open');
    var resolve = confirmState.resolve;
    confirmState.resolve = null;
    if (confirmState.lastFocus && typeof confirmState.lastFocus.focus === 'function') {
      try { confirmState.lastFocus.focus(); } catch (err) { /* ignore */ }
    }
    confirmState.lastFocus = null;
    if (typeof resolve === 'function') resolve(!!result);
  }

  function openConfirm(message, opts) {
    opts = opts || {};
    var root = confirmRoot();
    if (!root) {
      return Promise.resolve(window.confirm(String(message || 'Are you sure?')));
    }
    if (confirmState.open) closeConfirm(false);

    var msg = String(message || 'Are you sure?');
    var isDanger = opts.danger;
    if (typeof isDanger === 'undefined') {
      isDanger = /delete|remove|overwrite|replace|permanently|cannot be undone/i.test(msg);
    }
    setConfirmDanger(isDanger);

    var messageEl = document.getElementById('kora-confirm-message');
    if (messageEl) messageEl.textContent = msg;

    confirmState.lastFocus = document.activeElement;
    confirmState.open = true;
    root.hidden = false;
    root.setAttribute('aria-hidden', 'false');
    document.body.classList.add('kora-confirm-open');

    window.requestAnimationFrame(function () {
      root.classList.add('is-open');
      var okBtn = root.querySelector('[data-kora-confirm-ok]');
      if (okBtn) okBtn.focus();
    });

    return new Promise(function (resolve) {
      confirmState.resolve = resolve;
    });
  }

  window.KoraAdmin = window.KoraAdmin || {};
  window.KoraAdmin.confirm = openConfirm;

  function initConfirmDialogs() {
    var root = confirmRoot();
    if (root) {
      root.querySelectorAll('[data-kora-confirm-cancel]').forEach(function (btn) {
        btn.addEventListener('click', function () { closeConfirm(false); });
      });
      var ok = root.querySelector('[data-kora-confirm-ok]');
      if (ok) ok.addEventListener('click', function () { closeConfirm(true); });
      document.addEventListener('keydown', function (e) {
        if (!confirmState.open) return;
        if (e.key === 'Escape') {
          e.preventDefault();
          closeConfirm(false);
        } else if (e.key === 'Enter') {
          e.preventDefault();
          closeConfirm(true);
        }
      });
    }

    document.addEventListener('click', function (e) {
      var el = e.target.closest('[data-confirm]');
      if (!el) return;
      if (el.dataset.koraConfirmed === '1') {
        el.dataset.koraConfirmed = '';
        return;
      }
      e.preventDefault();
      e.stopPropagation();
      openConfirm(el.getAttribute('data-confirm') || 'Are you sure?').then(function (ok) {
        if (!ok) return;
        el.dataset.koraConfirmed = '1';
        if (el.tagName === 'A' && el.getAttribute('href')) {
          window.location.href = el.href;
          return;
        }
        if (el.tagName === 'BUTTON' && el.type === 'submit') {
          var form = el.closest('form');
          if (form) {
            if (typeof form.requestSubmit === 'function') {
              form.requestSubmit(el);
            } else {
              form.submit();
            }
            return;
          }
        }
        el.click();
      });
    }, true);
  }

  function initAlerts() {
    document.querySelectorAll('.alert').forEach(function (alert) {
      var timer = window.setTimeout(function () {
        alert.classList.add('is-dismissing');
        alert.addEventListener('transitionend', function onEnd() {
          alert.removeEventListener('transitionend', onEnd);
          alert.remove();
        });
        window.setTimeout(function () {
          if (alert.parentNode) alert.remove();
        }, 400);
      }, 6000);

      var closeBtn = alert.querySelector('.alert-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', function () {
          window.clearTimeout(timer);
          alert.classList.add('is-dismissing');
          window.setTimeout(function () { alert.remove(); }, 300);
        });
      }
    });
  }

  function initBulkSelection() {
    document.querySelectorAll('[data-bulk-toolbar]').forEach(function (toolbar) {
      var listSelector = toolbar.getAttribute('data-bulk-list');
      var list = listSelector ? document.querySelector(listSelector) : document;
      if (!list) return;

      var formId = toolbar.getAttribute('data-bulk-form');
      var form = formId ? document.getElementById(formId) : toolbar.closest('form');
      var selectAllInputs = toolbar.querySelectorAll('[data-bulk-select-all]');
      var countEl = toolbar.querySelector('[data-bulk-selected-count]');
      var deleteBtn = toolbar.querySelector('[data-bulk-delete]');

      function getChecks() {
        return list.querySelectorAll('[data-bulk-check]');
      }

      function selectedChecks() {
        return Array.prototype.filter.call(getChecks(), function (cb) { return cb.checked; });
      }

      function sync() {
        var checks = getChecks();
        var selected = selectedChecks();
        var n = selected.length;

        checks.forEach(function (cb) {
          var row = cb.closest('[data-bulk-row]');
          if (row) row.classList.toggle('is-selected', cb.checked);
        });

        toolbar.setAttribute('data-bulk-has-selection', n > 0 ? 'true' : 'false');
        if (countEl) countEl.textContent = n + ' selected';

        selectAllInputs.forEach(function (input) {
          input.checked = checks.length > 0 && n === checks.length;
          input.indeterminate = n > 0 && n < checks.length;
        });
      }

      selectAllInputs.forEach(function (input) {
        input.addEventListener('change', function () {
          var checked = input.checked;
          getChecks().forEach(function (cb) { cb.checked = checked; });
          sync();
        });
      });

      list.addEventListener('change', function (e) {
        if (e.target && e.target.matches('[data-bulk-check]')) sync();
      });

      if (toolbar.getAttribute('data-bulk-row-click') === 'true') {
        list.querySelectorAll('[data-bulk-row]').forEach(function (row) {
          row.addEventListener('click', function (e) {
            if (!e.target) return;
            if (e.target.closest('a, button, form, label, input, .action-menu, .media-preview, .media-item__overlay, .dropdown-panel, [data-media-view]')) {
              return;
            }
            var check = row.querySelector('[data-bulk-check]');
            if (!check) return;
            check.checked = !check.checked;
            sync();
          });
        });
      }

      if (deleteBtn && form) {
        deleteBtn.addEventListener('click', function () {
          var n = selectedChecks().length;
          if (n === 0) return;
          var template = deleteBtn.getAttribute('data-bulk-confirm') || 'Delete {n} selected items?';
          var msg = template.replace(/\{n\}/g, String(n));
          openConfirm(msg, { danger: true }).then(function (ok) {
            if (ok) form.submit();
          });
        });
      }

      sync();
    });
  }

  function initCopyButtons() {
    document.querySelectorAll('[data-copy]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        var text = btn.getAttribute('data-copy') || '';
        if (!text) return;
        var done = function () {
          var prev = btn.innerHTML;
          btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied';
          window.setTimeout(function () { btn.innerHTML = prev; }, 1600);
        };
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(done).catch(function () {
            window.prompt('Copy this path:', text);
          });
        } else {
          window.prompt('Copy this path:', text);
        }
      });
    });
  }

  function resolveImagePreview(path, siteBase) {
    path = String(path || '').trim();
    if (!path) return '';
    if (/^https?:\/\//i.test(path) || path.indexOf('//') === 0) return path;
    if (path.indexOf('/assets/') === 0) return path;

    var rel = path.replace(/^\/+/, '');
    if (/^assets\/images\//i.test(rel)) {
      return '/' + rel.split('/').map(function (part) { return encodeURIComponent(part); }).join('/');
    }
    if (/^images\//i.test(rel)) {
      return '/assets/' + rel.split('/').map(function (part) { return encodeURIComponent(part); }).join('/');
    }

    siteBase = String(siteBase || '/assets/images').replace(/\/$/, '');
    var encoded = rel.split('/').map(function (part) { return encodeURIComponent(part); }).join('/');
    return siteBase + '/' + encoded;
  }

  function normalizeImagePath(value) {
    value = String(value || '').trim();
    if (!value) return '';

    // Absolute URL pointing at this site's assets/images → relative path
    var match = value.match(/\/assets\/images\/(.+)$/i);
    if (match) {
      try {
        return decodeURIComponent(match[1].split('?')[0]);
      } catch (err) {
        return match[1].split('?')[0];
      }
    }

    if (/^assets\/images\//i.test(value)) {
      return value.replace(/^assets\/images\//i, '');
    }

    return value;
  }

  function csrfToken() {
    var el = document.querySelector('input[name="csrf_token"]');
    return el ? el.value : '';
  }

  function setImageFieldStatus(field, message, isError) {
    var status = field.querySelector('[data-image-status]');
    if (!status) return;
    if (!message) {
      status.hidden = true;
      status.textContent = '';
      status.classList.remove('is-error');
      return;
    }
    status.hidden = false;
    status.textContent = message;
    status.classList.toggle('is-error', !!isError);
  }

  function bindImageField(field) {
    if (!field || field.dataset.imageBound === '1') return;
    field.dataset.imageBound = '1';

    var input = field.querySelector('[data-image-input]');
    var img = field.querySelector('[data-image-preview], .image-field__preview');
    var empty = field.querySelector('[data-image-empty], .image-field__empty');
    var uploadBtn = field.querySelector('[data-image-upload]');
    var clearBtn = field.querySelector('[data-image-clear]');
    var fileInput = field.querySelector('[data-image-file]');
    if (!input || !img) return;

    function refresh() {
      var raw = input.value.trim();
      var url = resolveImagePreview(raw, input.getAttribute('data-site-base') || '/assets/images');
      field.classList.toggle('has-image', !!url);
      if (!url) {
        img.hidden = true;
        img.removeAttribute('src');
        if (empty) empty.hidden = false;
        return;
      }
      img.hidden = false;
      if (empty) empty.hidden = true;
      img.src = url;
    }

    input.addEventListener('input', refresh);
    input.addEventListener('change', function () {
      var normalized = normalizeImagePath(input.value);
      if (normalized !== input.value) {
        input.value = normalized;
      }
      refresh();
    });
    input.addEventListener('paste', function () {
      window.setTimeout(function () {
        var normalized = normalizeImagePath(input.value);
        if (normalized !== input.value) {
          input.value = normalized;
        }
        refresh();
      }, 0);
    });

    img.addEventListener('error', function () {
      if (!input.value.trim()) return;
      img.hidden = true;
      if (empty) empty.hidden = false;
      field.classList.remove('has-image');
    });

    if (clearBtn) {
      clearBtn.addEventListener('click', function () {
        input.value = '';
        input.dispatchEvent(new Event('input', { bubbles: true }));
        setImageFieldStatus(field, '');
      });
    }

    if (uploadBtn && fileInput) {
      uploadBtn.addEventListener('click', function () {
        fileInput.click();
      });

      fileInput.addEventListener('change', function () {
        var file = fileInput.files && fileInput.files[0];
        if (!file) return;

        setImageFieldStatus(field, 'Uploading…');
        uploadBtn.disabled = true;

        var fd = new FormData();
        fd.append('file', file);
        fd.append('csrf_token', csrfToken());

        fetch('api/media-upload.php', {
          method: 'POST',
          body: fd,
          credentials: 'same-origin',
          headers: { Accept: 'application/json' }
        })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (!data || !data.ok || !data.path) {
              throw new Error((data && data.error) || 'Upload failed');
            }
            input.value = data.path;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            setImageFieldStatus(field, 'Uploaded — path filled in');
            window.setTimeout(function () { setImageFieldStatus(field, ''); }, 2200);
          })
          .catch(function (err) {
            setImageFieldStatus(field, err.message || 'Upload failed', true);
          })
          .finally(function () {
            uploadBtn.disabled = false;
            fileInput.value = '';
          });
      });
    }

    refresh();
  }

  function initImageFields(root) {
    (root || document).querySelectorAll('[data-image-field]').forEach(bindImageField);
  }

  function initRepeatLists() {
    document.querySelectorAll('[data-repeat-list]').forEach(function (listRoot) {
      var itemsWrap = listRoot.querySelector('[data-repeat-items]') ||
        listRoot.querySelector('.repeat-list__items') ||
        listRoot;
      var template = listRoot.querySelector('template[data-repeat-template]');
      var addBtn = listRoot.querySelector('[data-repeat-add]');

      function directItems() {
        // Only this list's rows — never nested feature/image repeat items
        return Array.prototype.filter.call(itemsWrap.children, function (el) {
          return el.matches && el.matches('[data-repeat-item], .repeat-list__item');
        });
      }

      function reindex() {
        var items = directItems();
        var listPrefix = listRoot.getAttribute('data-repeat-prefix') || '';
        items.forEach(function (item, index) {
          var label = item.querySelector(':scope > .repeat-list__head [data-repeat-label], :scope > [data-repeat-label]');
          if (!label) {
            label = Array.prototype.find.call(
              item.querySelectorAll('[data-repeat-label]'),
              function (el) {
                return el.closest('[data-repeat-item], .repeat-list__item') === item;
              }
            );
          }
          if (label) label.textContent = 'Item ' + (index + 1);
          if (listPrefix) {
            item.querySelectorAll('[name]').forEach(function (el) {
              // Skip fields that belong to a nested repeat list
              var nestedList = el.closest('[data-repeat-list]');
              if (nestedList && nestedList !== listRoot) return;
              var name = el.getAttribute('name') || '';
              var updated = name.replace(
                new RegExp('^' + listPrefix.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\[\\d+\\]'),
                listPrefix + '[' + index + ']'
              );
              if (updated !== name) el.setAttribute('name', updated);
            });
            item.querySelectorAll('[id]').forEach(function (el) {
              var nestedList = el.closest('[data-repeat-list]');
              if (nestedList && nestedList !== listRoot) return;
              var id = el.getAttribute('id') || '';
              var updated = id.replace(
                new RegExp('^' + listPrefix.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '\\[\\d+\\]'),
                listPrefix + '[' + index + ']'
              );
              if (updated !== id) el.setAttribute('id', updated);
            });
          }
        });
      }

      function bindRemove(item) {
        var removeBtn = Array.prototype.find.call(
          item.querySelectorAll('[data-repeat-remove]'),
          function (btn) {
            return btn.closest('[data-repeat-item], .repeat-list__item') === item;
          }
        );
        if (!removeBtn || removeBtn.dataset.repeatBound === '1') return;
        removeBtn.dataset.repeatBound = '1';
        removeBtn.addEventListener('click', function () {
          var min = parseInt(listRoot.getAttribute('data-repeat-min') || '0', 10);
          var count = directItems().length;
          if (count <= min) return;
          item.remove();
          reindex();
        });
      }

      directItems().forEach(bindRemove);

      if (addBtn) {
        addBtn.addEventListener('click', function () {
          var max = parseInt(listRoot.getAttribute('data-repeat-max') || '99', 10);
          var count = directItems().length;
          if (count >= max) return;

          var node;
          if (template) {
            node = template.content.firstElementChild.cloneNode(true);
          } else {
            var items = directItems();
            var last = items.length ? items[items.length - 1] : null;
            if (!last) return;
            node = last.cloneNode(true);
            node.querySelectorAll('input, textarea, select').forEach(function (el) {
              if (el.type === 'checkbox' || el.type === 'radio') {
                el.checked = false;
              } else {
                el.value = '';
              }
            });
          }

          if (node) {
            itemsWrap.appendChild(node);
            bindRemove(node);
            reindex();
            // Re-bind image tools / gallery pickers on cloned rows
            node.querySelectorAll('[data-image-field]').forEach(function (field) {
              field.dataset.imageBound = '';
            });
            node.querySelectorAll('[data-gallery-pick]').forEach(function (btn) {
              btn.dataset.galleryBound = '';
            });
            initImageFields(node);
            document.dispatchEvent(new CustomEvent('kora:fields-added', { bubbles: true, detail: { root: node } }));
            var focusEl = node.querySelector('input, textarea, select');
            if (focusEl) focusEl.focus();
          }
        });
      }

      reindex();
    });
  }

  function init() {
    initSidebar();
    initDropdowns();
    initPasswordToggles();
    initConfirmDialogs();
    initAlerts();
    initBulkSelection();
    initCopyButtons();
    initImageFields();
    initRepeatLists();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
