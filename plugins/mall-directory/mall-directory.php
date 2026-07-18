<?php
/**
 * Plugin Name: Mall Directory
 * Plugin URI: https://cartimar.local
 * Description: Interactive mall directory with store listings and floor plan map
 * Version: 1.0.0
 * Author: Cartimar Team
 * License: GPL v2 or later
 * Text Domain: mall-directory
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MALL_DIR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MALL_DIR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MALL_DIR_VERSION', '1.0.1');

// Include required files
require_once MALL_DIR_PLUGIN_DIR . 'includes/cpt-register.php';
require_once MALL_DIR_PLUGIN_DIR . 'includes/metabox.php';
require_once MALL_DIR_PLUGIN_DIR . 'includes/shortcode.php';

function mall_dir_get_map_areas() {
    return [
        ['name' => 'Plaza (Various Shops)',               'x' => 514,  'y' => 450],
        ['name' => 'Admin',                               'x' => 714,  'y' => 450],
        ['name' => 'Food Court',                          'x' => 832,  'y' => 450],
        ['name' => 'Save More Grocery Store',             'x' => 1127, 'y' => 450],
        ['name' => 'Grains & Grocery',                    'x' => 526,  'y' => 703],
        ['name' => 'Cartimar Main Building',              'x' => 1127, 'y' => 703],
        ['name' => 'Cartimar Villa Village 1 (Pet Shops)', 'x' => 1219, 'y' => 242],
        ['name' => 'Cartimar Villa Village 2',            'x' => 526,  'y' => 251],
        ['name' => 'Cartimar Villa Village 3',            'x' => 867,  'y' => 251],
        ['name' => 'Cartimar Villa Village 4 (Pet Shops)', 'x' => 1219, 'y' => 330],
        ['name' => 'Aqualand Alley',                      'x' => 988,  'y' => 98],
        ['name' => 'Greenland Plants and Orchids Center', 'x' => 1260, 'y' => 98],
        ['name' => 'Cartimar Carpark and Fresh Food Plaza', 'x' => 179,  'y' => 630],
        ['name' => 'Gateway (Upper)',                       'x' => 1390, 'y' => 362],
        ['name' => 'Gateway (Lower)',                       'x' => 1390, 'y' => 592],
        // Parking / road / hallway areas — plain text labels on the map, not
        // a distinct shop, but still selectable here in case a store (e.g. a
        // stall in a transitional space) ever needs to be placed at one.
        ['name' => 'Road Lot & Parking (Open)',              'x' => 1151, 'y' => 164],
        ['name' => 'Road Lot & Parking (Roofed)',            'x' => 860,  'y' => 310],
        ['name' => 'Hallway',                                'x' => 1200, 'y' => 268],
        ['name' => 'Cartimar Road & Parking (Open)',         'x' => 540,  'y' => 310],
        ['name' => 'Cartimar Ave & Parking (Open) (Left)',   'x' => 567,  'y' => 523],
        ['name' => 'Cartimar Ave & Parking (Open) (Right)',  'x' => 1090, 'y' => 523],
        ['name' => 'Existing Road',                          'x' => 180,  'y' => 684],
    ];
}

// A file's last-modified time as its cache-busting version — unlike the
// static MALL_DIR_VERSION constant, this changes automatically the moment
// the file is edited, so browsers (and any host-level page cache) can never
// keep serving a stale copy after a deploy the way the old hardcoded version
// let them.
function mall_dir_asset_version($relative_path) {
    $full_path = MALL_DIR_PLUGIN_DIR . $relative_path;
    return file_exists($full_path) ? filemtime($full_path) : MALL_DIR_VERSION;
}

// Enqueue admin scripts and styles
function mall_dir_admin_enqueue_scripts($hook) {
    global $post_type;

    if ($post_type !== 'md_store') {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('mall-dir-metabox', MALL_DIR_PLUGIN_URL . 'admin/js/metabox.js', ['jquery'], mall_dir_asset_version('admin/js/metabox.js'), true);
    wp_localize_script('mall-dir-metabox', 'mallDirData', [
        'areas' => mall_dir_get_map_areas(),
    ]);
    wp_enqueue_style('mall-dir-admin-css', MALL_DIR_PLUGIN_URL . 'admin/css/metabox.css', [], mall_dir_asset_version('admin/css/metabox.css'));
}
add_action('admin_enqueue_scripts', 'mall_dir_admin_enqueue_scripts');

// Enqueue frontend scripts and styles
function mall_dir_frontend_enqueue_scripts() {
    wp_enqueue_script('mall-dir-frontend', MALL_DIR_PLUGIN_URL . 'frontend/js/directory.js', ['jquery'], mall_dir_asset_version('frontend/js/directory.js'), true);
    wp_enqueue_style('mall-dir-frontend-css', MALL_DIR_PLUGIN_URL . 'frontend/css/directory.css', [], mall_dir_asset_version('frontend/css/directory.css'));
}
add_action('wp_enqueue_scripts', 'mall_dir_frontend_enqueue_scripts');

// Plugin activation hook
function mall_dir_activate() {
    // Register CPT on activation to ensure rewrite rules are flushed
    mall_dir_register_cpt();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'mall_dir_activate');

// Plugin deactivation hook
function mall_dir_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'mall_dir_deactivate');
