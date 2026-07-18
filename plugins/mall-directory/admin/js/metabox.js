jQuery(document).ready(function($) {

    // Sync Store Name → WP post title (classic editor)
    $('#md_store_name').on('input', function() {
        var name = $(this).val();
        $('#title').val(name);
        $('#title-prompt-text').hide();
    });

    // Logo Upload Handler
    var mediaUploader;

    $('#mall-dir-logo-upload').on('click', function(e) {
        e.preventDefault();

        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Select Store Logo',
            button: { text: 'Choose Logo' },
            multiple: false,
        });

        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#md_store_logo').val(attachment.id);
            $('#mall-dir-logo-preview').html('<img src="' + attachment.url + '" style="max-width: 150px;">');

            if ($('#mall-dir-logo-remove').length === 0) {
                $('#mall-dir-logo-upload').after('<button type="button" class="button" id="mall-dir-logo-remove">Remove Logo</button>');
            }
        });

        mediaUploader.open();
    });

    $(document).on('click', '#mall-dir-logo-remove', function(e) {
        e.preventDefault();
        $('#md_store_logo').val('');
        $('#mall-dir-logo-preview').html('');
        $(this).remove();
    });

    // Area Dropdown → hidden X/Y + highlighted zone + pin on the embedded map
    //
    // The floor plan preview is the exact same SVG partial (map, zones, pin,
    // and their CSS) the live site's interactive map uses, included directly
    // by metabox.php — so picking a location here reuses the same
    // findZone/showPin/setHighlight approach as frontend/js/directory.js,
    // instead of a separate placeholder-dot implementation to keep in sync.
    var areas = (typeof mallDirData !== 'undefined') ? mallDirData.areas : [];
    var $svg  = $('#cartimar-map-svg');
    var $pin  = $('#map-pin');
    var pinBounceEl  = document.querySelector('#map-pin .map-pin__bounce');
    var pinArea      = null;
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

    function showPin(areaName) {
        var zone = findZone(areaName);
        var rect = zone && zone.querySelector('.area-fill');
        if (!rect) return;
        var bbox = rect.getBBox();
        var cx = bbox.x + bbox.width * 0.28;
        var cy = bbox.y + bbox.height * 0.2;
        $pin.attr('transform', 'translate(' + cx + ',' + cy + ')');
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

    function setHighlight(areaName) {
        if (highlightedArea) {
            var prev = findZone(highlightedArea);
            if (prev) $(prev).removeClass('is-highlighted');
            highlightedArea = null;
        }
        if (!areaName) { hidePin(); return; }
        var zone = findZone(areaName);
        if (!zone) { hidePin(); return; }
        $(zone).addClass('is-highlighted');
        highlightedArea = areaName;
        showPin(areaName);
    }

    function applyStoredArea() {
        setHighlight($('#md_store_map_area').val());
    }

    $('#md_store_map_area').on('change', function() {
        var selectedName = $(this).val();
        var area = null;
        for (var i = 0; i < areas.length; i++) {
            if (areas[i].name === selectedName) { area = areas[i]; break; }
        }
        $('#md_store_map_x').val(area ? area.x : '');
        $('#md_store_map_y').val(area ? area.y : '');
        setHighlight(selectedName);
    });

    // Clicking a zone directly on the preview selects it in the dropdown too
    $svg.on('click', '.area-zone', function () {
        var areaName = $(this).attr('data-area');
        $('#md_store_map_area').val(areaName).trigger('change');
    });
    $svg.on('keydown', '.area-zone', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault(); $(this).trigger('click');
        }
    });

    // Highlight + drop the pin on load if an area is already saved
    applyStoredArea();
});
