<?php
if (!defined('ABSPATH')) exit;

if (!function_exists('acf_add_local_field_group')) {
    return;
}

add_action('acf/init', function () {

    acf_add_local_field_group([
        'key' => 'group_cartimar_homepage',
        'title' => 'Homepage Content',
        'fields' => [

            // ---------------- Hero ----------------
            [
                'key' => 'field_cartimar_tab_hero',
                'label' => 'Hero',
                'type' => 'tab',
            ],
            [
                'key' => 'field_cartimar_hero_heading',
                'label' => 'Heading',
                'name' => 'hero_heading',
                'type' => 'text',
                'default_value' => 'Great Finds<br>at Cartimar',
                'instructions' => 'Use <br> to control the line break.',
            ],

            // ---------------- About Us ----------------
            [
                'key' => 'field_cartimar_tab_about',
                'label' => 'About Us',
                'type' => 'tab',
            ],
            [
                'key' => 'field_cartimar_about_heading',
                'label' => 'Story Heading',
                'name' => 'about_heading',
                'type' => 'text',
                'default_value' => 'A Legacy of Great Finds',
            ],
            [
                'key' => 'field_cartimar_about_paragraph_1',
                'label' => 'Story Paragraph 1',
                'name' => 'about_paragraph_1',
                'type' => 'textarea',
                'default_value' => "In the 1940s, you just can't call it life unless you've experienced the thrill of discovering something truly extraordinary. We captured that essence, and the rest, they would say, became history.",
            ],
            [
                'key' => 'field_cartimar_about_paragraph_2',
                'label' => 'Story Paragraph 2',
                'name' => 'about_paragraph_2',
                'type' => 'textarea',
                'default_value' => 'Cartimar Shopping Complex got its name from its founders — Godless Philippians, Willo(w) and their beloved family. Today, the legacy continues with the third and fourth generation of the Cartimar and Godless families still running this beloved marketplace.',
            ],
            [
                'key' => 'field_cartimar_about_paragraph_3',
                'label' => 'Story Paragraph 3',
                'name' => 'about_paragraph_3',
                'type' => 'textarea',
                'default_value' => 'For generations, Cartimar has been the destination of choice for shoppers looking for unique products, amazing people, and stories that tell of a life well lived. From exotic animals to automotive parts, Cartimar has always exceeded expectations and continues to amaze the world.',
            ],
            [
                'key' => 'field_cartimar_about_story_image',
                'label' => 'Story Image',
                'name' => 'about_story_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
            ],
            [
                'key' => 'field_cartimar_about_gallery_1',
                'label' => 'Gallery Image 1 (tall)',
                'name' => 'about_gallery_image_1',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_2',
                'label' => 'Gallery Image 2',
                'name' => 'about_gallery_image_2',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_3',
                'label' => 'Gallery Image 3',
                'name' => 'about_gallery_image_3',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_4',
                'label' => 'Gallery Image 4 (wide)',
                'name' => 'about_gallery_image_4',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_5',
                'label' => 'Gallery Image 5',
                'name' => 'about_gallery_image_5',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_6',
                'label' => 'Gallery Image 6',
                'name' => 'about_gallery_image_6',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],

            // ---------------- Here to Serve ----------------
            [
                'key' => 'field_cartimar_tab_serve',
                'label' => 'Here to Serve',
                'type' => 'tab',
            ],
            [
                'key' => 'field_cartimar_serve_heading',
                'label' => 'Heading',
                'name' => 'serve_heading',
                'type' => 'text',
                'default_value' => 'Here to Serve<br>Here to Stay',
                'instructions' => 'Use <br> to control the line break.',
            ],
            [
                'key' => 'field_cartimar_serve_paragraph_1',
                'label' => 'Paragraph 1',
                'name' => 'serve_paragraph_1',
                'type' => 'textarea',
                'default_value' => 'For more than seven decades, Cartimar has stood the test of time. Through the generations we have always found a way to bring you the best this country has to offer.',
            ],
            [
                'key' => 'field_cartimar_serve_paragraph_2',
                'label' => 'Paragraph 2',
                'name' => 'serve_paragraph_2',
                'type' => 'textarea',
                'default_value' => 'It has always come down to one thing: finding ways to provide things that our communities need — making life a little better, a little more colourful and a little more fun.',
            ],
            [
                'key' => 'field_cartimar_serve_paragraph_3',
                'label' => 'Paragraph 3',
                'name' => 'serve_paragraph_3',
                'type' => 'textarea',
                'default_value' => 'Cartimar is a destination, a tradition, and a place where generations of Filipinos continue to shop, discover, and connect — a timeless destination for every shopper.',
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_1',
                'label' => 'Mosaic Image 1',
                'name' => 'serve_mosaic_image_1',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_2',
                'label' => 'Mosaic Image 2',
                'name' => 'serve_mosaic_image_2',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_3',
                'label' => 'Mosaic Image 3',
                'name' => 'serve_mosaic_image_3',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_4',
                'label' => 'Mosaic Image 4',
                'name' => 'serve_mosaic_image_4',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],

            // ---------------- Anniversary / Timeline ----------------
            [
                'key' => 'field_cartimar_tab_anniversary',
                'label' => 'Anniversary Timeline',
                'type' => 'tab',
            ],
            [
                'key' => 'field_cartimar_anniversary_intro',
                'label' => 'Intro Text',
                'name' => 'anniversary_intro',
                'type' => 'textarea',
                'default_value' => "From its beginnings in 1946 to becoming one of the Philippines' most enduring specialty shopping destinations, Cartimar has been serving the community for 75 remarkable years.",
            ],

            [
                'key' => 'field_cartimar_timeline_1_year',
                'label' => 'Item 1 – Year',
                'name' => 'timeline_1_year',
                'type' => 'text',
                'default_value' => '1946',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_timeline_1_body',
                'label' => 'Item 1 – Description',
                'name' => 'timeline_1_body',
                'type' => 'textarea',
                'default_value' => "Cartimar opens for business and becomes one of Manila's pioneering post-war shopping centres, offering everything from fresh produce to specialty goods in the heart of Pasay City.",
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_timeline_1_image',
                'label' => 'Item 1 – Image',
                'name' => 'timeline_1_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],

            [
                'key' => 'field_cartimar_timeline_2_year',
                'label' => 'Item 2 – Year',
                'name' => 'timeline_2_year',
                'type' => 'text',
                'default_value' => '1970s',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_timeline_2_body',
                'label' => 'Item 2 – Description',
                'name' => 'timeline_2_body',
                'type' => 'textarea',
                'default_value' => 'Cartimar grows into a destination market, becoming famous for its wide selection of exotic birds, aquatic pets, and fresh food. Shoppers from across Metro Manila make it a regular weekend stop.',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_timeline_2_image',
                'label' => 'Item 2 – Image',
                'name' => 'timeline_2_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],

            [
                'key' => 'field_cartimar_timeline_3_year',
                'label' => 'Item 3 – Year',
                'name' => 'timeline_3_year',
                'type' => 'text',
                'default_value' => '1980s',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_timeline_3_body',
                'label' => 'Item 3 – Description',
                'name' => 'timeline_3_body',
                'type' => 'textarea',
                'default_value' => 'The automotive and bike section expands significantly, establishing Cartimar as the go-to hub for cycling enthusiasts and auto parts buyers throughout the Philippines.',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_timeline_3_image',
                'label' => 'Item 3 – Image',
                'name' => 'timeline_3_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],

            [
                'key' => 'field_cartimar_timeline_4_year',
                'label' => 'Item 4 – Year',
                'name' => 'timeline_4_year',
                'type' => 'text',
                'default_value' => 'Today',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_timeline_4_body',
                'label' => 'Item 4 – Description',
                'name' => 'timeline_4_body',
                'type' => 'textarea',
                'default_value' => 'With over 500 merchants across multiple buildings, Cartimar continues to serve as a beloved community landmark — balancing its rich heritage with the needs of modern shoppers.',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_timeline_4_image',
                'label' => 'Item 4 – Image',
                'name' => 'timeline_4_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'front_page',
                ],
            ],
        ],
    ]);
});

/**
 * Outputs an <img> for an ACF image field, or nothing if no image is set —
 * pair with cartimar_acf_image_class() on the wrapping element for the placeholder style.
 */
function cartimar_acf_image_tag($field_name, $fallback_alt = '') {
    $image = get_field($field_name);
    if ($image && !empty($image['url'])) {
        printf(
            '<img src="%s" alt="%s">',
            esc_url($image['url']),
            esc_attr($image['alt'] ?: $fallback_alt)
        );
    }
}

/**
 * Returns 'img-placeholder' when an ACF image field has no value set.
 */
function cartimar_acf_image_class($field_name) {
    $image = get_field($field_name);
    return ($image && !empty($image['url'])) ? '' : 'img-placeholder';
}
