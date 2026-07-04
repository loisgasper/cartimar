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

    // Area Dropdown → hidden X/Y + floor plan marker
    var areas = (typeof mallDirData !== 'undefined') ? mallDirData.areas : [];
    var floorplan = $('#mall-dir-floorplan-preview')[0];
    var marker    = $('#mall-dir-floorplan-marker');

    function placeMarker(naturalX, naturalY) {
        if (!floorplan || !floorplan.complete || !floorplan.naturalWidth) return;
        var scaleX = floorplan.clientWidth  / floorplan.naturalWidth;
        var scaleY = floorplan.clientHeight / floorplan.naturalHeight;
        marker.css({
            left:    (naturalX * scaleX) + 'px',
            top:     (naturalY * scaleY) + 'px',
            opacity: 1,
        });
    }

    function clearMarker() {
        marker.css('opacity', 0);
    }

    function applyStoredArea() {
        var selectedName = $('#md_store_map_area').val();
        if (!selectedName) {
            clearMarker();
            return;
        }
        var area = null;
        for (var i = 0; i < areas.length; i++) {
            if (areas[i].name === selectedName) { area = areas[i]; break; }
        }
        if (area) {
            placeMarker(area.x, area.y);
        }
    }

    $('#md_store_map_area').on('change', function() {
        var selectedName = $(this).val();
        var area = null;
        for (var i = 0; i < areas.length; i++) {
            if (areas[i].name === selectedName) { area = areas[i]; break; }
        }
        if (area) {
            $('#md_store_map_x').val(area.x);
            $('#md_store_map_y').val(area.y);
            placeMarker(area.x, area.y);
        } else {
            $('#md_store_map_x').val('');
            $('#md_store_map_y').val('');
            clearMarker();
        }
    });

    // Place marker on load if an area is already saved
    if ($('#mall-dir-floorplan-preview').complete) {
        applyStoredArea();
    } else {
        $('#mall-dir-floorplan-preview').on('load', applyStoredArea);
    }
});
