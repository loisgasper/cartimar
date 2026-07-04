<?php
if (!defined('ABSPATH')) exit;

define('CARTIMAR_VERSION', '1.0.0');

function cartimar_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-header', [
        'default-image' => '',
        'width'         => 1920,
        'height'        => 1080,
        'flex-height'   => true,
    ]);
    add_theme_support('html5', ['search-form', 'comment-form', 'gallery', 'caption']);

    register_nav_menus([
        'primary' => __('Primary Menu', 'cartimar'),
        'footer'  => __('Footer Menu', 'cartimar'),
    ]);
}
add_action('after_setup_theme', 'cartimar_setup');

function cartimar_enqueue() {
    wp_enqueue_style('cartimar-main', get_template_directory_uri() . '/assets/css/main.css', [], CARTIMAR_VERSION);
    wp_enqueue_script('cartimar-main', get_template_directory_uri() . '/assets/js/main.js', ['jquery'], CARTIMAR_VERSION, true);
}
add_action('wp_enqueue_scripts', 'cartimar_enqueue');

function cartimar_widgets_init() {
    register_sidebar([
        'name'          => __('Sidebar', 'cartimar'),
        'id'            => 'sidebar-1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
    ]);
}
add_action('widgets_init', 'cartimar_widgets_init');
