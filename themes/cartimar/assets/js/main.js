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

        // The real ceiling on how far the track can scroll — once the last
        // image's right edge reaches the viewport's right edge, there's
        // nothing more to reveal, regardless of how many "steps" of index
        // are left. On a wide/full-bleed viewport with few images this cap
        // kicks in well before index reaches items.length - 1.
        function maxOffset() {
            return Math.max(0, $track[0].scrollWidth - $viewport.width());
        }

        function update() {
            index = Math.max(index, 0);
            // At rest (index 0) the first image sits flush — no peek on the left yet.
            // Once the user moves forward, hold back part of a step so the previous
            // image's edge peeks in on the left, same as the next image already
            // peeks in on the right.
            var peek = itemStep() * 0.25;
            var rawOffset = index === 0 ? 0 : (index * itemStep() - peek);
            var max = maxOffset();
            var offset = Math.min(Math.max(rawOffset, 0), max);
            $track.css('transform', 'translateX(-' + offset + 'px)');
            $prev.prop('disabled', offset <= 0);
            $next.prop('disabled', offset >= max);
        }

        $prev.on('click', function () { index--; update(); });
        $next.on('click', function () { index++; update(); });
        $(window).on('resize', update);
        update();
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
