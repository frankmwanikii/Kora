(function () {
    'use strict';

    var header = document.querySelector('.site-header');
    var toggle = document.querySelector('.nav-toggle');
    var mobileNav = document.getElementById('mobile-nav');
    var lastScrollY = window.scrollY;
    var scrollThreshold = 72;
    var scrollTicking = false;

    function closeNav() {
        if (!toggle || !mobileNav) {
            return;
        }

        toggle.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open menu');
        mobileNav.classList.remove('is-open');
        mobileNav.hidden = true;
    }

    function openNav() {
        if (!toggle || !mobileNav) {
            return;
        }

        toggle.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close menu');
        mobileNav.classList.add('is-open');
        mobileNav.hidden = false;
    }

    function showHeader() {
        if (!header) {
            return;
        }

        header.classList.remove('is-hidden');
    }

    function hideHeader() {
        if (!header) {
            return;
        }

        if (mobileNav && mobileNav.classList.contains('is-open')) {
            return;
        }

        header.classList.add('is-hidden');
    }

    function updateHeaderOnScroll() {
        var currentScrollY = window.scrollY;
        var scrollDelta = currentScrollY - lastScrollY;

        if (currentScrollY <= scrollThreshold) {
            showHeader();
        } else if (scrollDelta > 4) {
            showHeader();
        } else if (scrollDelta < -4) {
            hideHeader();
        }

        lastScrollY = currentScrollY;
        scrollTicking = false;
    }

    function onScroll() {
        if (!scrollTicking) {
            scrollTicking = true;
            window.requestAnimationFrame(updateHeaderOnScroll);
        }
    }

    if (header) {
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (toggle && mobileNav) {
        toggle.addEventListener('click', function () {
            if (mobileNav.classList.contains('is-open')) {
                closeNav();
                return;
            }

            showHeader();
            openNav();
        });

        mobileNav.addEventListener('click', function (event) {
            if (event.target.closest('a')) {
                closeNav();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && mobileNav.classList.contains('is-open')) {
                closeNav();
                toggle.focus();
            }
        });
    }
})();
