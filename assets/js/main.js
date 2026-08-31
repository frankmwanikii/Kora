(function () {
    'use strict';

    var toggle = document.querySelector('.nav-toggle');
    var mobileNav = document.getElementById('mobile-nav');

    if (!toggle || !mobileNav) {
        return;
    }

    function closeNav() {
        toggle.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open menu');
        mobileNav.classList.remove('is-open');
        mobileNav.hidden = true;
    }

    function openNav() {
        toggle.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close menu');
        mobileNav.classList.add('is-open');
        mobileNav.hidden = false;
    }

    toggle.addEventListener('click', function () {
        if (mobileNav.classList.contains('is-open')) {
            closeNav();
        } else {
            openNav();
        }
    });

    mobileNav.addEventListener('click', function (event) {
        if (event.target.classList.contains('mobile-nav__link')) {
            closeNav();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && mobileNav.classList.contains('is-open')) {
            closeNav();
            toggle.focus();
        }
    });
})();
