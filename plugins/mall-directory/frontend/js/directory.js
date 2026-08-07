jQuery(document).ready(function ($) {

    // ─────────────────────────────────────────────────────────
    // DATA
    // ─────────────────────────────────────────────────────────
    var storesData = JSON.parse($('#storesData').text());

    // How many of these were already rendered server-side (see
    // MALL_DIR_INITIAL_BATCH in mall-directory.php) — that batch already
    // exists as real .store-item DOM nodes, so rendering starts after it
    // instead of duplicating them.
    var initialRenderedCount = $('#storesList .store-item').length;
    var BATCH_SIZE = 50;

    var pinIconSVG = '<svg class="store-pin-svg" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>';

    function escapeHtml(str) {
        return String(str == null ? '' : str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    // Mirrors store-item.php — kept in sync manually since PHP renders the
    // first batch and this renders everything after it (see
    // initialRenderedCount above).
    function renderStoreItem(store) {
        var categoryIds = (store.categories || []).map(function (c) { return c.id; });
        var thumb = store.logo
            ? '<img src="' + escapeHtml(store.logo) + '" alt="' + escapeHtml(store.title) + '" loading="lazy" width="56" height="56">'
            : '<div class="store-item-thumb--pin">' + pinIconSVG + '</div>';

        var locationHtml = store.location
            ? '<p class="store-item-location"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>' + escapeHtml(store.location) + '</p>'
            : '';
        var stallHtml = store.stall
            ? '<p class="store-item-stall"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v6H3V3zm0 8h8v10H3V11zm10 0h8v10h-8V11z"/></svg>' + escapeHtml(store.stall) + '</p>'
            : '';
        var phoneHtml = store.phone
            ? '<p class="store-item-phone"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>' + escapeHtml(store.phone) + '</p>'
            : '';

        var $item = $(
            '<div class="store-item" ' +
                'data-store-id="' + escapeHtml(store.id) + '" ' +
                'data-store-name="' + escapeHtml(store.title) + '" ' +
                'data-store-location="' + escapeHtml(store.location) + '" ' +
                'data-store-stall="' + escapeHtml(store.stall) + '" ' +
                'data-store-phone="' + escapeHtml(store.phone) + '" ' +
                'data-map-area="' + escapeHtml(store.map_area) + '" ' +
                'data-categories="' + escapeHtml(JSON.stringify(categoryIds)) + '">' +
                '<div class="store-item-thumb">' + thumb + '</div>' +
                '<div class="store-item-info"><h4>' + escapeHtml(store.title) + '</h4>' + locationHtml + stallHtml + phoneHtml + '</div>' +
            '</div>'
        );
        return $item;
    }

    // Building bounding boxes (SVG-coordinate space) used for "zoom to area"
    var areaDefs = {
        'Aqualand Alley':                           { x1: 820,  y1: 38,  x2: 934,  y2: 130  },
        'Greenland Plants and Orchids Center':      { x1: 954,  y1: 38,  x2: 1200, y2: 130  },
        'Cartimar Villa Village 2':                 { x1: 310,  y1: 155, x2: 610,  y2: 258  },
        'Cartimar Villa Village 1 (Left)':          { x1: 636,  y1: 155, x2: 870,  y2: 258  },
        'Cartimar Villa Village 1 (Right)':         { x1: 900,  y1: 160, x2: 1188, y2: 228  },
        'Pet Center':                               { x1: 900,  y1: 246, x2: 1188, y2: 314  },
        'Plaza':                                     { x1: 300,  y1: 300, x2: 578,  y2: 442  },
        'Admin':                                    { x1: 594,  y1: 300, x2: 656,  y2: 442  },
        'Food Court':                               { x1: 680,  y1: 300, x2: 762,  y2: 442  },
        'Save More Grocery Store':                  { x1: 778,  y1: 300, x2: 1192, y2: 442  },
        'Aqualand Center':                          { x1: 1210, y1: 300, x2: 1416, y2: 380  },
        'Grains & Grocery':                         { x1: 300,  y1: 497, x2: 650,  y2: 667  },
        'Cartimar Main Building':                   { x1: 665,  y1: 497, x2: 1192, y2: 667  },
        'Cartimar Carpark and Fresh Food Plaza':    { x1: 17,   y1: 498, x2: 258,  y2: 582  },
        'Gateway (Upper)':                          { x1: 1300, y1: 291, x2: 1416, y2: 442  },
        'Gateway (Lower)':                          { x1: 1300, y1: 497, x2: 1416, y2: 667  },
    };

    // ─────────────────────────────────────────────────────────
    // ZOOM / PAN STATE
    // ─────────────────────────────────────────────────────────
    var VB_W = 1672, VB_H = 941;          // original viewBox dimensions
    var MIN_W = 400;                       // minimum width (≈ max zoom level)
    var vb = { x: 0, y: 0, w: VB_W, h: VB_H };

    var svgEl  = document.getElementById('cartimar-map-svg');
    var $svg   = $(svgEl);

    var isDragging     = false;
    var didDrag        = false;
    var dragStart      = {};
    var lastTouchDist  = null;
    var lastTouchMid   = null;

    function applyViewBox() {
        svgEl.setAttribute('viewBox', vb.x + ' ' + vb.y + ' ' + vb.w + ' ' + vb.h);
    }

    function clamp() {
        // Maintain aspect ratio while constraining zoom level
        vb.w = Math.max(MIN_W, Math.min(VB_W, vb.w));
        vb.h = vb.w * (VB_H / VB_W);
        vb.x = Math.max(0, Math.min(VB_W - vb.w, vb.x));
        vb.y = Math.max(0, Math.min(VB_H - vb.h, vb.y));
    }

    // Convert a client-space point to SVG-viewBox coordinates
    function clientToSvg(cx, cy) {
        var r = svgEl.getBoundingClientRect();
        return {
            x: (cx - r.left) / r.width  * vb.w + vb.x,
            y: (cy - r.top)  / r.height * vb.h + vb.y
        };
    }

    // Zoom centred on a given client-space point
    function zoomAt(cx, cy, factor) {
        var p = clientToSvg(cx, cy);
        vb.w *= factor;
        vb.h *= factor;
        var r = svgEl.getBoundingClientRect();
        vb.x  = p.x - (cx - r.left) / r.width  * vb.w;
        vb.y  = p.y - (cy - r.top)  / r.height * vb.h;
        clamp();
        applyViewBox();
    }

    // Smooth zoom to a bounding box (for "zoom to area")
    function zoomToBBox(x1, y1, x2, y2) {
        var pad = 80;
        var bw  = (x2 - x1) + pad * 2;
        var bh  = bw * (VB_H / VB_W);
        vb.x = Math.max(0, x1 - pad);
        vb.y = Math.max(0, y1 - pad);
        vb.w = bw;
        vb.h = bh;
        clamp();
        applyViewBox();
    }

    // ── Wheel: pinch-to-zoom only, plain scroll passes through ──
    // Trackpad pinch gestures (and ctrl+wheel) arrive as wheel events with
    // ctrlKey set — that's the only case we zoom. Any other wheel/scroll
    // (mouse wheel, two-finger scroll, horizontal scroll) is left alone so
    // the page scrolls normally instead of getting trapped by the map.
    svgEl.addEventListener('wheel', function (e) {
        if (!e.ctrlKey) return;
        e.preventDefault();
        var factor = e.deltaY > 0 ? 1.14 : 1 / 1.14;
        zoomAt(e.clientX, e.clientY, factor);
    }, { passive: false });

    // ── Mouse drag-to-pan ─────────────────────────────────────
    svgEl.addEventListener('mousedown', function (e) {
        // Don't steal clicks on area zones
        if (e.target.closest && e.target.closest('.area-zone')) return;
        isDragging = true;
        didDrag    = false;
        dragStart  = { cx: e.clientX, cy: e.clientY, vx: vb.x, vy: vb.y };
        $svg.addClass('is-dragging');
        e.preventDefault();
    });

    document.addEventListener('mousemove', function (e) {
        if (!isDragging) return;
        var dx = e.clientX - dragStart.cx;
        var dy = e.clientY - dragStart.cy;
        if (Math.abs(dx) > 3 || Math.abs(dy) > 3) didDrag = true;
        var r  = svgEl.getBoundingClientRect();
        var sx = vb.w / r.width;
        var sy = vb.h / r.height;
        vb.x   = Math.max(0, Math.min(VB_W - vb.w, dragStart.vx - dx * sx));
        vb.y   = Math.max(0, Math.min(VB_H - vb.h, dragStart.vy - dy * sy));
        applyViewBox();
    });

    document.addEventListener('mouseup', function () {
        if (!isDragging) return;
        isDragging = false;
        $svg.removeClass('is-dragging');
    });

    // ── Touch pinch-to-zoom + one-finger pan ──────────────────
    svgEl.addEventListener('touchstart', function (e) {
        if (e.touches.length === 2) {
            var t0 = e.touches[0], t1 = e.touches[1];
            var dx = t0.clientX - t1.clientX, dy = t0.clientY - t1.clientY;
            lastTouchDist = Math.sqrt(dx * dx + dy * dy);
            lastTouchMid  = { x: (t0.clientX + t1.clientX) / 2, y: (t0.clientY + t1.clientY) / 2 };
        } else if (e.touches.length === 1) {
            isDragging = true;
            dragStart  = { cx: e.touches[0].clientX, cy: e.touches[0].clientY, vx: vb.x, vy: vb.y };
        }
    }, { passive: true });

    svgEl.addEventListener('touchmove', function (e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            var t0 = e.touches[0], t1 = e.touches[1];
            var dx   = t0.clientX - t1.clientX, dy = t0.clientY - t1.clientY;
            var dist = Math.sqrt(dx * dx + dy * dy);
            var mid  = { x: (t0.clientX + t1.clientX) / 2, y: (t0.clientY + t1.clientY) / 2 };
            if (lastTouchDist) {
                zoomAt(mid.x, mid.y, lastTouchDist / dist);
            }
            lastTouchDist = dist;
            lastTouchMid  = mid;
        } else if (e.touches.length === 1 && isDragging) {
            var r  = svgEl.getBoundingClientRect();
            var sx = vb.w / r.width, sy = vb.h / r.height;
            vb.x   = Math.max(0, Math.min(VB_W - vb.w, dragStart.vx - (e.touches[0].clientX - dragStart.cx) * sx));
            vb.y   = Math.max(0, Math.min(VB_H - vb.h, dragStart.vy - (e.touches[0].clientY - dragStart.cy) * sy));
            applyViewBox();
        }
    }, { passive: false });

    svgEl.addEventListener('touchend', function () {
        isDragging    = false;
        lastTouchDist = null;
        lastTouchMid  = null;
    });

    // ── Zoom button handlers ──────────────────────────────────
    $('#svgZoomIn').on('click', function () {
        var cx = vb.x + vb.w / 2, cy = vb.y + vb.h / 2;
        vb.w /= 1.3; vb.h /= 1.3;
        vb.x  = cx - vb.w / 2; vb.y = cy - vb.h / 2;
        clamp(); applyViewBox();
    });

    $('#svgZoomOut').on('click', function () {
        var cx = vb.x + vb.w / 2, cy = vb.y + vb.h / 2;
        vb.w *= 1.3; vb.h *= 1.3;
        vb.x  = cx - vb.w / 2; vb.y = cy - vb.h / 2;
        clamp(); applyViewBox();
    });

    $('#svgZoomReset').on('click', function () {
        vb = { x: 0, y: 0, w: VB_W, h: VB_H };
        applyViewBox();
    });

    // ─────────────────────────────────────────────────────────
    // FILTER STATE
    // ─────────────────────────────────────────────────────────
    var activeFilterType  = 'all';
    var activeFilterValue = '';
    var searchQuery       = '';

    // ─────────────────────────────────────────────────────────
    // HIGHLIGHT
    // ─────────────────────────────────────────────────────────
    var highlightedArea = null;

    function findZone(areaName) {
        var found = null;
        $svg.find('.area-zone').each(function () {
            if ($(this).attr('data-area').toLowerCase() === areaName.toLowerCase()) {
                found = this; return false;
            }
        });
        return found;
    }

    // ── Map pin — drops onto whichever zone setHighlight() turns on ──
    var $pin        = $('#map-pin');
    var pinBounceEl = document.querySelector('#map-pin .map-pin__bounce');
    var pinArea     = null;

    // Per-zone nudges on top of the default getBBox() placement below —
    // for zones (like Save More Grocery Store's L-shaped notch) where the
    // default 28%/20% offset lands somewhere awkward.
    var pinOffsets = {
        'Save More Grocery Store': { dx: -50, dy: 0 },
    };

    function showPin(areaName) {
        var zone = findZone(areaName);
        var rect = zone && zone.querySelector('.area-fill');
        if (!rect) return;
        // getBBox() reads the zone's actual rendered position/size directly,
        // so the pin always lands correctly on it even if the rect's
        // coordinates ever change — no separate list of positions to keep in
        // sync. Offset toward the upper-left (rather than dead-centre) so it
        // doesn't sit on top of the zone's own name label.
        var bbox = rect.getBBox();
        var cx = bbox.x + bbox.width * 0.28;
        var cy = bbox.y + bbox.height * 0.2;
        var offset = pinOffsets[areaName];
        if (offset) {
            cx += offset.dx;
            cy += offset.dy;
        }
        $pin.attr('transform', 'translate(' + cx + ',' + cy + ')');
        // Only replay the drop/bounce when arriving somewhere new — re-hovering
        // the area it's already sitting on shouldn't make it jitter.
        if (pinArea !== areaName || !$pin.hasClass('is-visible')) {
            $pin.removeClass('is-visible');
            void pinBounceEl.offsetWidth; // force reflow to restart the CSS animation
            $pin.addClass('is-visible');
        }
        pinArea = areaName;
    }

    function hidePin() {
        $pin.removeClass('is-visible');
        pinArea = null;
    }

    function setHighlight(areaName, on) {
        if (highlightedArea && highlightedArea !== areaName) {
            var prev = findZone(highlightedArea);
            if (prev) $(prev).removeClass('is-highlighted');
            highlightedArea = null;
        }
        var zone = findZone(areaName);
        if (!zone) return;
        if (on) {
            $(zone).addClass('is-highlighted');
            highlightedArea = areaName;
            showPin(areaName);
        } else {
            $(zone).removeClass('is-highlighted');
            if (highlightedArea === areaName) highlightedArea = null;
            hidePin();
        }
    }

    function clearAllHighlights() {
        $svg.find('.area-zone').removeClass('is-highlighted');
        highlightedArea = null;
        hidePin();
    }

    // ─────────────────────────────────────────────────────────
    // ZOOM TO AREA
    // ─────────────────────────────────────────────────────────
    function zoomToArea(areaName) {
        var def = areaDefs[areaName];
        if (!def) return;
        zoomToBBox(def.x1, def.y1, def.x2, def.y2);
    }

    // ─────────────────────────────────────────────────────────
    // FILTERING + INCREMENTAL RENDER
    // ─────────────────────────────────────────────────────────
    // With 800+ stores, keeping every card in the DOM and toggling
    // visibility (the old approach) means 800 image requests and 800 DOM
    // nodes on every page load. Instead: filtering runs against the
    // storesData array (not the DOM), and only a batch of matches is
    // rendered at a time — more render in as the user scrolls the list
    // (see the IntersectionObserver below) or as a new filter/search runs.
    var $storesList     = $('#storesList');
    var $sentinel       = $('#storesListSentinel');
    var filteredStores  = null; // null = "showing the default unfiltered/unsearched list"
    // The initial batch already exists as server-rendered DOM nodes (see
    // initialRenderedCount above) — start counting from there instead of
    // re-rendering/duplicating them on first load.
    var renderedCount   = initialRenderedCount;

    function storeMatches(store) {
        var storeArea = (store.map_area || '').toLowerCase();
        var storeCategories = (store.categories || []).map(function (c) { return c.id; });

        var filterMatch = activeFilterType === 'all' ||
            (activeFilterType === 'location' && storeArea === activeFilterValue.toLowerCase()) ||
            (activeFilterType === 'category' && storeCategories.indexOf(parseInt(activeFilterValue, 10)) !== -1);

        // Search matches the shop name, its Building text, its Stall
        // Number (e.g. "Stall # 4 & 5 – 18"), or its phone number — not
        // the map area, which is the physical building/section used by
        // the filters above, a different thing from the free-text
        // Building/Stall Number fields.
        var q = searchQuery.toLowerCase();
        var srchMatch = q === '' ||
            (store.title    || '').toLowerCase().indexOf(q) !== -1 ||
            (store.location || '').toLowerCase().indexOf(q) !== -1 ||
            (store.stall    || '').toLowerCase().indexOf(q) !== -1 ||
            (store.phone    || '').toLowerCase().indexOf(q) !== -1;

        return filterMatch && srchMatch;
    }

    // Renders the next BATCH_SIZE not-yet-rendered items from the active
    // list (filteredStores when a search/filter is active, otherwise the
    // full storesData) and appends them before the scroll sentinel.
    function renderNextBatch() {
        var source = filteredStores !== null ? filteredStores : storesData;
        if (renderedCount >= source.length) return;

        var next = source.slice(renderedCount, renderedCount + BATCH_SIZE);
        var $frag = $(document.createDocumentFragment());
        next.forEach(function (store) { $frag.append(renderStoreItem(store)); });
        $sentinel.before($frag);
        renderedCount += next.length;
    }

    // Re-runs the active filter/search from scratch: clears rendered cards
    // and renders just the first batch of matches, ready to grow via
    // renderNextBatch() as the user scrolls.
    function filterStores() {
        var isDefault = activeFilterType === 'all' && searchQuery === '';
        filteredStores = isDefault ? null : storesData.filter(storeMatches);

        $storesList.find('.store-item').remove();
        $('.no-results-found').remove();
        renderedCount = 0;
        renderNextBatch();

        var total = filteredStores !== null ? filteredStores.length : storesData.length;
        if (total === 0) {
            $storesList.append('<p class="no-results-found">No stores match your filters.</p>');
        }
    }

    // Clicking a building on the map still filters the list down to that
    // location — unrelated to the category dropdown above the list, which is
    // a separate filter axis. Selecting a location this way resets the
    // dropdown back to "All Shops" since the two aren't shown combined.
    function filterToArea(areaName) {
        activeFilterType  = 'location';
        activeFilterValue = areaName;
        $('#store-category-filter').val('');
        filterStores();
    }

    // Grow the visible list as the user scrolls near the bottom, instead of
    // rendering all 800+ cards up front.
    if ('IntersectionObserver' in window) {
        var loadMoreObserver = new IntersectionObserver(function (entries) {
            if (entries[0].isIntersecting) renderNextBatch();
        }, { root: $storesList[0], rootMargin: '200px' });
        loadMoreObserver.observe($sentinel[0]);
    }

    // ─────────────────────────────────────────────────────────
    // SVG ZONE INTERACTIONS
    // ─────────────────────────────────────────────────────────
    $svg.on('mouseenter', '.area-zone', function () {
        setHighlight($(this).attr('data-area'), true);
    });

    $svg.on('mouseleave', '.area-zone', function () {
        if ($('.store-item.active').length) return;
        setHighlight($(this).attr('data-area'), false);
    });

    $svg.on('click', '.area-zone', function () {
        if (didDrag) return; // ignore mouseup from a drag
        var area = $(this).attr('data-area');
        filterToArea(area);
        setHighlight(area, true);
    });

    $svg.on('keydown', '.area-zone', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); $(this).trigger('click');
        }
    });

    // ─────────────────────────────────────────────────────────
    // CATEGORY DROPDOWN (custom-styled button + listbox)
    // ─────────────────────────────────────────────────────────
    // Purely a visual replacement for the native <select> below — every
    // selection here just sets that <select>'s value and fires its native
    // 'change' event, so the actual filtering logic right after this block
    // needs no changes at all.
    var $categoryDropdown = $('#storeCategoryDropdown');
    var $categoryToggle   = $('#storeCategoryToggle');
    var $categoryMenu     = $('#storeCategoryMenu');
    var $categoryNative   = $('#store-category-filter');

    function closeCategoryDropdown() {
        $categoryDropdown.removeClass('is-open');
        $categoryToggle.attr('aria-expanded', 'false');
    }

    function openCategoryDropdown() {
        $categoryDropdown.addClass('is-open');
        $categoryToggle.attr('aria-expanded', 'true');
    }

    $categoryToggle.on('click', function (e) {
        e.stopPropagation();
        $categoryDropdown.hasClass('is-open') ? closeCategoryDropdown() : openCategoryDropdown();
    });

    $categoryMenu.on('click', '.store-category-dropdown__option', function () {
        var $option = $(this);
        var value   = $option.data('value') || '';

        $categoryMenu.find('.store-category-dropdown__option')
            .removeClass('is-selected').attr('aria-selected', 'false');
        $option.addClass('is-selected').attr('aria-selected', 'true');
        $categoryToggle.find('.store-category-dropdown__label').text($option.text());

        $categoryNative.val(value).trigger('change');
        closeCategoryDropdown();
        $categoryToggle.trigger('focus');
    });

    // Click anywhere outside closes it
    $(document).on('click', function (e) {
        if ($categoryDropdown.hasClass('is-open') && !$(e.target).closest($categoryDropdown).length) {
            closeCategoryDropdown();
        }
    });

    // Minimal keyboard support: the button opens the list and hands focus to
    // it; arrow keys move between options; Enter/Space selects; Escape closes.
    $categoryToggle.on('keydown', function (e) {
        if (e.key === 'Escape') { closeCategoryDropdown(); return; }
        if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            openCategoryDropdown();
            $categoryMenu.find('.store-category-dropdown__option').first().trigger('focus');
        }
    });

    $categoryMenu.on('keydown', '.store-category-dropdown__option', function (e) {
        var $options = $categoryMenu.find('.store-category-dropdown__option');
        var index    = $options.index(this);
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            $options.eq(Math.min(index + 1, $options.length - 1)).trigger('focus');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            $options.eq(Math.max(index - 1, 0)).trigger('focus');
        } else if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            $(this).trigger('click');
        } else if (e.key === 'Escape') {
            closeCategoryDropdown();
            $categoryToggle.trigger('focus');
        }
    });

    // ─────────────────────────────────────────────────────────
    // CATEGORY FILTER
    // ─────────────────────────────────────────────────────────
    $('#store-category-filter').on('change', function () {
        var val = $(this).val();
        activeFilterType  = val === '' ? 'all' : 'category';
        activeFilterValue = val;
        filterStores();
        clearAllHighlights();
        vb = { x: 0, y: 0, w: VB_W, h: VB_H };
        applyViewBox();
    });

    // ─────────────────────────────────────────────────────────
    // SEARCH
    // ─────────────────────────────────────────────────────────
    $('#store-search').on('keyup', function () {
        searchQuery = $(this).val();
        filterStores();
    });

    // ─────────────────────────────────────────────────────────
    // STORE LIST ↔ MAP CROSS-LINK
    // ─────────────────────────────────────────────────────────
    $(document).on('mouseenter', '.store-item', function () {
        var area = $(this).attr('data-map-area');
        if (area) setHighlight(area, true);
        $(this).addClass('active');
    }).on('mouseleave', '.store-item', function () {
        $(this).removeClass('active');
        if (activeFilterType !== 'location') {
            var area = $(this).attr('data-map-area');
            if (area) setHighlight(area, false);
        }
    }).on('click', '.store-item', function () {
        var area = $(this).attr('data-map-area');
        if (area) setHighlight(area, true);
    });

    // ─────────────────────────────────────────────────────────
    // IMAGE PROTECTION — block right-click save and drag on store
    // logos and the floor plan map (delegated so it still applies
    // after the store list re-renders on search/filter)
    // ─────────────────────────────────────────────────────────
    $(document).on('contextmenu dragstart', '.mall-directory-container img, .mall-directory-container svg image', function (e) {
        e.preventDefault();
    });

});
