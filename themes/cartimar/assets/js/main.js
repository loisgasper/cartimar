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
        var $track = $carousel.find('.cart-serve__track');
        var $items = $track.find('> .wp-block-image');
        var $prev = $carousel.find('.cart-serve__arrow--prev');
        var $next = $carousel.find('.cart-serve__arrow--next');
        var index = 0;

        function itemStep() {
            return $items.eq(0).outerWidth(true);
        }

        function update() {
            var maxIndex = Math.max($items.length - 1, 0);
            index = Math.min(Math.max(index, 0), maxIndex);
            // At rest (index 0) the first image sits flush — no peek on the left yet.
            // Once the user moves forward, hold back part of a step so the previous
            // image's edge peeks in on the left, same as the next image already
            // peeks in on the right.
            var peek = itemStep() * 0.25;
            var offset = index === 0 ? 0 : (index * itemStep() - peek);
            $track.css('transform', 'translateX(-' + offset + 'px)');
            $prev.prop('disabled', index === 0);
            $next.prop('disabled', index >= maxIndex);
        }

        $prev.on('click', function () { index--; update(); });
        $next.on('click', function () { index++; update(); });
        $(window).on('resize', update);
        update();
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
