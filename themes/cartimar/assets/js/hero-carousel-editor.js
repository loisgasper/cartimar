/* Same hero carousel logic as main.js (front end), loaded separately here so
   it can run inside the block editor without pulling in the rest of the
   site's front-end-only script (nav scroll, directory search relocation,
   anchor hijacking, etc.) which isn't safe to run against the editor's DOM. */
jQuery(function ($) {

    function initSlides() {
        // Gutenberg re-renders the block on every edit (typing, selecting,
        // uploading a new slide) — without this guard each pass would
        // re-append a fresh set of arrows/dots and start a new timer on top
        // of any still-running one from before.
        $('.cart-hero__slides:not(.is-carousel-init)').each(function () {
            $(this).addClass('is-carousel-init');
            var slidesEl = this;
            var $slides = $(this).children();
            var $hero = $(this).closest('.cart-hero');
            if ($slides.length < 2) {
                $slides.addClass('is-active');
                var $onlyVideo = $slides.find('video');
                if ($onlyVideo.length) $onlyVideo[0].play();
                return;
            }

            // The editor can remount .cart-hero__slides as a fresh DOM node
            // (e.g. on a layout-affecting re-render) while the previous
            // instance's arrows/dots are still sitting in .cart-hero from
            // an earlier init — .is-carousel-init lives on the (now
            // replaced) slides node, not on .cart-hero, so it can't catch
            // this. Clear any stale ones out before appending new ones.
            $hero.children('.cart-hero__arrow, .cart-hero__dots').remove();

            var $arrowPrev = $('<button type="button" class="cart-hero__arrow cart-hero__arrow--prev" aria-label="Previous slide">' +
                '<svg width="12" height="20" viewBox="0 0 12 20" fill="none"><path d="M10 2L2 10L10 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>');
            var $arrowNext = $('<button type="button" class="cart-hero__arrow cart-hero__arrow--next" aria-label="Next slide">' +
                '<svg width="12" height="20" viewBox="0 0 12 20" fill="none"><path d="M2 2L10 10L2 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></button>');
            var $dots = $('<div class="cart-hero__dots"></div>');
            $slides.each(function (i) {
                $dots.append('<button type="button" class="cart-hero__dot" aria-label="Go to slide ' + (i + 1) + '"></button>');
            });
            $hero.append($arrowPrev, $arrowNext, $dots);
            var $dot = $dots.find('.cart-hero__dot');

            var IMAGE_DWELL_MS = 6000;
            var index = 0;
            var timer = null;

            function showSlide(i) {
                // The editor can drop this whole block (slide deleted,
                // undo/redo, block removed) while a timer is still pending.
                // Bail instead of operating on a detached node forever.
                if (!document.body.contains(slidesEl)) {
                    clearTimeout(timer);
                    return;
                }

                var $current = $slides.eq(index);
                var $video = $current.find('video')[0];
                if ($video) { $video.pause(); $video.currentTime = 0; }

                index = i;
                $slides.removeClass('is-active');
                var $next = $slides.eq(index).addClass('is-active');
                $dot.removeClass('is-active').eq(index).addClass('is-active');

                var video = $next.find('video')[0];
                if (video) {
                    video.currentTime = 0;
                    video.play();
                    clearTimeout(timer);
                    timer = setTimeout(advance, (video.duration && isFinite(video.duration) ? video.duration * 1000 : IMAGE_DWELL_MS));
                } else {
                    clearTimeout(timer);
                    timer = setTimeout(advance, IMAGE_DWELL_MS);
                }
            }

            function advance() {
                showSlide((index + 1) % $slides.length);
            }

            $arrowPrev.on('click', function () {
                showSlide((index - 1 + $slides.length) % $slides.length);
            });
            $arrowNext.on('click', function () {
                showSlide((index + 1) % $slides.length);
            });
            $dot.on('click', function () {
                showSlide($dot.index(this));
            });

            showSlide(0);
        });
    }

    initSlides();

    // The post/site editor canvas re-renders the hero block's markup on
    // most edits (new slide uploaded, slide reordered, undo/redo) — a
    // one-time init on load would miss all of that. Watching the document
    // for added nodes covers it without depending on Gutenberg's internal
    // data-store APIs, which differ across editor contexts and WP versions.
    var observer = new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
            if (mutations[i].addedNodes.length) {
                initSlides();
                return;
            }
        }
    });
    observer.observe(document.body, { childList: true, subtree: true });

});
