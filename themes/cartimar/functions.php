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

// The hero carousel's slide-switching (crossfade, arrows, dots, autoplay) is
// driven entirely by main.js, which only loads on the front end — so without
// this, the editor preview shows an empty hero (see main.css's
// .editor-styles-wrapper .cart-hero__slides rule for the CSS-only fallback
// this complements). Loading main.js itself here isn't safe: it also wires
// up unrelated front-end-only behavior (nav scroll, directory search
// DOM relocation, anchor-click hijacking) against `document`, which can
// conflict with the block editor's own DOM and event handling. This is a
// standalone copy of just the carousel logic instead.
// enqueue_block_assets (unlike enqueue_block_editor_assets) is mirrored by
// WordPress into the post/site editor's iframe canvas, which is where
// .cart-hero__slides actually lives — the parent admin document never sees it.
function cartimar_enqueue_editor_hero_carousel() {
    if (!is_admin()) return;
    $js_path = get_template_directory() . '/assets/js/hero-carousel-editor.js';
    wp_enqueue_script(
        'cartimar-hero-carousel-editor',
        get_template_directory_uri() . '/assets/js/hero-carousel-editor.js',
        ['jquery'],
        file_exists($js_path) ? filemtime($js_path) : CARTIMAR_VERSION,
        true
    );
}
add_action('enqueue_block_assets', 'cartimar_enqueue_editor_hero_carousel');

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

// Hero carousel videos must always play the exact file the client uploaded —
// force each <video> src back to wp_get_attachment_url() for its attachment
// ID so no cached/older URL or alternate copy can ever be served instead.
//
// Each core/video inner block is located and replaced by its own attachment
// ID rather than by counting <video src="..."> matches across the whole
// block's HTML: core/video doesn't always put src on the <video> tag itself
// (it can render as <video controls><source src="..."></video> depending on
// attrs), and a shared counter that only advances on a regex match will drift
// out of sync with the inner block list the moment one slide's markup misses
// that match — silently reassigning a later slide's URL to an earlier slide.
function cartimar_hero_carousel_force_original_video_src($block_content, $block) {
    if (($block['blockName'] ?? '') !== 'cartimar/hero-carousel-slides') {
        return $block_content;
    }
    $video_blocks = array_values(array_filter(
        $block['innerBlocks'] ?? [],
        function ($inner_block) {
            return ($inner_block['blockName'] ?? '') === 'core/video';
        }
    ));
    if (empty($video_blocks)) {
        return $block_content;
    }

    // <video>…</video> per slide, matched one-to-one with $video_blocks in
    // document order (core/video always renders exactly one <video> element).
    $video_tags = array();
    preg_match_all('/<video\b[^>]*>.*?<\/video>/is', $block_content, $video_tags);
    $video_tags = $video_tags[0];

    if (count($video_tags) !== count($video_blocks)) {
        return $block_content;
    }

    foreach ($video_blocks as $i => $inner_block) {
        $attachment_id = $inner_block['attrs']['id'] ?? 0;
        $original_url = $attachment_id ? wp_get_attachment_url($attachment_id) : false;
        if (!$original_url) {
            continue;
        }
        $replaced_tag = preg_replace(
            '/\bsrc="[^"]*"/i',
            'src="' . esc_url($original_url) . '"',
            $video_tags[$i],
            1
        );
        $block_content = substr_replace(
            $block_content,
            $replaced_tag,
            strpos($block_content, $video_tags[$i]),
            strlen($video_tags[$i])
        );
    }

    return $block_content;
}
add_filter('render_block_cartimar/hero-carousel-slides', 'cartimar_hero_carousel_force_original_video_src', 10, 2);

// Hero carousel images must always render at full resolution — core/image
// defaults to whichever size the editor had selected (often a scaled-down
// "large" size) and adds a srcset that lets the browser pick something even
// smaller, so force both back to the original uploaded file per image.
function cartimar_hero_carousel_force_full_size_images($block_content, $block) {
    if (($block['blockName'] ?? '') !== 'cartimar/hero-carousel-slides') {
        return $block_content;
    }
    $original_urls = array();
    foreach (($block['innerBlocks'] ?? []) as $inner_block) {
        if (($inner_block['blockName'] ?? '') !== 'core/image') {
            continue;
        }
        $attachment_id = $inner_block['attrs']['id'] ?? 0;
        $original_url = $attachment_id ? wp_get_attachment_url($attachment_id) : false;
        $original_urls[] = $original_url ?: null;
    }
    if (empty($original_urls)) {
        return $block_content;
    }
    $image_index = 0;
    return preg_replace_callback(
        '/<img\b[^>]*>/i',
        function ($matches) use (&$image_index, $original_urls) {
            $url = $original_urls[$image_index] ?? null;
            $image_index++;
            if (!$url) {
                return $matches[0];
            }
            $tag = preg_replace('/\ssrcset="[^"]*"/i', '', $matches[0]);
            $tag = preg_replace('/\ssizes="[^"]*"/i', '', $tag);
            $tag = preg_replace('/(\bsrc=")[^"]*(")/i', '${1}' . esc_url($url) . '${2}', $tag);
            return $tag;
        },
        $block_content
    );
}
add_filter('render_block_cartimar/hero-carousel-slides', 'cartimar_hero_carousel_force_full_size_images', 10, 2);

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

    // On the oldest/newest post one side has no chronological neighbor —
    // wrap around to the first/last post instead of showing only one card.
    if (!$prev) {
        $prev = get_posts([
            'numberposts' => 1,
            'orderby'     => 'date',
            'order'       => 'DESC',
            'post__not_in' => [get_the_ID()],
        ]);
        $prev = $prev ? $prev[0] : null;
    }
    if (!$next) {
        $next = get_posts([
            'numberposts' => 1,
            'orderby'     => 'date',
            'order'       => 'ASC',
            'post__not_in' => [get_the_ID()],
        ]);
        $next = $next ? $next[0] : null;
    }

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
