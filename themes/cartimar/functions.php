<?php
if (!defined('ABSPATH')) exit;

define('CARTIMAR_VERSION', '1.0.0');

require_once get_template_directory() . '/inc/contact-form.php';

function cartimar_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // Load the theme's own stylesheet inside the block editor too, so editing
    // a page looks like the real front end instead of a plain, unstyled preview.
    add_theme_support('editor-styles');
    add_editor_style('assets/css/main.css');
}
add_action('after_setup_theme', 'cartimar_setup');

function cartimar_enqueue() {
    wp_enqueue_style('cartimar-main', get_template_directory_uri() . '/assets/css/main.css', [], CARTIMAR_VERSION);
    wp_enqueue_script('cartimar-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], CARTIMAR_VERSION, true);
}
add_action('wp_enqueue_scripts', 'cartimar_enqueue');

function cartimar_register_blocks() {
    register_block_type(get_template_directory() . '/inc/blocks/carousel');
    register_block_type(get_template_directory() . '/inc/blocks/timeline');
    register_block_type(get_template_directory() . '/inc/blocks/timeline-item');
}
add_action('init', 'cartimar_register_blocks');

// The "Further Read" Query Loop on single posts should never show the post you're already reading.
function cartimar_exclude_current_post_from_further_read($query_vars, $block) {
    $class_name = $block->attributes['className'] ?? '';
    if (strpos($class_name, 'further-read__grid') !== false && is_singular('post')) {
        $query_vars['post__not_in'] = [get_the_ID()];
    }
    return $query_vars;
}
add_filter('query_loop_block_query_vars', 'cartimar_exclude_current_post_from_further_read', 10, 2);
