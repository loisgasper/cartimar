jQuery(function ($) {

    // Nav: add .scrolled class on scroll
    var nav = $('#cartNav');
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 60) {
            nav.addClass('scrolled');
        } else {
            nav.removeClass('scrolled');
        }
    });

    // Shop Directory (homepage section only): visually align the heading
    // with the search/filter bar into one row by relocating the bar —
    // rendered by the mall-directory plugin's shortcode further down the
    // DOM — into the heading block itself. Scoped to #directory so the
    // standalone Find a Store page's own heading is untouched.
    var $directoryHeader  = $('#directory .section-header');
    var $directoryFilters = $('#directory .mall-directory-filters');
    if ($directoryHeader.length && $directoryFilters.length) {
        $directoryHeader.append($directoryFilters);
    }

    // Category pill active state (visual only — the checkbox still drives filtering)
    $(document).on('change', '.store-category-filter', function () {
        var $label = $(this).closest('.category-checkbox');
        if ($(this).is(':checked')) {
            $label.addClass('is-checked');
        } else {
            $label.removeClass('is-checked');
        }
    });

    // Hero search → focus the directory search input
    $('.cart-hero__search').on('submit', function (e) {
        e.preventDefault();
        var query = $(this).find('.cart-hero__search-input').val().trim();
        var $directorySearch = $('#store-search');
        if ($directorySearch.length) {
            $('html, body').animate({ scrollTop: $('#directory').offset().top - 80 }, 500, function () {
                $directorySearch.val(query).trigger('keyup');
            });
        }
    });

    // Smooth scroll for all anchor links
    $(document).on('click', 'a[href*="#"]', function (e) {
        var target = this.hash;
        if (!target || $(target).length === 0) return;
        e.preventDefault();
        $('html, body').animate({ scrollTop: $(target).offset().top - 70 }, 500);
    });

    // Hero carousel: auto-cycling crossfade between image/video slides.
    // Images hold for a fixed dwell time; videos hold for their own
    // duration (muted/looped playback, but the carousel still advances
    // once through so a mixed image+video hero keeps a steady rhythm).
    $('.cart-hero__slides').each(function () {
        var $slides = $(this).children();
        var $hero = $(this).closest('.cart-hero');
        if ($slides.length < 2) {
            $slides.addClass('is-active');
            var $onlyVideo = $slides.find('video');
            if ($onlyVideo.length) $onlyVideo[0].play();
            return;
        }

        // Prev/next arrows + dot pagination, built here rather than in the
        // block's saved markup since this is the code that already owns the
        // active slide index.
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

    // Here to Serve: carousel prev/next
    $('.cart-serve__carousel').each(function () {
        var $carousel = $(this);
        var $viewport = $carousel.find('.cart-serve__viewport');
        var $track = $carousel.find('.cart-serve__track');
        var $items = $track.find('> .wp-block-image');
        var $prev = $carousel.find('.cart-serve__arrow--prev');
        var $next = $carousel.find('.cart-serve__arrow--next');
        var index = 0;

        function itemStep() {
            return $items.eq(0).outerWidth(true);
        }

        // The real ceiling on how far the track can scroll, i.e. how far past
        // the viewport's content edge the track's true content extends.
        // scrollWidth is used because — unlike offsetLeft or a live
        // getBoundingClientRect() read — it reflects the track's real content
        // width regardless of any transform (or in-progress transform
        // transition) currently applied to it, so it can't be thrown off by
        // either of those.
        function maxOffset() {
            return Math.max(0, $track[0].scrollWidth - $viewport.width());
        }

        function update() {
            index = Math.max(index, 0);
            // At rest (index 0) the first image sits flush — no peek on the left yet.
            // Once the user moves forward, hold back part of a step so the previous
            // image's edge peeks in on the left, same as the next image already
            // peeks in on the right.
            var step = itemStep();
            var peek = step * 0.25;
            var rawOffset = index === 0 ? 0 : (index * step - peek);
            var max = maxOffset();
            var offset = Math.min(Math.max(rawOffset, 0), max);
            // itemStep (read via outerWidth) and the flex layout engine's own
            // internal item widths round differently, so the last real step
            // can land a few px short of max even though there's nothing left
            // to actually reveal. Snap flush once inside that noise margin —
            // well under any gap that reflects a real additional slice of
            // image (tens of px or more) — so the click that visually
            // finishes the carousel always disables Next.
            if (max - offset < 10) offset = max;
            $track.css('transform', 'translateX(-' + offset + 'px)');
            $prev.prop('disabled', offset <= 0);
            $next.prop('disabled', offset >= max);
        }

        $prev.on('click', function () { index--; update(); });
        $next.on('click', function () { index++; update(); });
        $(window).on('resize', update);
        update();
    });

    // What's Happening: the article grid always fetches every post (see the
    // query's perPage in templates/home.html) and this pages through it 3 at
    // a time via transform, rather than a full-page-reload pagination click.
    // The nav is hidden entirely whenever everything already fits on one
    // page (3 or fewer posts) — no controls needed if there's nothing to page to.
    $('.cart-news__slideshow').each(function () {
        var $slideshow = $(this);
        var $viewport = $slideshow.find('.cart-news__viewport');
        var $track = $viewport.find('.wp-block-post-template');
        var $cards = $track.children();
        var $nav = $slideshow.find('.cart-news__slideshow-nav');
        var $prev = $nav.find('.cart-news__slideshow-arrow--prev');
        var $next = $nav.find('.cart-news__slideshow-arrow--next');
        var $count = $nav.find('.cart-news__slideshow-count');
        var page = 0;

        if ($cards.length === 0) return;

        function perPage() {
            if (window.matchMedia('(max-width: 768px)').matches) return 1;
            if (window.matchMedia('(max-width: 1024px)').matches) return 2;
            return 3;
        }

        // Card width is derived from the *viewport's* width, not the track's —
        // a CSS percentage set directly on the cards would resolve against the
        // track instead, which is as wide as every post laid out in a row.
        function layout() {
            var n = perPage();
            var totalPages = Math.ceil($cards.length / n);
            page = Math.min(page, totalPages - 1);

            var gap = parseFloat($track.css('gap')) || 0;
            var cardWidth = ($viewport.width() - gap * (n - 1)) / n;
            $cards.css('flex', '0 0 ' + cardWidth + 'px');

            var pageWidth = cardWidth * n + gap * n;
            $track.css('transform', 'translateX(-' + (page * pageWidth) + 'px)');
            $prev.prop('disabled', page <= 0);
            $next.prop('disabled', page >= totalPages - 1);
            $count.text((page + 1) + ' / ' + totalPages);
            $nav.toggleClass('is-active', totalPages > 1);
        }

        $prev.on('click', function () { page = Math.max(page - 1, 0); layout(); });
        $next.on('click', function () {
            page = Math.min(page + 1, Math.ceil($cards.length / perPage()) - 1);
            layout();
        });
        $(window).on('resize', layout);
        layout();
    });

    // 75 Years Timeline: the photo pane stays sticky while you scroll through
    // the years, and which year is "active" (bold text + matching photo)
    // tracks whatever year is currently scrolled near the pane — hovering or
    // tapping a year also jumps straight to it. On desktop the photos are
    // moved out of their own year row into one shared pane so it can be
    // truly sticky (released once you scroll past the last year) — reverted
    // back inline if the viewport narrows to the mobile accordion layout.
    $('.cart-timeline').each(function () {
        var $timeline = $(this);
        var $items = $timeline.find('.cart-timeline__item');
        var $pane = $('<div class="cart-timeline__img-pane"></div>');
        var isDesktop = false;
        var mq = window.matchMedia('(min-width: 1025px)');
        var observer = null;

        $items.each(function (i) {
            $(this).attr('data-timeline-index', i);
            $(this).find('.cart-timeline__item-img').attr('data-timeline-index', i);
        });

        function setActive(index) {
            $items.removeClass('is-active');
            $items.eq(index).addClass('is-active');
            $timeline.find('.cart-timeline__item-img').removeClass('is-active');
            $timeline.find('.cart-timeline__item-img[data-timeline-index="' + index + '"]').addClass('is-active');
        }

        function startObserving() {
            if (!('IntersectionObserver' in window)) return;
            observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        setActive($(entry.target).attr('data-timeline-index'));
                    }
                });
            }, { rootMargin: '-40% 0px -50% 0px', threshold: 0 });
            $items.each(function () { observer.observe(this); });
        }

        function stopObserving() {
            if (observer) { observer.disconnect(); observer = null; }
        }

        function layout() {
            if (mq.matches && !isDesktop) {
                $items.find('.cart-timeline__item-img').appendTo($pane);
                $timeline.append($pane);
                isDesktop = true;
                startObserving();
            } else if (!mq.matches && isDesktop) {
                $pane.find('.cart-timeline__item-img').each(function () {
                    $items.eq($(this).attr('data-timeline-index')).append($(this));
                });
                $pane.detach();
                isDesktop = false;
                stopObserving();
            }
        }

        layout();
        setActive(0);
        mq.addEventListener ? mq.addEventListener('change', layout) : mq.addListener(layout);

        // Hovering lets desktop users jump straight to a year without
        // needing to scroll to it; scrolling then takes back over naturally.
        $items.on('mouseenter', function () { setActive($(this).attr('data-timeline-index')); });

        $items.find('.cart-timeline__year').on('click', function () {
            setActive($(this).closest('.cart-timeline__item').attr('data-timeline-index'));
        });
    });

    // Fade-in sections on scroll
    var $sections = $('section');
    function checkVisible() {
        $sections.each(function () {
            var top = $(this).offset().top;
            var scrollBottom = $(window).scrollTop() + $(window).height();
            if (scrollBottom > top + 80 && !$(this).hasClass('visible')) {
                $(this).addClass('visible');
            }
        });
    }
    $(window).on('scroll', checkVisible);
    checkVisible();
});
