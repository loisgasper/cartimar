jQuery(document).ready(function ($) {
    var storesData       = JSON.parse($('#storesData').text());
    var activeFilterType  = 'all';
    var activeFilterValue = '';
    var searchQuery       = '';
    var map;
    var imgHeight     = 0;
    var mapBounds;
    var floorplanUrl  = $('#mallDirectoryMap').data('floorplan');

    // ── Area definitions ─────────────────────────────────────
    // Pixel coords measured from top-left of the 1672×941 floor plan image
    var areaDefs = {
        'Aqualand Alley':                          { x1: 820,  y1: 38,  x2: 934,  y2: 130, color: '#5aaee0' },
        'Greenland Plants and Orchids Center':     { x1: 954,  y1: 38,  x2: 1200, y2: 130, color: '#5aae6e' },
        'Cartimar Villa Village 2':                { x1: 310,  y1: 155, x2: 610,  y2: 258, color: '#e8a030' },
        'Cartimar Villa Village 3':                { x1: 636,  y1: 155, x2: 870,  y2: 258, color: '#8860d0' },
        'Cartimar Villa Village 1 (Pet Shops)':   { x1: 900,  y1: 160, x2: 1188, y2: 228, color: '#e0607a' },
        'Cartimar Villa Village 4 (Pet Shops)':   { x1: 900,  y1: 246, x2: 1188, y2: 314, color: '#e0607a' },
        'Plaza (Various Shops)':                   { x1: 300,  y1: 300, x2: 578,  y2: 442, color: '#3a72c8' },
        'Admin':                                   { x1: 594,  y1: 300, x2: 656,  y2: 442, color: '#e06845' },
        'Food Court':                              { x1: 680,  y1: 300, x2: 762,  y2: 442, color: '#e0607a' },
        'Save More Grocery Store':                 { x1: 778,  y1: 300, x2: 1192, y2: 442, color: '#7850c8' },
        'Grains & Grocery':                        { x1: 300,  y1: 497, x2: 650,  y2: 667, color: '#e8a030' },
        'Cartimar Main Building':                  { x1: 665,  y1: 497, x2: 1192, y2: 667, color: '#28a8a5' },
        'Cartimar Carpark and Fresh Food Plaza':   { x1: 17,   y1: 498, x2: 258,  y2: 582, color: '#8848b8' },
        'Gateway (Upper)':                         { x1: 1300, y1: 291, x2: 1416, y2: 442, color: '#9a8040' },
        'Gateway (Lower)':                         { x1: 1300, y1: 497, x2: 1416, y2: 667, color: '#9a8040' },
    };

    var areaRects       = {};   // areaName → L.rectangle
    var highlightedArea = null; // currently highlighted area name

    // ── Helpers ───────────────────────────────────────────────
    function toLeaflet(x, y) {
        return [imgHeight - y, x];
    }

    function getBoundsForArea(def) {
        return [toLeaflet(def.x1, def.y2), toLeaflet(def.x2, def.y1)];
    }

    // ── Map init ──────────────────────────────────────────────
    function initializeMap(imageWidth, imageHeight) {
        imgHeight = imageHeight;
        mapBounds = [[0, 0], [imageHeight, imageWidth]];

        map = L.map('mallDirectoryMap', {
            crs: L.CRS.Simple,
            minZoom: -2,
            maxZoom: 2,
            zoomControl: true,
            attributionControl: false,
        });

        L.imageOverlay(floorplanUrl, mapBounds).addTo(map);
        map.fitBounds(mapBounds);
        map.setMaxBounds(mapBounds);

        // Coordinate readout — hover the map to see pixel coords
        var $coords = $('<div class="map-coord-readout">x: — &nbsp; y: —</div>').appendTo('#mallDirectoryMap');
        map.on('mousemove', function (e) {
            var x = Math.round(e.latlng.lng);
            var y = Math.round(imgHeight - e.latlng.lat);
            $coords.text('x: ' + x + '   y: ' + y);
        });
        map.on('mouseleave', function () { $coords.text('x: —   y: —'); });

        createAreaRects();
        setupInteractions();
    }

    function createAreaRects() {
        // Collect which areas have stores
        var areasWithStores = {};
        storesData.forEach(function (s) {
            if (s.map_area) areasWithStores[s.map_area] = true;
        });

        Object.keys(areaDefs).forEach(function (name) {
            var def    = areaDefs[name];
            var bounds = getBoundsForArea(def);
            var hasStores = !!areasWithStores[name];

            var rect = L.rectangle(bounds, {
                color:       def.color,
                weight:      0,
                fillColor:   def.color,
                fillOpacity: 0,
                opacity:     0,
                interactive: hasStores,
                className:   'area-rect',
            }).addTo(map);

            if (hasStores) {
                rect.bindTooltip('<strong>' + name + '</strong>', {
                    sticky:    true,
                    className: 'mall-directory-tooltip',
                    direction: 'top',
                });

                rect.on('mouseover', function () { setHighlight(name, true); });
                rect.on('mouseout',  function () {
                    // Only un-highlight if no list item is active
                    if (!$('.store-item.active').length) setHighlight(name, false);
                });
                rect.on('click', function () {
                    filterToArea(name);
                });
            }

            areaRects[name] = rect;
        });
    }

    function setHighlight(areaName, on) {
        // Always clear the previously highlighted area first
        if (highlightedArea && highlightedArea !== areaName) {
            var prev = areaRects[highlightedArea];
            if (prev) prev.setStyle({ fillOpacity: 0, weight: 0, opacity: 0 });
            highlightedArea = null;
        }

        var rect = areaRects[areaName];
        if (!rect) return;

        if (on) {
            rect.setStyle({ fillOpacity: 0.52, weight: 2.5, opacity: 0.9 });
            highlightedArea = areaName;
        } else {
            rect.setStyle({ fillOpacity: 0, weight: 0, opacity: 0 });
            if (highlightedArea === areaName) highlightedArea = null;
        }
    }

    function clearAllHighlights() {
        Object.keys(areaRects).forEach(function (name) {
            areaRects[name].setStyle({ fillOpacity: 0, weight: 0, opacity: 0 });
        });
        highlightedArea = null;
    }

    function zoomToArea(areaName) {
        var def = areaDefs[areaName];
        if (!def || !map) return;
        map.fitBounds(getBoundsForArea(def), { padding: [40, 40], maxZoom: 1 });
    }

    function filterToArea(areaName) {
        // Activate the matching pill
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

    // ── Filtering ─────────────────────────────────────────────
    function filterStores() {
        var visibleCount = 0;

        $('.store-item').each(function () {
            var $item     = $(this);
            var storeName = $item.attr('data-store-name').toLowerCase();
            var storeArea = ($item.attr('data-map-area') || '').toLowerCase();

            var locationMatch = activeFilterType === 'all' ||
                (activeFilterType === 'location' && storeArea === activeFilterValue.toLowerCase());
            var searchMatch   = searchQuery === '' ||
                storeName.indexOf(searchQuery.toLowerCase()) !== -1;
            var isVisible     = locationMatch && searchMatch;

            $item.toggle(isVisible);
            if (isVisible) visibleCount++;
        });

        if (visibleCount === 0) {
            if (!$('.no-results-found').length) {
                $('#storesList').append('<p class="no-results-found">No stores match your filters.</p>');
            }
        } else {
            $('.no-results-found').remove();
        }
    }

    // ── Interactions ──────────────────────────────────────────
    function setupInteractions() {
        // Category / location pills
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
                map.fitBounds(mapBounds);
            }
        });

        // Search
        $('#store-search').on('keyup', function () {
            searchQuery = $(this).val();
            filterStores();
        });

        // Store list ↔ map area cross-link
        $(document).on('mouseenter', '.store-item', function () {
            var area = $(this).attr('data-map-area');
            if (area) setHighlight(area, true);
            $(this).addClass('active');
        }).on('mouseleave', '.store-item', function () {
            var area = $(this).attr('data-map-area');
            if (area) setHighlight(area, false);
            $(this).removeClass('active');
        }).on('click', '.store-item', function () {
            var area = $(this).attr('data-map-area');
            if (area) {
                setHighlight(area, true);
                zoomToArea(area);
            }
        });
    }

    // ── Boot ──────────────────────────────────────────────────
    function loadFloorplan() {
        var image = new Image();
        image.onload = function () {
            initializeMap(image.naturalWidth, image.naturalHeight);
        };
        image.src = floorplanUrl;
    }

    loadFloorplan();
});
