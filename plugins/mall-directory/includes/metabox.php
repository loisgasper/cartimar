<?php
/**
 * Admin Metabox for Store Details
 */

if (!defined('ABSPATH')) {
    exit;
}

function mall_dir_add_metabox() {
    add_meta_box(
        'md_store_details',
        __('Store Details', 'mall-directory'),
        'mall_dir_metabox_callback',
        'md_store',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'mall_dir_add_metabox');

function mall_dir_metabox_callback($post) {
    wp_nonce_field('mall_dir_metabox_nonce', 'mall_dir_nonce');
    
    // Get existing values
    $logo_id = get_post_meta($post->ID, '_md_store_logo', true);
    $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
    $map_x = get_post_meta($post->ID, '_md_store_map_x', true);
    $map_y = get_post_meta($post->ID, '_md_store_map_y', true);
    
    ?>
    <div class="mall-dir-metabox">
        
        <!-- Store Logo -->
        <div class="mall-dir-field">
            <label for="md_store_logo"><strong><?php _e('Store Logo', 'mall-directory'); ?></strong></label>
            <div class="logo-upload-wrapper">
                <?php if ($logo_url): ?>
                    <img id="mall-dir-logo-preview" src="<?php echo esc_url($logo_url); ?>" style="max-width: 150px; margin-bottom: 10px;">
                    <br>
                <?php else: ?>
                    <div id="mall-dir-logo-preview" style="margin-bottom: 10px;"></div>
                <?php endif; ?>
                <input type="hidden" id="md_store_logo" name="md_store_logo" value="<?php echo esc_attr($logo_id); ?>">
                <button type="button" class="button button-primary" id="mall-dir-logo-upload"><?php _e('Upload Logo', 'mall-directory'); ?></button>
                <?php if ($logo_id): ?>
                    <button type="button" class="button" id="mall-dir-logo-remove"><?php _e('Remove Logo', 'mall-directory'); ?></button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Map Coordinates -->
        <div class="mall-dir-field">
            <label for="md_store_map_x"><strong><?php _e('Position on Map', 'mall-directory'); ?></strong></label>
            <p style="color: #666; margin-top: 0;"><?php _e('Position coordinates (in pixels) on the floor plan', 'mall-directory'); ?></p>
            <div class="coordinates-wrapper">
                <div class="coord-field">
                    <label for="md_store_map_x"><?php _e('X Position:', 'mall-directory'); ?></label>
                    <input type="number" id="md_store_map_x" name="md_store_map_x" value="<?php echo esc_attr($map_x); ?>" placeholder="0" min="0">
                </div>
                <div class="coord-field">
                    <label for="md_store_map_y"><?php _e('Y Position:', 'mall-directory'); ?></label>
                    <input type="number" id="md_store_map_y" name="md_store_map_y" value="<?php echo esc_attr($map_y); ?>" placeholder="0" min="0">
                </div>
            </div>
            <p style="color: #999; font-size: 12px; margin-top: 5px;">
                <?php _e('Tip: Click on the map preview to automatically set coordinates', 'mall-directory'); ?>
            </p>
        </div>

    </div>
    <?php
}

function mall_dir_save_metabox($post_id) {
    if (!isset($_POST['mall_dir_nonce']) || !wp_verify_nonce($_POST['mall_dir_nonce'], 'mall_dir_metabox_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save logo
    if (isset($_POST['md_store_logo'])) {
        update_post_meta($post_id, '_md_store_logo', sanitize_text_field($_POST['md_store_logo']));
    }

    // Save map coordinates
    if (isset($_POST['md_store_map_x'])) {
        update_post_meta($post_id, '_md_store_map_x', sanitize_text_field($_POST['md_store_map_x']));
    }
    if (isset($_POST['md_store_map_y'])) {
        update_post_meta($post_id, '_md_store_map_y', sanitize_text_field($_POST['md_store_map_y']));
    }
}
add_action('save_post_md_store', 'mall_dir_save_metabox');
