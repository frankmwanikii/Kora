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
        var desktopQuery = window.matchMedia('(min-width: 900px)');

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

    function initHomeFaqAccordion() {
        var items = document.querySelectorAll('.home-faq__item');

        if (!items.length) {
            return;
        }

        items.forEach(function (item) {
            item.addEventListener('toggle', function () {
                if (!item.open) {
                    return;
                }

                items.forEach(function (other) {
                    if (other !== item) {
                        other.removeAttribute('open');
                    }
                });
            });
        });
    }

    initFooterAccordion();
    initHomeFaqAccordion();

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
                var firstItem = gallery.querySelector('.sample-gallery__item') || gallery.querySelector('img');

                if (!firstItem) {
                    return 280;
                }

                var gap = parseFloat(window.getComputedStyle(gallery).gap) || 0;
                return firstItem.offsetWidth + gap;
            }

            function updateArrows() {
                if (!prevBtn || !nextBtn) {
                    return;
                }

                var maxScroll = gallery.scrollWidth - gallery.clientWidth;
                var canScroll = maxScroll > 2;

                wrap.classList.toggle('is-centered', !canScroll);
                prevBtn.disabled = !canScroll || gallery.scrollLeft <= 1;
                nextBtn.disabled = !canScroll || gallery.scrollLeft >= maxScroll - 1;
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

            Array.prototype.forEach.call(gallery.querySelectorAll('img'), function (img) {
                if (img.complete) {
                    return;
                }

                img.addEventListener('load', updateArrows, { once: true });
            });

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
                    return;
                }

                var item = event.target.closest('.sample-gallery__item');

                if (!item || !gallery.contains(item)) {
                    return;
                }

                if (typeof window.openSampleLightbox === 'function') {
                    window.openSampleLightbox(item);
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

    function initSampleLightbox() {
        var lightbox = document.getElementById('sample-lightbox');

        if (!lightbox) {
            return;
        }

        var imageEl = lightbox.querySelector('.sample-lightbox__image');
        var captionEl = lightbox.querySelector('.sample-lightbox__caption');
        var prevBtn = lightbox.querySelector('[data-lightbox-prev]');
        var nextBtn = lightbox.querySelector('[data-lightbox-next]');
        var closeBtns = lightbox.querySelectorAll('[data-lightbox-close]');
        var items = [];
        var index = 0;
        var lastFocus = null;

        function updateNav() {
            if (prevBtn) {
                prevBtn.disabled = items.length < 2;
            }

            if (nextBtn) {
                nextBtn.disabled = items.length < 2;
            }
        }

        function showItem(nextIndex) {
            if (!items.length) {
                return;
            }

            index = (nextIndex + items.length) % items.length;
            var item = items[index];
            var img = item.querySelector('img');

            if (!img || !imageEl) {
                return;
            }

            imageEl.src = img.currentSrc || img.src;
            imageEl.alt = img.alt || '';

            if (captionEl) {
                captionEl.textContent = img.alt || '';
            }

            updateNav();
        }

        function open(item) {
            var gallery = item.closest('.sample-gallery');

            if (!gallery) {
                return;
            }

            items = Array.prototype.slice.call(gallery.querySelectorAll('.sample-gallery__item'));
            index = items.indexOf(item);

            if (index < 0) {
                return;
            }

            lastFocus = document.activeElement;
            lightbox.hidden = false;
            document.body.classList.add('lightbox-open');
            showItem(index);

            window.requestAnimationFrame(function () {
                var closeBtn = lightbox.querySelector('.sample-lightbox__close');

                if (closeBtn) {
                    closeBtn.focus();
                }
            });
        }

        function close() {
            if (lightbox.hidden) {
                return;
            }

            lightbox.hidden = true;
            document.body.classList.remove('lightbox-open');

            if (imageEl) {
                imageEl.removeAttribute('src');
                imageEl.alt = '';
            }

            if (captionEl) {
                captionEl.textContent = '';
            }

            if (lastFocus && typeof lastFocus.focus === 'function') {
                lastFocus.focus();
            }

            lastFocus = null;
            items = [];
        }

        function step(direction) {
            if (items.length < 2) {
                return;
            }

            showItem(index + direction);
        }

        window.openSampleLightbox = open;

        closeBtns.forEach(function (btn) {
            btn.addEventListener('click', close);
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                step(-1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                step(1);
            });
        }

        document.addEventListener('keydown', function (event) {
            if (lightbox.hidden) {
                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                close();
                return;
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                step(-1);
                return;
            }

            if (event.key === 'ArrowRight') {
                event.preventDefault();
                step(1);
            }
        });
    }

    initSampleLightbox();

    function initHeroSlider() {
        var root = document.querySelector('[data-hero-slider]');

        if (!root) {
            return;
        }

        var slides = Array.prototype.slice.call(root.querySelectorAll('.hero-slider__slide'));
        var dots = Array.prototype.slice.call(root.querySelectorAll('.hero-slider__dot'));
        var prevBtn = root.querySelector('.hero-slider__arrow--prev');
        var nextBtn = root.querySelector('.hero-slider__arrow--next');
        var index = 0;
        var timer = null;
        var pointerStartX = 0;
        var pointerDelta = 0;
        var isPointerDown = false;

        if (slides.length < 2) {
            return;
        }

        function goTo(nextIndex) {
            var target = (nextIndex + slides.length) % slides.length;

            if (target === index) {
                return;
            }

            index = target;

            slides.forEach(function (slide, slideIndex) {
                var isActive = slideIndex === index;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', isActive ? 'false' : 'true');
            });

            dots.forEach(function (dot, dotIndex) {
                var isActive = dotIndex === index;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
        }

        var autoplayMs = 6200;

        function next() {
            goTo(index + 1);
        }

        function prev() {
            goTo(index - 1);
        }

        function stopAutoplay() {
            if (timer) {
                window.clearTimeout(timer);
                timer = null;
            }
        }

        function startAutoplay() {
            stopAutoplay();

            if (document.hidden) {
                return;
            }

            timer = window.setTimeout(function () {
                next();
                startAutoplay();
            }, autoplayMs);
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                next();
                startAutoplay();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                prev();
                startAutoplay();
            });
        }

        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                goTo(Number(dot.getAttribute('data-slide-to')));
                startAutoplay();
            });
        });

        root.addEventListener('keydown', function (event) {
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                next();
                startAutoplay();
            }

            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                prev();
                startAutoplay();
            }
        });

        root.addEventListener('pointerdown', function (event) {
            if (event.pointerType === 'mouse' && event.button !== 0) {
                return;
            }

            if (event.target.closest('a, button')) {
                return;
            }

            isPointerDown = true;
            pointerStartX = event.clientX;
            pointerDelta = 0;
            stopAutoplay();
        });

        root.addEventListener('pointerup', function (event) {
            if (!isPointerDown) {
                return;
            }

            isPointerDown = false;
            pointerDelta = event.clientX - pointerStartX;

            if (Math.abs(pointerDelta) >= 48) {
                if (pointerDelta < 0) {
                    next();
                } else {
                    prev();
                }
            }

            startAutoplay();
        });

        root.addEventListener('pointercancel', function () {
            isPointerDown = false;
            startAutoplay();
        });

        root.addEventListener('pointerleave', function () {
            isPointerDown = false;
        });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stopAutoplay();
            } else {
                startAutoplay();
            }
        });

        startAutoplay();
    }

    function initHeroTypewriter() {
        var root = document.querySelector('[data-hero-typewriter]');

        if (!root) {
            return;
        }

        var targets = Array.prototype.slice.call(root.querySelectorAll('[data-typewriter]'));
        var actions = root.querySelector('.hero-slider__actions');
        var title = root.querySelector('.hero-slider__title');
        var reduceMotion = false;

        try {
            reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        } catch (err) {
            reduceMotion = false;
        }

        function fillAll() {
            targets.forEach(function (el) {
                var text = el.getAttribute('data-typewriter-text') || '';
                var typed = el.querySelector('.hero-slider__typed');
                if (typed) {
                    typed.textContent = text;
                }
                el.classList.add('is-complete');
                el.classList.remove('is-typing');
            });
            if (title) {
                title.classList.remove('is-typing');
            }
            if (actions) {
                actions.classList.add('is-visible');
            }
        }

        if (reduceMotion || targets.length === 0) {
            fillAll();
            return;
        }

        // Clear fallback text so typing is visible.
        targets.forEach(function (el) {
            var typed = el.querySelector('.hero-slider__typed');
            if (typed) {
                typed.textContent = '';
            }
            el.classList.remove('is-complete');
        });
        if (actions) {
            actions.classList.remove('is-visible');
        }

        function typeElement(el, speed, done) {
            var text = el.getAttribute('data-typewriter-text') || '';
            var typed = el.querySelector('.hero-slider__typed');
            var i = 0;
            var inTitle = !!(title && title.contains(el));

            el.classList.add('is-typing');
            if (inTitle) {
                title.classList.add('is-typing');
            }

            function tick() {
                if (i <= text.length) {
                    if (typed) {
                        typed.textContent = text.slice(0, i);
                    }
                    i += 1;
                    window.setTimeout(tick, speed);
                    return;
                }

                el.classList.remove('is-typing');
                el.classList.add('is-complete');
                if (inTitle) {
                    title.classList.remove('is-typing');
                }
                if (typeof done === 'function') {
                    done();
                }
            }

            tick();
        }

        function runNext(index) {
            if (index >= targets.length) {
                if (actions) {
                    actions.classList.add('is-visible');
                }
                return;
            }

            var el = targets[index];
            var isTitlePart = !!(title && title.contains(el));
            var speed = el.classList.contains('hero-slider__kicker')
                ? 48
                : (isTitlePart ? 55 : 36);
            var pause = el.classList.contains('hero-slider__kicker') ? 420 : 260;

            typeElement(el, speed, function () {
                window.setTimeout(function () {
                    runNext(index + 1);
                }, pause);
            });
        }

        window.setTimeout(function () {
            runNext(0);
        }, 350);
    }

    initHeroSlider();
    initHeroTypewriter();
})();
