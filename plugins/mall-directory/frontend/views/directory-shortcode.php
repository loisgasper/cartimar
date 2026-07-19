<?php
/**
 * Mall Directory Frontend Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get all stores
$stores = new WP_Query([
    'post_type'      => 'md_store',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

// Get only categories that have at least one store
$categories = get_terms([
    'taxonomy'   => 'md_store_category',
    'hide_empty' => true,
]);

// Prepare stores data for JavaScript
$stores_data = [];
if ($stores->have_posts()) {
    while ($stores->have_posts()) {
        $stores->the_post();
        $store_id   = get_the_ID();
        $logo_id    = get_post_meta($store_id, '_md_store_logo', true);
        $logo_url   = $logo_id ? wp_get_attachment_url($logo_id) : '';
        $map_x      = get_post_meta($store_id, '_md_store_map_x', true);
        $map_y      = get_post_meta($store_id, '_md_store_map_y', true);
        $store_name = get_post_meta($store_id, '_md_store_name', true);

        $terms           = wp_get_post_terms($store_id, 'md_store_category', ['fields' => 'all']);
        $categories_list = [];
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $categories_list[] = ['id' => $term->term_id, 'name' => $term->name];
            }
        }

        $stores_data[] = [
            'id'         => $store_id,
            'title'      => $store_name ?: get_the_title(),
            'logo'       => $logo_url,
            'x'          => (int) $map_x,
            'y'          => (int) $map_y,
            'location'   => get_post_meta($store_id, '_md_store_location', true),
            'phone'      => get_post_meta($store_id, '_md_store_phone', true),
            'map_area'   => get_post_meta($store_id, '_md_store_map_area', true),
            'categories' => $categories_list,
        ];
    }
    wp_reset_postdata();
}

$pin_icon = '<svg class="store-pin-svg" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>';
?>

<div class="mall-directory-container">

    <!-- Search + Category Filter -->
    <div class="mall-directory-filters">
        <div class="mall-directory-search">
            <input type="text" id="store-search" class="store-search-input" placeholder="<?php _e('Search directory...', 'mall-directory'); ?>">
        </div>
        <div class="mall-directory-category">
            <!-- Custom-styled dropdown. The real <select> below still drives
                 all filtering (see directory.js) and is kept for form
                 semantics — it's visually hidden, and this button+listbox
                 pair is the actual visible/interactive UI, kept in sync
                 with it on every selection. -->
            <div class="store-category-dropdown" id="storeCategoryDropdown">
                <button type="button" class="store-category-dropdown__toggle" id="storeCategoryToggle" aria-haspopup="listbox" aria-expanded="false" aria-controls="storeCategoryMenu">
                    <span class="store-category-dropdown__label"><?php _e('All Shops', 'mall-directory'); ?></span>
                    <svg class="store-category-dropdown__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <ul class="store-category-dropdown__menu" role="listbox" id="storeCategoryMenu" aria-labelledby="storeCategoryToggle" tabindex="-1">
                    <li class="store-category-dropdown__option is-selected" role="option" data-value="" aria-selected="true" tabindex="-1"><?php _e('All Shops', 'mall-directory'); ?></li>
                    <?php foreach ($categories as $category): ?>
                        <li class="store-category-dropdown__option" role="option" data-value="<?php echo esc_attr($category->term_id); ?>" aria-selected="false" tabindex="-1"><?php echo esc_html($category->name); ?></li>
                    <?php endforeach; ?>
                </ul>
                <select id="store-category-filter" class="store-category-select-native" tabindex="-1" aria-hidden="true">
                    <option value=""><?php _e('All Shops', 'mall-directory'); ?></option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <!-- Main: Stores (left 30%) | Map (right 70%) -->
    <div class="mall-directory-main">

        <!-- Store List -->
        <div class="stores-list-section">
            <div class="stores-list" id="storesList">
                <?php foreach ($stores_data as $store): ?>
                    <div class="store-item"
                         data-store-id="<?php echo esc_attr($store['id']); ?>"
                         data-store-name="<?php echo esc_attr($store['title']); ?>"
                         data-store-location="<?php echo esc_attr($store['location']); ?>"
                         data-store-phone="<?php echo esc_attr($store['phone']); ?>"
                         data-map-area="<?php echo esc_attr($store['map_area']); ?>"
                         data-categories="<?php echo esc_attr(json_encode(array_column($store['categories'], 'id'))); ?>">

                        <div class="store-item-thumb">
                            <?php if ($store['logo']): ?>
                                <img src="<?php echo esc_url($store['logo']); ?>" alt="<?php echo esc_attr($store['title']); ?>">
                            <?php else: ?>
                                <div class="store-item-thumb--pin"><?php echo $pin_icon; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="store-item-info">
                            <h4><?php echo esc_html($store['title']); ?></h4>
                            <?php if (!empty($store['location'])): ?>
                                <p class="store-item-location">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                    <?php echo esc_html($store['location']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($store['phone'])): ?>
                                <p class="store-item-phone">
                                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1-9.4 0-17-7.6-17-17 0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/></svg>
                                    <?php echo esc_html($store['phone']); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (empty($stores_data)): ?>
                <p class="no-stores-message"><?php _e('No stores found. Please add some stores first.', 'mall-directory'); ?></p>
            <?php endif; ?>
        </div>

         <!-- Interactive SVG Map -->
        <div class="floor-plan-section">
            <div class="floor-plan-container">
                <?php include plugin_dir_path(__FILE__) . 'map-svg.php'; ?>
                <div class="svg-map-controls" aria-label="<?php _e('Map controls', 'mall-directory'); ?>">
                    <button class="svg-zoom-btn" id="svgZoomIn"    title="<?php _e('Zoom in',    'mall-directory'); ?>">+</button>
                    <button class="svg-zoom-btn" id="svgZoomOut"   title="<?php _e('Zoom out',   'mall-directory'); ?>">−</button>
                    <button class="svg-zoom-btn" id="svgZoomReset" title="<?php _e('Reset view', 'mall-directory'); ?>">⊙</button>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Store Data for JavaScript -->
<script type="application/json" id="storesData">
    <?php echo wp_json_encode($stores_data); ?>
</script>
