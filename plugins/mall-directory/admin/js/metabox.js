jQuery(document).ready(function($) {
    
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
            button: {
                text: 'Choose Logo'
            },
            multiple: false
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#md_store_logo').val(attachment.id);
            $('#mall-dir-logo-preview').html('<img src="' + attachment.url + '" style="max-width: 150px;">');
            
            // Show remove button if not already visible
            if ($('#mall-dir-logo-remove').length === 0) {
                $('#mall-dir-logo-upload').after('<button type="button" class="button" id="mall-dir-logo-remove">Remove Logo</button>');
            }
        });
        
        mediaUploader.open();
    });
    
    // Logo Remove Handler
    $(document).on('click', '#mall-dir-logo-remove', function(e) {
        e.preventDefault();
        $('#md_store_logo').val('');
        $('#mall-dir-logo-preview').html('');
        $(this).remove();
    });
});
