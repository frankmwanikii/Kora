/**
 * Kora upload helper — images only.
 * Binds [data-upload] dropzones and POSTs to api/media-upload.php.
 */
(function (global) {
  'use strict';

  function formatBytes(bytes) {
    bytes = Number(bytes) || 0;
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1).replace(/\.0$/, '') + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1).replace(/\.0$/, '') + ' MB';
  }

  function hintEl(zone) {
    if (!zone) return null;
    return zone.querySelector('[data-upload-hint]') || zone.querySelector(':scope > span:not([aria-hidden])');
  }

  function ensureProgress(zone) {
    if (!zone) return null;
    var wrap = zone.querySelector('[data-upload-progress]');
    if (wrap) return wrap;
    wrap = document.createElement('div');
    wrap.className = 'dropzone-progress';
    wrap.setAttribute('data-upload-progress', '');
    wrap.hidden = true;
    wrap.innerHTML =
      '<div class="dropzone-progress__track" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
        '<span class="dropzone-progress__bar" data-upload-progress-bar></span>' +
      '</div>' +
      '<p class="dropzone-progress__meta"><span data-upload-progress-text>0%</span></p>';
    zone.appendChild(wrap);
    return wrap;
  }

  function setProgress(zone, pct, label) {
    var wrap = ensureProgress(zone);
    var bar = zone.querySelector('[data-upload-progress-bar]');
    var text = zone.querySelector('[data-upload-progress-text]');
    var strong = zone.querySelector('strong');
    var track = wrap && wrap.querySelector('[role="progressbar"]');
    if (wrap) wrap.hidden = false;
    var n = Math.max(0, Math.min(100, pct == null ? 8 : pct));
    if (bar) bar.style.width = n + '%';
    if (track) track.setAttribute('aria-valuenow', String(Math.round(n)));
    if (text) text.textContent = pct == null ? 'Working…' : Math.round(n) + '%';
    if (strong && label) strong.textContent = label;
  }

  function hideProgress(zone) {
    var wrap = zone && zone.querySelector('[data-upload-progress]');
    var bar = zone && zone.querySelector('[data-upload-progress-bar]');
    if (wrap) wrap.hidden = true;
    if (bar) bar.style.width = '0%';
  }

  function restoreIdle(zone) {
    if (!zone) return;
    var strong = zone.querySelector('strong');
    var hint = hintEl(zone);
    if (strong && zone.dataset.uploadDefaultLabel) {
      strong.textContent = zone.dataset.uploadDefaultLabel;
    }
    if (hint && hint.dataset.uploadIdleHint) {
      hint.textContent = hint.dataset.uploadIdleHint;
    }
  }

  function isImageFile(file) {
    var type = (file && file.type) || '';
    if (type.indexOf('image/') === 0) return true;
    var name = (file && file.name) || '';
    return /\.(jpe?g|png|gif|webp|svg|avif)$/i.test(name);
  }

  function updateLastUpload(data) {
    var el = document.getElementById('last-upload');
    if (!el) return;
    var path = data.path || data.url || '';
    if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
      el.value = path;
      el.dispatchEvent(new Event('input', { bubbles: true }));
      el.dispatchEvent(new Event('change', { bubbles: true }));
    } else {
      el.textContent = path || '—';
    }
  }

  function appendQueueItem(file, data) {
    var queue = document.getElementById('upload-queue');
    if (!queue) return;
    queue.hidden = false;

    var li = document.createElement('li');
    li.className = 'gallery-upload-queue__item';
    var path = (data && (data.path || data.url)) || '';
    li.innerHTML =
      '<div class="gallery-upload-queue__meta">' +
        '<strong>' + (file.name || 'Image') + '</strong>' +
        '<span class="muted">' + (path || 'Uploaded') + '</span>' +
      '</div>' +
      '<span class="gallery-upload-queue__size muted">' + formatBytes(file.size) + '</span>';
    queue.appendChild(li);
  }

  function koraUploadFile(file, opts) {
    opts = opts || {};
    if (!isImageFile(file)) {
      return Promise.reject(new Error('Only image files are allowed'));
    }

    var zone = opts.zone || null;
    var fd = new FormData();
    fd.append('file', file);
    fd.append('kind', 'image');
    fd.append('csrf_token', (document.querySelector('input[name="csrf_token"]') || {}).value || '');

    var strong = zone ? zone.querySelector('strong') : null;
    var hint = hintEl(zone);
    if (zone && strong && !zone.dataset.uploadDefaultLabel) {
      zone.dataset.uploadDefaultLabel = strong.textContent;
    }
    if (hint && !hint.dataset.uploadIdleHint) {
      hint.dataset.uploadIdleHint = hint.textContent;
    }

    var inQueue = !!opts.inQueue;
    if (zone) {
      if (!inQueue && zone.dataset.uploading === '1') {
        return Promise.reject(new Error('Upload already in progress'));
      }
      if (!inQueue) {
        zone.dataset.uploading = '1';
        zone.classList.add('is-uploading', 'is-drag');
      }
      if (hint) hint.textContent = formatBytes(file.size) + ' · ' + (file.name || 'file');
      setProgress(zone, 0, opts.progressLabel || 'Uploading… 0%');
    }

    return new Promise(function (resolve, reject) {
      var xhr = new XMLHttpRequest();
      xhr.open('POST', 'api/media-upload.php');
      xhr.setRequestHeader('Accept', 'application/json');
      xhr.responseType = 'text';

      xhr.upload.addEventListener('progress', function (e) {
        if (!zone) return;
        if (!e.lengthComputable) {
          setProgress(zone, 18, opts.progressLabel || 'Uploading…');
          return;
        }
        var pct = Math.round((e.loaded / e.total) * 100);
        var label = opts.progressLabel || ('Uploading… ' + pct + '%');
        if (!opts.progressLabel && pct > 0) label = 'Uploading… ' + pct + '%';
        setProgress(zone, pct, label);
      });

      xhr.onload = function () {
        var data = null;
        try {
          data = JSON.parse(xhr.responseText || '{}');
        } catch (err) {
          data = null;
        }
        if (xhr.status >= 200 && xhr.status < 300 && data && data.ok && (data.path || data.url)) {
          if (zone) setProgress(zone, 100, opts.doneLabel || 'Uploaded — click to add more');
          resolve(data);
          return;
        }
        var message = (data && (data.error || data.message)) || 'Upload failed';
        reject(new Error(message));
      };
      xhr.onerror = function () { reject(new Error('Network error while uploading')); };
      xhr.onabort = function () { reject(new Error('Upload cancelled')); };
      xhr.send(fd);
    }).then(function (data) {
      if (zone && !inQueue) {
        zone.dataset.uploading = '0';
        zone.classList.remove('is-uploading', 'is-drag');
        if (hint && hint.dataset.uploadIdleHint) hint.textContent = hint.dataset.uploadIdleHint;
        window.setTimeout(function () { hideProgress(zone); }, 700);
      }
      return data;
    }, function (err) {
      if (zone && !inQueue) {
        zone.dataset.uploading = '0';
        zone.classList.remove('is-uploading', 'is-drag');
        hideProgress(zone);
        if (strong) strong.textContent = err && err.message ? err.message : 'Upload failed';
        window.setTimeout(function () { restoreIdle(zone); }, 2400);
      }
      throw err;
    });
  }

  function uploadFilesSequentially(files, opts) {
    opts = opts || {};
    var zone = opts.zone || null;
    var total = files.length;
    var index = 0;
    var results = [];
    var hadError = null;

    if (zone) {
      zone.dataset.uploading = '1';
      zone.classList.add('is-uploading');
    }

    function next() {
      if (index >= total) {
        if (zone) {
          zone.dataset.uploading = '0';
          zone.classList.remove('is-uploading', 'is-drag');
          restoreIdle(zone);
          if (hadError) {
            var strong = zone.querySelector('strong');
            if (strong) strong.textContent = hadError.message || 'Some uploads failed';
            window.setTimeout(function () { restoreIdle(zone); hideProgress(zone); }, 2400);
          } else {
            setProgress(zone, 100, total === 1 ? 'Uploaded — click to add more' : total + ' files uploaded');
            window.setTimeout(function () { hideProgress(zone); }, 900);
          }
        }
        return hadError ? Promise.reject(hadError) : Promise.resolve(results);
      }

      var file = files[index];
      var current = index + 1;
      index += 1;
      var progressLabel = total > 1 ? ('Uploading ' + current + ' of ' + total + '…') : 'Uploading…';

      return koraUploadFile(file, {
        zone: zone,
        inQueue: true,
        progressLabel: progressLabel,
        doneLabel: total > 1 ? ('Uploaded ' + current + ' of ' + total) : 'Uploaded — click to add more'
      }).then(function (data) {
        results.push(data);
        updateLastUpload(data);
        appendQueueItem(file, data);
        document.dispatchEvent(new CustomEvent('kora:upload-success', {
          detail: Object.assign({}, data, { index: current, total: total, file: file })
        }));
        return next();
      }).catch(function (err) {
        hadError = err;
        return next();
      });
    }

    return next();
  }

  function bindDropzones() {
    document.querySelectorAll('[data-upload]').forEach(function (zone) {
      var input = zone.querySelector('input[type="file"]');
      var targetSel = zone.getAttribute('data-upload-target');
      var scope = zone.closest('[data-upload-scope], form') || document;
      var target = targetSel ? scope.querySelector(targetSel) : null;
      if (!input) return;
      if (zone.dataset.uploadBound === '1') return;
      zone.dataset.uploadBound = '1';

      var allowMultiple = zone.hasAttribute('data-upload-multiple');
      if (allowMultiple) input.setAttribute('multiple', 'multiple');
      input.setAttribute('accept', 'image/*');

      var label = zone.querySelector('strong');
      if (label && !zone.dataset.uploadDefaultLabel) {
        zone.dataset.uploadDefaultLabel = label.textContent;
      }

      function applyUploadResult(data) {
        var path = data.path || data.url || '';
        if (target && path) {
          target.value = path;
          target.dispatchEvent(new Event('input', { bubbles: true }));
          target.dispatchEvent(new Event('change', { bubbles: true }));
        }
        updateLastUpload(data);
      }

      function handleFiles(fileList) {
        var files = Array.prototype.slice.call(fileList || []).filter(function (f) {
          return f && isImageFile(f);
        });
        if (!files.length) return;
        if (zone.dataset.uploading === '1') return;

        if (allowMultiple && files.length > 1) {
          uploadFilesSequentially(files, { zone: zone }).catch(function () { /* surfaced on zone */ }).finally(function () {
            input.value = '';
          });
          return;
        }

        koraUploadFile(files[0], { zone: zone }).then(function (data) {
          applyUploadResult(data);
          appendQueueItem(files[0], data);
          document.dispatchEvent(new CustomEvent('kora:upload-success', {
            detail: Object.assign({}, data, { index: 1, total: 1, file: files[0] })
          }));
        }).catch(function () { /* error shown on zone */ }).finally(function () {
          input.value = '';
        });
      }

      zone.addEventListener('click', function () {
        if (zone.dataset.uploading === '1') return;
        input.click();
      });
      input.addEventListener('change', function () {
        if (input.files && input.files.length) handleFiles(input.files);
      });
      zone.addEventListener('dragover', function (e) {
        e.preventDefault();
        zone.classList.add('is-drag');
      });
      zone.addEventListener('dragleave', function () {
        zone.classList.remove('is-drag');
      });
      zone.addEventListener('drop', function (e) {
        e.preventDefault();
        zone.classList.remove('is-drag');
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
          handleFiles(e.dataTransfer.files);
        }
      });
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindDropzones);
  } else {
    bindDropzones();
  }

  global.koraUploadFile = koraUploadFile;
  global.koraUploadFiles = uploadFilesSequentially;
})(window);
