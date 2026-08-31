(function () {
    'use strict';

    var form = document.getElementById('quote-form');

    if (!form) {
        return;
    }

    var statusEl = document.getElementById('form-status');
    var submitBtn = form.querySelector('button[type="submit"]');
    var inspoInput = document.getElementById('inspo_files');
    var inspoListEl = document.getElementById('inspo-file-list');
    var uploadProgressEl = document.getElementById('upload-progress');
    var uploadProgressBar = document.getElementById('upload-progress-bar');
    var uploadProgressLabel = document.getElementById('upload-progress-label');
    var fields = form.querySelectorAll('[data-validate]');
    var inspoSelectedFiles = [];
    var isSubmitting = false;

    var messages = {
        required: 'This field is required.',
        email: 'Enter a valid email address.',
        phone: 'Enter a valid phone number.',
        quantity: 'Enter a quantity of 1 or more.',
        date: 'Enter a valid date — today or later.',
        inspoType: 'Only PDF, PNG, WebP, and JPG files are allowed.',
        inspoSize: 'Each file must be 20MB or smaller.',
        ready: 'Ready',
        uploading: 'Uploading…',
        uploaded: 'Uploaded',
        uploadFailed: 'Upload failed. Please try again.'
    };

    var inspoAllowedTypes = ['application/pdf', 'image/png', 'image/webp', 'image/jpeg'];
    var inspoAllowedExtensions = ['pdf', 'png', 'webp', 'jpg', 'jpeg'];
    var inspoMaxSize = 20 * 1024 * 1024;

    function isValidEmail(value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    function isValidPhone(value) {
        return /^[\d\s+()-]{7,20}$/.test(value);
    }

    function isValidFutureDate(value) {
        if (!/^\d{4}-\d{2}-\d{2}$/.test(value)) {
            return false;
        }

        var parsed = new Date(value + 'T00:00:00');

        if (isNaN(parsed.getTime())) {
            return false;
        }

        var today = new Date();
        today.setHours(0, 0, 0, 0);

        return parsed.getTime() >= today.getTime();
    }

    function escapeHtml(value) {
        return value
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) {
            return bytes + ' B';
        }

        if (bytes < 1024 * 1024) {
            return (bytes / 1024).toFixed(1) + ' KB';
        }

        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    }

    function getInspoFileError(file) {
        var extension = file.name.split('.').pop().toLowerCase();
        var typeAllowed = inspoAllowedTypes.indexOf(file.type) !== -1;
        var extensionAllowed = inspoAllowedExtensions.indexOf(extension) !== -1;

        if (!typeAllowed && !extensionAllowed) {
            return messages.inspoType;
        }

        if (file.size > inspoMaxSize) {
            return messages.inspoSize;
        }

        return '';
    }

    function isDuplicateFile(file) {
        return inspoSelectedFiles.some(function (existing) {
            return existing.name === file.name
                && existing.size === file.size
                && existing.lastModified === file.lastModified;
        });
    }

    function syncInspoInput() {
        if (!inspoInput || typeof DataTransfer === 'undefined') {
            return;
        }

        var dataTransfer = new DataTransfer();

        inspoSelectedFiles.forEach(function (file) {
            dataTransfer.items.add(file);
        });

        inspoInput.files = dataTransfer.files;
    }

    function addInspoFiles(fileList) {
        Array.prototype.forEach.call(fileList, function (file) {
            if (!isDuplicateFile(file)) {
                inspoSelectedFiles.push(file);
            }
        });

        syncInspoInput();

        if (inspoInput) {
            inspoInput.value = '';
        }

        renderInspoFileList();

        if (inspoInput) {
            validateField(inspoInput);
        }
    }

    function removeInspoFile(index) {
        if (isSubmitting || index < 0 || index >= inspoSelectedFiles.length) {
            return;
        }

        inspoSelectedFiles.splice(index, 1);
        syncInspoInput();

        if (inspoInput) {
            inspoInput.value = '';
        }

        renderInspoFileList();

        if (inspoInput) {
            validateField(inspoInput);
        }
    }

    function setUploadProgress(percent, label) {
        if (!uploadProgressEl || !uploadProgressBar || !uploadProgressLabel) {
            return;
        }

        uploadProgressEl.hidden = false;
        uploadProgressBar.style.width = percent + '%';
        uploadProgressLabel.textContent = label;
    }

    function hideUploadProgress() {
        if (!uploadProgressEl || !uploadProgressBar || !uploadProgressLabel) {
            return;
        }

        uploadProgressEl.hidden = true;
        uploadProgressBar.style.width = '0';
        uploadProgressLabel.textContent = '';
    }

    function setInspoFileStatuses(statusText, statusClass) {
        if (!inspoListEl) {
            return;
        }

        inspoListEl.querySelectorAll('.inspo-file-list__item.is-ready').forEach(function (item) {
            item.classList.remove('is-ready', 'is-uploading', 'is-uploaded');
            item.classList.add(statusClass);

            var itemStatusEl = item.querySelector('.inspo-file-list__status');
            if (itemStatusEl) {
                itemStatusEl.textContent = statusText;
            }
        });
    }

    function renderInspoFileList() {
        if (!inspoListEl) {
            return;
        }

        inspoListEl.innerHTML = '';

        if (inspoSelectedFiles.length === 0) {
            inspoListEl.hidden = true;
            hideUploadProgress();
            return;
        }

        inspoListEl.hidden = false;

        inspoSelectedFiles.forEach(function (file, index) {
            var error = getInspoFileError(file);
            var item = document.createElement('li');
            var statusText = error || messages.ready;
            var removeButton = '';

            if (!isSubmitting) {
                removeButton =
                    '<button type="button" class="inspo-file-list__remove" data-index="' + index + '" aria-label="Remove ' + escapeHtml(file.name) + '">' +
                    '<span aria-hidden="true">&times;</span>' +
                    '</button>';
            }

            item.className = 'inspo-file-list__item ' + (error ? 'is-invalid' : 'is-ready');
            item.innerHTML =
                '<span class="inspo-file-list__name">' + escapeHtml(file.name) + '</span>' +
                '<span class="inspo-file-list__size">' + formatFileSize(file.size) + '</span>' +
                '<span class="inspo-file-list__status">' + escapeHtml(statusText) + '</span>' +
                removeButton;

            inspoListEl.appendChild(item);
        });
    }

    function isValidQuantity(value) {
        var quantity = Number(value);

        return Number.isInteger(quantity) && quantity >= 1;
    }

    function getFieldError(field) {
        var type = field.getAttribute('data-validate');

        if (type === 'inspo-files') {
            if (inspoSelectedFiles.length === 0) {
                return '';
            }

            for (var i = 0; i < inspoSelectedFiles.length; i += 1) {
                var fileError = getInspoFileError(inspoSelectedFiles[i]);

                if (fileError) {
                    return fileError;
                }
            }

            return '';
        }

        var value = field.value.trim();

        if (type === 'optional') {
            return '';
        }

        if (value === '') {
            return messages.required;
        }

        if (type === 'email' && !isValidEmail(value)) {
            return messages.email;
        }

        if (type === 'phone' && !isValidPhone(value)) {
            return messages.phone;
        }

        if (type === 'quantity' && !isValidQuantity(value)) {
            return messages.quantity;
        }

        if (type === 'date' && !isValidFutureDate(value)) {
            return messages.date;
        }

        return '';
    }

    function setFieldState(field, error) {
        var errorId = field.id + '-error';
        var errorEl = document.getElementById(errorId);

        field.classList.remove('is-valid', 'is-invalid');

        if (error) {
            field.classList.add('is-invalid');
            if (errorEl) {
                errorEl.textContent = error;
            }
            return false;
        }

        if (field.type === 'file' ? inspoSelectedFiles.length > 0 : field.value.trim() !== '') {
            field.classList.add('is-valid');
        }

        if (errorEl) {
            errorEl.textContent = '';
        }

        return true;
    }

    function validateField(field) {
        return setFieldState(field, getFieldError(field));
    }

    function validateForm() {
        var valid = true;

        fields.forEach(function (field) {
            if (!validateField(field)) {
                valid = false;
            }
        });

        return valid;
    }

    function appendInspoFilesToFormData(formData) {
        formData.delete('inspo_files[]');

        inspoSelectedFiles.forEach(function (file) {
            formData.append('inspo_files[]', file, file.name);
        });
    }

    function submitWithProgress() {
        var xhr = new XMLHttpRequest();
        var formData = new FormData(form);
        var hasFiles = inspoSelectedFiles.length > 0;
        var uploadActive = false;

        appendInspoFilesToFormData(formData);

        isSubmitting = true;
        renderInspoFileList();

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = hasFiles ? 'Uploading…' : 'Sending…';
        }

        if (!hasFiles && statusEl) {
            statusEl.textContent = 'Sending your quotation request…';
            statusEl.style.color = 'var(--color-navy)';
        }

        xhr.open('POST', form.action);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.addEventListener('progress', function (event) {
            if (!hasFiles || event.total === 0 || event.loaded === 0) {
                return;
            }

            if (!uploadActive) {
                uploadActive = true;
                setInspoFileStatuses(messages.uploading, 'is-uploading');
            }

            var percent = Math.min(99, Math.round((event.loaded / event.total) * 100));
            setUploadProgress(percent, 'Uploading files… ' + percent + '%');
        });

        xhr.upload.addEventListener('loadend', function () {
            hideUploadProgress();
        });

        xhr.addEventListener('load', function () {
            var response = null;

            try {
                response = JSON.parse(xhr.responseText);
            } catch (error) {
                response = null;
            }

            if (xhr.status >= 200 && xhr.status < 300 && response && response.redirect) {
                if (hasFiles && uploadActive) {
                    setInspoFileStatuses(messages.uploaded, 'is-uploaded');
                }

                window.location.href = response.redirect;
                return;
            }

            isSubmitting = false;

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Request a Quotation';
            }

            hideUploadProgress();
            renderInspoFileList();

            if (statusEl) {
                if (response && response.errors) {
                    var firstError = Object.values(response.errors)[0];
                    statusEl.textContent = firstError || messages.uploadFailed;
                } else {
                    statusEl.textContent = messages.uploadFailed;
                }

                statusEl.style.color = '#8b3a3a';
            }
        });

        xhr.addEventListener('error', function () {
            isSubmitting = false;

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Request a Quotation';
            }

            hideUploadProgress();
            renderInspoFileList();

            if (statusEl) {
                statusEl.textContent = messages.uploadFailed;
                statusEl.style.color = '#8b3a3a';
            }
        });

        xhr.send(formData);
    }

    if (inspoListEl) {
        inspoListEl.addEventListener('click', function (event) {
            var removeButton = event.target.closest('.inspo-file-list__remove');

            if (!removeButton) {
                return;
            }

            removeInspoFile(Number(removeButton.getAttribute('data-index')));
        });
    }

    fields.forEach(function (field) {
        var events = field.type === 'file' ? ['change'] : ['input', 'blur'];

        events.forEach(function (eventName) {
            field.addEventListener(eventName, function () {
                if (field.type === 'file') {
                    if (field.files && field.files.length > 0) {
                        addInspoFiles(field.files);
                    }
                    return;
                }

                validateField(field);
            });
        });
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        if (isSubmitting) {
            return;
        }

        if (!validateForm()) {
            if (statusEl) {
                statusEl.textContent = 'Please correct the highlighted fields.';
                statusEl.style.color = '#8b3a3a';
            }
            return;
        }

        if (statusEl) {
            statusEl.textContent = '';
        }

        submitWithProgress();
    });

    if (window.location.search.indexOf('submitted=1') !== -1 && statusEl) {
        statusEl.textContent = 'Thank you. Your quotation request has been sent. We will respond shortly.';
        statusEl.style.color = 'var(--color-navy)';
    }
})();
