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
    register_block_type(get_template_directory() . '/inc/blocks/hero-carousel');
    register_block_type(get_template_directory() . '/inc/blocks/hero-carousel-slides');
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

// Hero carousel video slides must behave like a silent background loop
// regardless of whether the editor remembered to enable Muted/Loop/Autoplay/
// Plays inline on the block — force them so a client forgetting a toggle
// doesn't ship a video with audio or visible controls.
function cartimar_hero_carousel_force_video_attrs($block_content, $block) {
    if (($block['blockName'] ?? '') !== 'cartimar/hero-carousel-slides') {
        return $block_content;
    }
    return preg_replace(
        '/<video\b(?![^>]*\bmuted\b)/i',
        '<video muted loop playsinline autoplay',
        $block_content
    );
}
add_filter('render_block_cartimar/hero-carousel-slides', 'cartimar_hero_carousel_force_video_attrs', 10, 2);

// Every social icon link (Facebook, TikTok, Instagram, etc.) should open in a
// new tab rather than navigate away from the site — the core Social Links
// block has no built-in toggle for this, so add it to every link it renders.
function cartimar_social_link_new_tab($block_content) {
    if (strpos($block_content, 'target=') !== false) {
        return $block_content;
    }
    return preg_replace(
        '/<a\s+href="([^"]*)"/',
        '<a href="$1" target="_blank" rel="noopener noreferrer"',
        $block_content,
        1
    );
}
add_filter('render_block_core/social-link', 'cartimar_social_link_new_tab');

// "Further Read" on single posts should show the true previous/next post (by publish date),
// not just the site's latest posts — a Query Loop can't express that, so render the two
// cards manually via shortcode, reusing the same news-feature-card markup/styling.
function cartimar_further_read_card($post) {
    $permalink = get_permalink($post);
    $title = get_the_title($post);
    $excerpt = wp_trim_words(get_the_excerpt($post), 18);
    $thumb = get_the_post_thumbnail($post, 'medium_large', ['class' => 'news-feature-card__img-el']);

    ob_start();
    ?>
    <div class="wp-block-group news-feature-card">
        <figure class="news-feature-card__img"><a href="<?php echo esc_url($permalink); ?>"><?php echo $thumb; ?></a></figure>
        <div class="news-feature-card__body">
            <h3 class="news-feature-card__title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($title); ?></a></h3>
            <p class="news-feature-card__excerpt"><?php echo esc_html($excerpt); ?></p>
            <a class="btn btn--dark news-feature-card__btn" href="<?php echo esc_url($permalink); ?>">Read More</a>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function cartimar_further_read_shortcode() {
    if (!is_singular('post')) {
        return '';
    }

    $prev = get_adjacent_post(false, '', true);
    $next = get_adjacent_post(false, '', false);

    $cards = '';
    if ($prev) {
        setup_postdata($prev);
        $cards .= cartimar_further_read_card($prev);
    }
    if ($next) {
        setup_postdata($next);
        $cards .= cartimar_further_read_card($next);
    }
    wp_reset_postdata();

    return $cards;
}

// Rendered via a group block with this className rather than a shortcode block:
// core/shortcode's render pipeline runs output through wpautop(), which mangles
// hand-written block-level markup into stray/unbalanced <p> tags.
add_filter('render_block_core/group', function ($block_content, $block) {
    $class_name = $block['attrs']['className'] ?? '';
    if (strpos($class_name, 'further-read__grid') === false) {
        return $block_content;
    }
    return '<div class="wp-block-group further-read__grid">' . cartimar_further_read_shortcode() . '</div>';
}, 10, 2);
