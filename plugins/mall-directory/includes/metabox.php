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
    $store_name     = get_post_meta($post->ID, '_md_store_name', true);
    $store_location = get_post_meta($post->ID, '_md_store_location', true);
    $store_phone    = get_post_meta($post->ID, '_md_store_phone', true);
    $logo_id        = get_post_meta($post->ID, '_md_store_logo', true);
    $logo_url       = $logo_id ? wp_get_attachment_url($logo_id) : '';
    $map_x          = get_post_meta($post->ID, '_md_store_map_x', true);
    $map_y          = get_post_meta($post->ID, '_md_store_map_y', true);
    $map_area       = get_post_meta($post->ID, '_md_store_map_area', true);

    ?>
    <div class="mall-dir-metabox">

        <!-- Store Name -->
        <div class="mall-dir-field">
            <label for="md_store_name"><strong><?php _e('Store Name', 'mall-directory'); ?></strong></label>
            <input type="text" id="md_store_name" name="md_store_name" value="<?php echo esc_attr($store_name); ?>" placeholder="<?php _e('e.g. Manila Aquarium & Pet Shop', 'mall-directory'); ?>" class="mall-dir-text-input">
        </div>

        <!-- Shop Location -->
        <div class="mall-dir-field">
            <label for="md_store_location"><strong><?php _e('Shop Location', 'mall-directory'); ?></strong></label>
            <input type="text" id="md_store_location" name="md_store_location" value="<?php echo esc_attr($store_location); ?>" placeholder="<?php _e('e.g. 2nd Floor, Section A', 'mall-directory'); ?>" class="mall-dir-text-input">
        </div>

        <!-- Contact Number -->
        <div class="mall-dir-field">
            <label for="md_store_phone"><strong><?php _e('Contact Number', 'mall-directory'); ?></strong></label>
            <input type="text" id="md_store_phone" name="md_store_phone" value="<?php echo esc_attr($store_phone); ?>" placeholder="<?php _e('e.g. 0995 574 4839', 'mall-directory'); ?>" class="mall-dir-text-input">
        </div>

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

        <!-- Map Location -->
        <div class="mall-dir-field">
            <label for="md_store_map_area"><strong><?php _e('Location on Map', 'mall-directory'); ?></strong></label>
            <p style="color: #666; margin-top: 0;"><?php _e('Select the building or section where this store is located.', 'mall-directory'); ?></p>

            <select id="md_store_map_area" name="md_store_map_area" class="mall-dir-area-select">
                <option value=""><?php _e('-- Select a Location --', 'mall-directory'); ?></option>
                <?php foreach (mall_dir_get_map_areas() as $area): ?>
                    <option value="<?php echo esc_attr($area['name']); ?>" <?php selected($map_area, $area['name']); ?>>
                        <?php echo esc_html($area['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="hidden" id="md_store_map_x" name="md_store_map_x" value="<?php echo esc_attr($map_x); ?>">
            <input type="hidden" id="md_store_map_y" name="md_store_map_y" value="<?php echo esc_attr($map_y); ?>">

            <div class="mall-dir-map-picker">
                <label><strong><?php _e('Floor Plan Preview', 'mall-directory'); ?></strong></label>
                <div class="mall-dir-floorplan-wrapper">
                    <img id="mall-dir-floorplan-preview" src="<?php echo esc_url(MALL_DIR_PLUGIN_URL . 'assets/images/cartimar-shop-directory-map.jpg'); ?>" alt="<?php _e('Floor Plan Preview', 'mall-directory'); ?>">
                    <div id="mall-dir-floorplan-marker" class="mall-dir-floorplan-marker"></div>
                </div>
                <p style="color: #999; font-size: 12px; margin-top: 8px;">
                    <?php _e('The marker updates automatically when you select a location above.', 'mall-directory'); ?>
                </p>
            </div>
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

    // Save store name, location, phone — and sync name to WP post title
    if (isset($_POST['md_store_name'])) {
        $store_name = sanitize_text_field($_POST['md_store_name']);
        update_post_meta($post_id, '_md_store_name', $store_name);

        if (!empty($store_name)) {
            remove_action('save_post_md_store', 'mall_dir_save_metabox');
            wp_update_post(['ID' => $post_id, 'post_title' => $store_name]);
            add_action('save_post_md_store', 'mall_dir_save_metabox');
        }
    }
    if (isset($_POST['md_store_location'])) {
        update_post_meta($post_id, '_md_store_location', sanitize_text_field($_POST['md_store_location']));
    }
    if (isset($_POST['md_store_phone'])) {
        update_post_meta($post_id, '_md_store_phone', sanitize_text_field($_POST['md_store_phone']));
    }

    // Save logo
    if (isset($_POST['md_store_logo'])) {
        update_post_meta($post_id, '_md_store_logo', sanitize_text_field($_POST['md_store_logo']));
    }

    // Save map area and coordinates
    if (isset($_POST['md_store_map_area'])) {
        update_post_meta($post_id, '_md_store_map_area', sanitize_text_field($_POST['md_store_map_area']));
    }
    if (isset($_POST['md_store_map_x'])) {
        update_post_meta($post_id, '_md_store_map_x', sanitize_text_field($_POST['md_store_map_x']));
    }
    if (isset($_POST['md_store_map_y'])) {
        update_post_meta($post_id, '_md_store_map_y', sanitize_text_field($_POST['md_store_map_y']));
    }
}
add_action('save_post_md_store', 'mall_dir_save_metabox');
