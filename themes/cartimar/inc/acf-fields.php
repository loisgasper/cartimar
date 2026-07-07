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
                'default_value' => "In the 1950's, as rock n' roll started to fill the airwaves, the country's first shopping center opened in Pasay, one that would forever change the shopping experience in the Philippines.",
            ],
            [
                'key' => 'field_cartimar_about_paragraph_2',
                'label' => 'Story Paragraph 2',
                'name' => 'about_paragraph_2',
                'type' => 'textarea',
                'default_value' => "Cartimar Shopping Center got its name from its founders—Carlos Cuyugan, Timotea Cuyugan, and their daughter, Margarita Cuyugan-Oppen. Today, the legacy continues under the leadership of Margarita's grandson, Ernesto Jose Oppen who is currently the President and CEO of Cartimar.",
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
                'key' => 'field_cartimar_about_intro_paragraph_1',
                'label' => 'Intro Paragraph 1',
                'name' => 'about_intro_paragraph_1',
                'type' => 'textarea',
                'default_value' => 'For generations, Cartimar has been the destination of choice for shoppers looking for something unusual, exciting, and hard to find. Long before specialty stores became widely available, Cartimar was already known as the place to go for imported goods from around the world.',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_about_intro_paragraph_2',
                'label' => 'Intro Paragraph 2',
                'name' => 'about_intro_paragraph_2',
                'type' => 'textarea',
                'default_value' => 'It offered access to designer jeans, sneakers, chocolates, perfumes, specialty products, and countless other unique finds.',
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_1',
                'label' => 'Gallery Image 1 (Pet Center sign)',
                'name' => 'about_gallery_image_1',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_2',
                'label' => 'Gallery Image 2 (turtles)',
                'name' => 'about_gallery_image_2',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_3',
                'label' => 'Gallery Image 3 (dog)',
                'name' => 'about_gallery_image_3',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_4',
                'label' => 'Gallery Image 4 (admin door)',
                'name' => 'about_gallery_image_4',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_5',
                'label' => 'Gallery Image 5 (bike alley, tall)',
                'name' => 'about_gallery_image_5',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_gallery_6',
                'label' => 'Gallery Image 6 (bikes close-up, tall)',
                'name' => 'about_gallery_image_6',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '33'],
            ],
            [
                'key' => 'field_cartimar_about_closing_paragraph',
                'label' => 'Closing Paragraph',
                'name' => 'about_closing_paragraph',
                'type' => 'textarea',
                'default_value' => 'What sets Cartimar apart is its remarkable variety with a distinctive mix of products and services that cater to different needs, interests, and lifestyles. Whether visiting for a specific purchase, searching for a rare find, or simply exploring what our merchants have to offer, Cartimar remains a place where shoppers can discover something new and delightful with every visit.',
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
                'key' => 'field_cartimar_serve_intro',
                'label' => 'Intro Paragraph (next to heading)',
                'name' => 'serve_intro',
                'type' => 'textarea',
                'default_value' => 'For more than seven decades, Cartimar has stood the test of time. Through changing trends, times of crisis, and evolving consumer habits, Cartimar has remained a trusted and beloved shopping destination.',
            ],
            [
                'key' => 'field_cartimar_serve_paragraph_1',
                'label' => 'Closing Paragraph 1 (below carousel)',
                'name' => 'serve_paragraph_1',
                'type' => 'textarea',
                'default_value' => 'It has always been more than just a shopping center, serving the community by providing access to essential products, including food and daily necessities, while also being home to thriving businesses that cater to hobbies, passions, and lifestyle interests.',
            ],
            [
                'key' => 'field_cartimar_serve_paragraph_2',
                'label' => 'Closing Paragraph 2 (below carousel)',
                'name' => 'serve_paragraph_2',
                'type' => 'textarea',
                'default_value' => 'Cartimar is a landmark, a tradition, and a place where generations of Filipinos continue to shop, discover, and connect - a timeless destination for every shopper.',
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_1',
                'label' => 'Carousel Image 1',
                'name' => 'serve_mosaic_image_1',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_2',
                'label' => 'Carousel Image 2',
                'name' => 'serve_mosaic_image_2',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_3',
                'label' => 'Carousel Image 3',
                'name' => 'serve_mosaic_image_3',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_serve_mosaic_4',
                'label' => 'Carousel Image 4',
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
                'default_value' => "From its beginnings in 1946 to becoming one of the country's most recognized specialty shopping centers.",
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_anniversary_intro_2',
                'label' => 'Intro Text 2',
                'name' => 'anniversary_intro_2',
                'type' => 'textarea',
                'default_value' => "Cartimar's story is built on the merchants, shoppers, and families who continue to make it a destination unlike any other.",
                'wrapper' => ['width' => '50'],
            ],
            [
                'key' => 'field_cartimar_timeline_image',
                'label' => 'Timeline Image (sticky)',
                'name' => 'timeline_image',
                'type' => 'image',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'instructions' => 'Stays pinned on the right while the years scroll past on the left.',
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
                'default_value' => "Cartimar opens its doors and quickly becomes one of Manila's pioneering shopping centers, bringing together merchants and shoppers in a thriving commercial community.",
                'wrapper' => ['width' => '75'],
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
                'default_value' => 'Cartimar becomes known for its thriving bicycle, pet, aquarium, and specialty shops creating a destination for hobbyists and passionate communities.',
                'wrapper' => ['width' => '75'],
            ],

            [
                'key' => 'field_cartimar_timeline_3_year',
                'label' => 'Item 3 – Year',
                'name' => 'timeline_3_year',
                'type' => 'text',
                'default_value' => '1990s',
                'wrapper' => ['width' => '25'],
            ],
            [
                'key' => 'field_cartimar_timeline_3_body',
                'label' => 'Item 3 – Description',
                'name' => 'timeline_3_body',
                'type' => 'textarea',
                'default_value' => "Generations of Filipinos continue to visit Cartimar for its unique mix of merchants, expertise, and products that can't be found anywhere else.",
                'wrapper' => ['width' => '75'],
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
                'default_value' => 'While much has changed over the years, Cartimar remains committed to supporting local businesses, serving loyal customers, and creating memorable shopping experiences.',
                'wrapper' => ['width' => '75'],
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
