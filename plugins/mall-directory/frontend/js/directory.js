jQuery(document).ready(function ($) {
    var storesData      = JSON.parse($('#storesData').text());
    var activeFilterType = 'all';   // 'all' | 'location'
    var activeFilterValue = '';     // area name when type === 'location'
    var searchQuery      = '';
    var map;
    var markerGroup  = L.layerGroup();
    var markers      = {};
    var mapBounds;
    var imgHeight    = 0;
    var floorplanUrl = $('#mallDirectoryMap').data('floorplan');

    var pinSvg = '<svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">'
        + '<path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z'
        + 'm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>';

    // ── Map init ────────────────────────────────────────────
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

        storesData.forEach(function (store) { createMarker(store); });
        markerGroup.addTo(map);
    }

    function createMarker(store) {
        if (!store.x || !store.y) return;

        var inner;
        if (store.logo) {
            inner = '<img src="' + store.logo + '" alt="' + store.title + '">';
        } else {
            inner = pinSvg;
        }

        var icon = L.divIcon({
            className: 'mall-directory-marker' + (store.logo ? '' : ' mall-directory-marker--pin'),
            html: '<div class="leaflet-marker-inner">' + inner + '</div>',
            iconSize:   store.logo ? [48, 48] : [32, 42],
            iconAnchor: store.logo ? [24, 24] : [16, 42],
        });

        var tooltipContent = '<strong>' + store.title + '</strong>';
        if (store.location) tooltipContent += '<br><em>&#9906; ' + store.location + '</em>';
        if (store.phone)    tooltipContent += '<br><em>&#9990; ' + store.phone + '</em>';

        var marker = L.marker([imgHeight - store.y, store.x], { icon: icon, riseOnHover: true });
        marker.bindTooltip(tooltipContent, {
            direction: 'top',
            offset: [0, -10],
            sticky: true,
            opacity: 0.95,
            className: 'mall-directory-tooltip',
        });

        marker.on('mouseover', function () { highlightListItem(store.id, true); });
        marker.on('mouseout',  function () { highlightListItem(store.id, false); });

        markers[store.id] = marker;
        markerGroup.addLayer(marker);
    }

    // ── Filtering ────────────────────────────────────────────
    function filterStores() {
        var visibleCount = 0;

        $('.store-item').each(function () {
            var $item          = $(this);
            var storeName      = $item.attr('data-store-name').toLowerCase();
            var storeId        = $item.attr('data-store-id');
            var storeArea      = ($item.attr('data-map-area') || '').toLowerCase();
            var marker         = markers[storeId];

            var locationMatch = activeFilterType === 'all' ||
                (activeFilterType === 'location' && storeArea === activeFilterValue.toLowerCase());
            var searchMatch   = searchQuery === '' || storeName.indexOf(searchQuery.toLowerCase()) !== -1;
            var isVisible     = locationMatch && searchMatch;

            if (isVisible) {
                $item.show();
                visibleCount++;
                if (marker && !markerGroup.hasLayer(marker)) markerGroup.addLayer(marker);
            } else {
                $item.hide();
                if (marker && markerGroup.hasLayer(marker)) markerGroup.removeLayer(marker);
            }
        });

        if (visibleCount === 0) {
            if (!$('.no-results-found').length) {
                $('#storesList').append('<p class="no-results-found">No stores match your filters.</p>');
            }
        } else {
            $('.no-results-found').remove();
        }
    }

    // ── Highlight cross-link ─────────────────────────────────
    function highlightListItem(storeId, active) {
        var $item = $('.store-item[data-store-id="' + storeId + '"]');
        if (active) { $item.addClass('active'); } else { $item.removeClass('active'); }
    }

    // ── Interactions ─────────────────────────────────────────
    function setupInteractions() {
        // Filter pills
        $('.category-pill').on('click', function () {
            $('.category-pill').removeClass('is-active');
            $(this).addClass('is-active');
            activeFilterType  = $(this).data('filter-type');
            activeFilterValue = $(this).data('location') || '';
            filterStores();
        });

        // Search
        $('#store-search').on('keyup', function () {
            searchQuery = $(this).val();
            filterStores();
        });

        // Store list ↔ map cross-highlight
        $(document).on('mouseenter', '.store-item', function () {
            var storeId = $(this).attr('data-store-id');
            var marker  = markers[storeId];
            if (marker) marker.openTooltip();
            $(this).addClass('active');
        }).on('mouseleave', '.store-item', function () {
            var storeId = $(this).attr('data-store-id');
            var marker  = markers[storeId];
            if (marker) marker.closeTooltip();
            $(this).removeClass('active');
        }).on('click', '.store-item', function () {
            var storeId = $(this).attr('data-store-id');
            var marker  = markers[storeId];
            if (marker && map) {
                map.setView(marker.getLatLng(), map.getZoom());
                marker.openTooltip();
            }
        });
    }

    // ── Boot ─────────────────────────────────────────────────
    function loadFloorplan() {
        var image  = new Image();
        image.onload = function () {
            initializeMap(image.naturalWidth, image.naturalHeight);
            setupInteractions();
        };
        image.src = floorplanUrl;
    }

    loadFloorplan();
});
