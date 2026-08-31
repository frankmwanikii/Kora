(function () {
    'use strict';

    var form = document.getElementById('quote-form');

    if (!form) {
        return;
    }

    var statusEl = document.getElementById('form-status');
    var fields = form.querySelectorAll('[data-validate]');

    var messages = {
        required: 'This field is required.',
        email: 'Enter a valid email address.',
        phone: 'Enter a valid phone number.',
        inspoType: 'Only PDF, PNG, WebP, and JPG files are allowed.',
        inspoSize: 'Each file must be 20MB or smaller.'
    };

    var inspoAllowedTypes = ['application/pdf', 'image/png', 'image/webp', 'image/jpeg'];
    var inspoAllowedExtensions = ['pdf', 'png', 'webp', 'jpg', 'jpeg'];
    var inspoMaxSize = 20 * 1024 * 1024;

    function isValidEmail(value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    function isValidPhone(value) {
        return /^[\d\s+()]{7,20}$/.test(value);
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

    function getFieldError(field) {
        var type = field.getAttribute('data-validate');

        if (type === 'inspo-files') {
            var files = field.files;

            if (!files || files.length === 0) {
                return '';
            }

            for (var i = 0; i < files.length; i += 1) {
                var fileError = getInspoFileError(files[i]);

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

        if (field.type === 'file' ? field.files.length > 0 : field.value.trim() !== '') {
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

    fields.forEach(function (field) {
        var events = field.type === 'file' ? ['change'] : ['input', 'blur'];

        events.forEach(function (eventName) {
            field.addEventListener(eventName, function () {
                validateField(field);
            });
        });
    });

    form.addEventListener('submit', function (event) {
        var valid = true;

        fields.forEach(function (field) {
            if (!validateField(field)) {
                valid = false;
            }
        });

        if (!valid) {
            event.preventDefault();
            if (statusEl) {
                statusEl.textContent = 'Please correct the highlighted fields.';
                statusEl.style.color = '#8b3a3a';
            }
        }
    });

    if (window.location.search.indexOf('submitted=1') !== -1 && statusEl) {
        statusEl.textContent = 'Thank you. Your quotation request has been sent. We will respond shortly.';
        statusEl.style.color = 'var(--color-navy)';
    }
})();
