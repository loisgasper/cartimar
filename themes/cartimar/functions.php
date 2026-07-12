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
    $css_path = get_template_directory() . '/assets/css/main.css';
    $js_path  = get_template_directory() . '/assets/js/main.js';
    wp_enqueue_style('cartimar-main', get_template_directory_uri() . '/assets/css/main.css', [], file_exists($css_path) ? filemtime($css_path) : CARTIMAR_VERSION);
    wp_enqueue_script('cartimar-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], file_exists($js_path) ? filemtime($js_path) : CARTIMAR_VERSION, true);
}
add_action('wp_enqueue_scripts', 'cartimar_enqueue');

function cartimar_register_blocks() {
    register_block_type(get_template_directory() . '/inc/blocks/carousel');
    register_block_type(get_template_directory() . '/inc/blocks/timeline');
    register_block_type(get_template_directory() . '/inc/blocks/timeline-item');
}
add_action('init', 'cartimar_register_blocks');

// Pages whose content opens with a full-bleed banner keep the transparent nav
// with white text; every other page gets a "no-banner-nav" body class so the
// CSS can flip to a white bar with dark links and the blue logo — white text
// would vanish against the page background.
function cartimar_nav_banner_body_class($classes) {
    $has_banner = is_home(); // the What's Happening template carries its own archive-hero banner
    if (!$has_banner && is_singular()) {
        $content = get_post()->post_content ?? '';
        $has_banner = strpos($content, 'cart-hero') !== false
            || strpos($content, 'page-hero-split') !== false
            || strpos($content, 'archive-hero') !== false;
    }
    if (!$has_banner) {
        $classes[] = 'no-banner-nav';
    }
    return $classes;
}
add_filter('body_class', 'cartimar_nav_banner_body_class');

// The What's Happening banner lives in the home template, not in editable page
// content, so its photo can't be swapped from the page editor like the
// homepage's. Instead the banner uses the Featured Image set on the posts page
// (Pages → What's Happening → Featured image in the sidebar); the theme's
// bundled photo in main.css stays as the fallback when none is set.
function cartimar_archive_hero_featured_image($block_content, $block) {
    if (($block['attrs']['className'] ?? '') !== 'archive-hero' || !is_home()) {
        return $block_content;
    }
    $banner = get_the_post_thumbnail_url(get_option('page_for_posts'), 'full');
    if (!$banner) {
        return $block_content;
    }
    return preg_replace(
        '/<div\b/',
        '<div style="background-image: url(' . esc_url($banner) . ')"',
        $block_content,
        1
    );
}
add_filter('render_block_core/group', 'cartimar_archive_hero_featured_image', 10, 2);

// The "Further Read" Query Loop on single posts should never show the post you're already reading.
function cartimar_exclude_current_post_from_further_read($query_vars, $block) {
    $class_name = $block->attributes['className'] ?? '';
    if (strpos($class_name, 'further-read__grid') !== false && is_singular('post')) {
        $query_vars['post__not_in'] = [get_the_ID()];
    }
    return $query_vars;
}
add_filter('query_loop_block_query_vars', 'cartimar_exclude_current_post_from_further_read', 10, 2);
