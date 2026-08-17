<?php
/**
 * Register Custom Post Type and Taxonomies
 */

if (!defined('ABSPATH')) {
    exit;
}

function mall_dir_register_cpt() {
    // Register Custom Post Type: Store
    $labels = [
        'name' => __('Stores', 'mall-directory'),
        'singular_name' => __('Store', 'mall-directory'),
        'menu_name' => __('Stores', 'mall-directory'),
        'name_admin_bar' => __('Store', 'mall-directory'),
        'add_new' => __('Add New', 'mall-directory'),
        'add_new_item' => __('Add New Store', 'mall-directory'),
        'new_item' => __('New Store', 'mall-directory'),
        'edit_item' => __('Edit Store', 'mall-directory'),
        'view_item' => __('View Store', 'mall-directory'),
        'all_items' => __('All Stores', 'mall-directory'),
        'search_items' => __('Search Stores', 'mall-directory'),
        'parent_item_colon' => __('Parent Stores:', 'mall-directory'),
        'not_found' => __('No stores found.', 'mall-directory'),
        'not_found_in_trash' => __('No stores found in Trash.', 'mall-directory'),
    ];

    $args = [
        'labels' => $labels,
        'label' => __('Stores', 'mall-directory'),
        'description' => __('Mall Directory Stores', 'mall-directory'),
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-store',
        'supports' => ['title'],
        'show_in_rest' => false,
    ];
    
    register_post_type('md_store', $args);
    
    // Register Taxonomy: Store Category
    $tax_args = [
        'label' => __('Store Categories', 'mall-directory'),
        'public' => true,
        'publicly_queryable' => true,
        'hierarchical' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'store-category'],
    ];
    
    register_taxonomy('md_store_category', 'md_store', $tax_args);
}

add_action('init', 'mall_dir_register_cpt');

// Seed the default store categories once. This is code (not a manual DB
// edit), so it runs the same way on any environment the plugin is deployed
// to — no separate step needed to keep local/staging/live in sync. Admins
// can still add more afterward via Stores → Store Categories.
function mall_dir_seed_default_categories() {
    $existing = get_terms([
        'taxonomy'   => 'md_store_category',
        'hide_empty' => false,
        'fields'     => 'ids',
    ]);
    if (!empty($existing)) {
        return;
    }

    $defaults = [
        'Apparel',
        'Bikes & Accessories',
        'Fresh Food & Grains',
        'Parking',
        'Pets',
        'Plants',
        'Restaurants & Groceries',
        'Specialty Stores & Services',
    ];
    foreach ($defaults as $name) {
        wp_insert_term($name, 'md_store_category');
    }
}
add_action('init', 'mall_dir_seed_default_categories', 20);
