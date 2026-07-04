<?php
/**
 * Frontend Shortcode and Display
 */

if (!defined('ABSPATH')) {
    exit;
}

function mall_dir_directory_shortcode($atts) {
    $atts = shortcode_atts([
        'floorplan' => MALL_DIR_PLUGIN_URL . 'assets/images/cartimar-shop-directory-map.jpg',
    ], $atts);

    ob_start();
    
    // Include the frontend template
    include MALL_DIR_PLUGIN_DIR . 'frontend/views/directory-shortcode.php';
    
    return ob_get_clean();
}
add_shortcode('mall_directory', 'mall_dir_directory_shortcode');
