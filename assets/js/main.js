(function () {
    'use strict';

    var header = document.querySelector('.site-header');
    var toggle = document.querySelector('.nav-toggle');
    var mobileNav = document.getElementById('mobile-nav');
    var overlay = document.getElementById('mobile-nav-overlay');
    var lastScrollY = window.scrollY;
    var scrollThreshold = 72;
    var scrollTicking = false;
    var closeTimer = null;

    function closeNav() {
        if (!toggle || !mobileNav || !overlay) {
            return;
        }

        toggle.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Open menu');
        mobileNav.classList.remove('is-open');
        overlay.classList.remove('is-open');

        if (header) {
            header.classList.remove('is-menu-open');
        }

        document.body.classList.remove('mobile-nav-open');

        if (closeTimer) {
            window.clearTimeout(closeTimer);
        }

        closeTimer = window.setTimeout(function () {
            if (!mobileNav.classList.contains('is-open')) {
                mobileNav.hidden = true;
                overlay.hidden = true;
            }
        }, 320);
    }

    function openNav() {
        if (!toggle || !mobileNav || !overlay) {
            return;
        }

        if (closeTimer) {
            window.clearTimeout(closeTimer);
            closeTimer = null;
        }

        mobileNav.hidden = false;
        overlay.hidden = false;

        if (header) {
            header.classList.remove('is-hidden');
            header.classList.add('is-menu-open');
        }

        document.body.classList.add('mobile-nav-open');

        window.requestAnimationFrame(function () {
            mobileNav.classList.add('is-open');
            overlay.classList.add('is-open');
        });

        toggle.classList.add('is-open');
        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Close menu');
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

        if (header.classList.contains('is-menu-open')) {
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

    if (toggle && mobileNav && overlay) {
        toggle.addEventListener('click', function () {
            if (mobileNav.classList.contains('is-open')) {
                closeNav();
                return;
            }

            openNav();
        });

        overlay.addEventListener('click', closeNav);

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

        window.addEventListener('resize', function () {
            if (window.matchMedia('(min-width: 900px)').matches && mobileNav.classList.contains('is-open')) {
                closeNav();
            }
        });
    }

    function initFooterAccordion() {
        var accordions = document.querySelectorAll('.site-footer__accordion');
        var desktopQuery = window.matchMedia('(min-width: 640px)');

        if (!accordions.length) {
            return;
        }

        function syncAccordionMode() {
            accordions.forEach(function (accordion) {
                if (desktopQuery.matches) {
                    accordion.setAttribute('open', '');
                    return;
                }

                accordion.removeAttribute('open');
            });
        }

        accordions.forEach(function (accordion) {
            accordion.addEventListener('toggle', function () {
                if (desktopQuery.matches || !accordion.open) {
                    return;
                }

                accordions.forEach(function (other) {
                    if (other !== accordion) {
                        other.removeAttribute('open');
                    }
                });
            });
        });

        if (typeof desktopQuery.addEventListener === 'function') {
            desktopQuery.addEventListener('change', syncAccordionMode);
        } else if (typeof desktopQuery.addListener === 'function') {
            desktopQuery.addListener(syncAccordionMode);
        }

        syncAccordionMode();
    }

    initFooterAccordion();

    function initSampleGalleries() {
        var galleries = document.querySelectorAll('.sample-gallery');

        if (!galleries.length) {
            return;
        }

        galleries.forEach(function (gallery) {
            var wrap = gallery.closest('.sample-gallery-wrap');

            if (!wrap) {
                return;
            }

            var prevBtn = wrap.querySelector('.sample-gallery__arrow--prev');
            var nextBtn = wrap.querySelector('.sample-gallery__arrow--next');
            var isDragging = false;
            var startX = 0;
            var scrollLeft = 0;
            var moved = false;

            function getScrollStep() {
                var firstImage = gallery.querySelector('img');

                if (!firstImage) {
                    return 280;
                }

                var gap = parseFloat(window.getComputedStyle(gallery).gap) || 0;
                return firstImage.offsetWidth + gap;
            }

            function updateArrows() {
                if (!prevBtn || !nextBtn) {
                    return;
                }

                var maxScroll = gallery.scrollWidth - gallery.clientWidth;
                prevBtn.disabled = gallery.scrollLeft <= 1;
                nextBtn.disabled = gallery.scrollLeft >= maxScroll - 1;
            }

            function scrollGallery(direction) {
                gallery.scrollBy({
                    left: direction * getScrollStep(),
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
                });
            }

            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    scrollGallery(-1);
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    scrollGallery(1);
                });
            }

            gallery.addEventListener('scroll', updateArrows, { passive: true });
            window.addEventListener('resize', updateArrows);
            updateArrows();

            gallery.addEventListener('mousedown', function (event) {
                if (event.button !== 0) {
                    return;
                }

                isDragging = true;
                moved = false;
                startX = event.pageX;
                scrollLeft = gallery.scrollLeft;
                gallery.classList.add('is-dragging');
            });

            gallery.addEventListener('mousemove', function (event) {
                if (!isDragging) {
                    return;
                }

                event.preventDefault();
                var walk = event.pageX - startX;

                if (Math.abs(walk) > 3) {
                    moved = true;
                }

                gallery.scrollLeft = scrollLeft - walk;
            });

            function stopDragging() {
                if (!isDragging) {
                    return;
                }

                isDragging = false;
                gallery.classList.remove('is-dragging');
                updateArrows();
            }

            gallery.addEventListener('mouseup', stopDragging);
            gallery.addEventListener('mouseleave', stopDragging);

            gallery.addEventListener('click', function (event) {
                if (moved) {
                    event.preventDefault();
                    event.stopPropagation();
                    moved = false;
                }
            });

            gallery.addEventListener('keydown', function (event) {
                if (event.key === 'ArrowLeft') {
                    event.preventDefault();
                    scrollGallery(-1);
                }

                if (event.key === 'ArrowRight') {
                    event.preventDefault();
                    scrollGallery(1);
                }
            });
        });
    }

    initSampleGalleries();
})();
