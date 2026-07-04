jQuery(document).ready(function ($) {

    // ─────────────────────────────────────────────────────────
    // DATA
    // ─────────────────────────────────────────────────────────
    var storesData = JSON.parse($('#storesData').text());

    // Building bounding boxes (SVG-coordinate space) used for "zoom to area"
    var areaDefs = {
        'Aqualand Alley':                           { x1: 820,  y1: 38,  x2: 934,  y2: 130  },
        'Greenland Plants and Orchids Center':      { x1: 954,  y1: 38,  x2: 1200, y2: 130  },
        'Cartimar Villa Village 2':                 { x1: 310,  y1: 155, x2: 610,  y2: 258  },
        'Cartimar Villa Village 3':                 { x1: 636,  y1: 155, x2: 870,  y2: 258  },
        'Cartimar Villa Village 1 (Pet Shops)':    { x1: 900,  y1: 160, x2: 1188, y2: 228  },
        'Cartimar Villa Village 4 (Pet Shops)':    { x1: 900,  y1: 246, x2: 1188, y2: 314  },
        'Plaza (Various Shops)':                    { x1: 300,  y1: 300, x2: 578,  y2: 442  },
        'Admin':                                    { x1: 594,  y1: 300, x2: 656,  y2: 442  },
        'Food Court':                               { x1: 680,  y1: 300, x2: 762,  y2: 442  },
        'Save More Grocery Store':                  { x1: 778,  y1: 300, x2: 1192, y2: 442  },
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

    // ── Mouse wheel zoom ──────────────────────────────────────
    svgEl.addEventListener('wheel', function (e) {
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
        } else {
            $(zone).removeClass('is-highlighted');
            if (highlightedArea === areaName) highlightedArea = null;
        }
    }

    function clearAllHighlights() {
        $svg.find('.area-zone').removeClass('is-highlighted');
        highlightedArea = null;
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
    // FILTERING
    // ─────────────────────────────────────────────────────────
    function filterStores() {
        var count = 0;
        $('.store-item').each(function () {
            var $item     = $(this);
            var storeName = $item.attr('data-store-name').toLowerCase();
            var storeArea = ($item.attr('data-map-area') || '').toLowerCase();
            var locMatch  = activeFilterType === 'all' ||
                            (activeFilterType === 'location' && storeArea === activeFilterValue.toLowerCase());
            var srchMatch = searchQuery === '' || storeName.indexOf(searchQuery.toLowerCase()) !== -1;
            var visible   = locMatch && srchMatch;
            $item.toggle(visible);
            if (visible) count++;
        });
        if (count === 0) {
            if (!$('.no-results-found').length) {
                $('#storesList').append('<p class="no-results-found">No stores match your filters.</p>');
            }
        } else {
            $('.no-results-found').remove();
        }
    }

    function filterToArea(areaName) {
        $('.category-pill').each(function () {
            if ($(this).data('filter-type') === 'location' &&
                $(this).data('location') === areaName) {
                $('.category-pill').removeClass('is-active');
                $(this).addClass('is-active');
                activeFilterType  = 'location';
                activeFilterValue = areaName;
                filterStores();
            }
        });
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
    // CATEGORY PILLS
    // ─────────────────────────────────────────────────────────
    $('.category-pill').on('click', function () {
        $('.category-pill').removeClass('is-active');
        $(this).addClass('is-active');
        activeFilterType  = $(this).data('filter-type');
        activeFilterValue = $(this).data('location') || '';
        filterStores();
        clearAllHighlights();
        if (activeFilterType === 'location') {
            setHighlight(activeFilterValue, true);
        } else {
            vb = { x: 0, y: 0, w: VB_W, h: VB_H };
            applyViewBox();
        }
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

});
