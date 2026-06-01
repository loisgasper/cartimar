<?php
/**
 * Mall Directory Frontend Template
 */

if (!defined('ABSPATH')) {
    exit;
}

// Get all stores
$stores = new WP_Query([
    'post_type' => 'md_store',
    'posts_per_page' => -1,
    'orderby' => 'title',
    'order' => 'ASC',
]);

// Get all categories
$categories = get_terms([
    'taxonomy' => 'md_store_category',
    'hide_empty' => false,
]);

// Prepare stores data for JavaScript
$stores_data = [];
if ($stores->have_posts()) {
    while ($stores->have_posts()) {
        $stores->the_post();
        $store_id = get_the_ID();
        $logo_id = get_post_meta($store_id, '_md_store_logo', true);
        $logo_url = $logo_id ? wp_get_attachment_url($logo_id) : '';
        $map_x = get_post_meta($store_id, '_md_store_map_x', true);
        $map_y = get_post_meta($store_id, '_md_store_map_y', true);
        
        $terms = wp_get_post_terms($store_id, 'md_store_category', ['fields' => 'all']);
        $categories_list = [];
        if (!empty($terms)) {
            foreach ($terms as $term) {
                $categories_list[] = [
                    'id' => $term->term_id,
                    'name' => $term->name,
                ];
            }
        }
        
        $stores_data[] = [
            'id' => $store_id,
            'title' => get_the_title(),
            'logo' => $logo_url,
            'x' => (int)$map_x,
            'y' => (int)$map_y,
            'categories' => $categories_list,
            'description' => get_the_excerpt(),
        ];
    }
    wp_reset_postdata();
}
?>

<div class="mall-directory-container">
    
    <!-- Filter Section -->
    <div class="mall-directory-filters">
        <div class="filter-group">
            <h3><?php _e('Filter by Category', 'mall-directory'); ?></h3>
            <div class="category-filters">
                <?php if (!empty($categories) && !is_wp_error($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <label class="category-checkbox">
                            <input type="checkbox" value="<?php echo esc_attr($category->term_id); ?>" class="store-category-filter">
                            <span><?php echo esc_html($category->name); ?></span>
                        </label>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="filter-group">
            <h3><?php _e('Search Store', 'mall-directory'); ?></h3>
            <input type="text" id="store-search" class="store-search-input" placeholder="<?php _e('Enter store name...', 'mall-directory'); ?>">
        </div>
    </div>

    <!-- Main Directory Section -->
    <div class="mall-directory-main">
        
        <!-- Floor Plan Map -->
        <div class="floor-plan-section">
            <div class="floor-plan-container">
                <img src="<?php echo esc_url($atts['floorplan']); ?>" alt="<?php _e('Floor Plan', 'mall-directory'); ?>" class="floor-plan-image" id="floorPlanImage">
                <div class="stores-overlay" id="storesOverlay">
                    <?php foreach ($stores_data as $store): ?>
                        <div class="store-marker" 
                             data-store-id="<?php echo esc_attr($store['id']); ?>"
                             data-categories="<?php echo esc_attr(json_encode(array_column($store['categories'], 'id'))); ?>"
                             style="left: <?php echo esc_attr($store['x']); ?>px; top: <?php echo esc_attr($store['y']); ?>px;">
                            <div class="store-marker-inner">
                                <?php if ($store['logo']): ?>
                                    <img src="<?php echo esc_url($store['logo']); ?>" alt="<?php echo esc_attr($store['title']); ?>" class="store-marker-logo">
                                <?php else: ?>
                                    <span class="store-marker-icon">🏪</span>
                                <?php endif; ?>
                            </div>
                            <div class="store-tooltip">
                                <strong><?php echo esc_html($store['title']); ?></strong>
                                <?php if (!empty($store['categories'])): ?>
                                    <br><small><?php echo esc_html(implode(', ', array_column($store['categories'], 'name'))); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Store List -->
        <div class="stores-list-section">
            <h3><?php _e('Store Directory', 'mall-directory'); ?></h3>
            <div class="stores-list" id="storesList">
                <?php foreach ($stores_data as $store): ?>
                    <div class="store-item" 
                         data-store-id="<?php echo esc_attr($store['id']); ?>"
                         data-store-name="<?php echo esc_attr($store['title']); ?>"
                         data-categories="<?php echo esc_attr(json_encode(array_column($store['categories'], 'id'))); ?>">
                        <div class="store-item-header">
                            <?php if ($store['logo']): ?>
                                <img src="<?php echo esc_url($store['logo']); ?>" alt="<?php echo esc_attr($store['title']); ?>" class="store-item-logo">
                            <?php else: ?>
                                <div class="store-item-logo-placeholder">🏪</div>
                            <?php endif; ?>
                            <div class="store-item-info">
                                <h4><?php echo esc_html($store['title']); ?></h4>
                                <?php if (!empty($store['categories'])): ?>
                                    <div class="store-categories">
                                        <?php foreach ($store['categories'] as $cat): ?>
                                            <span class="category-badge"><?php echo esc_html($cat['name']); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if (!empty($store['description'])): ?>
                            <p class="store-item-description"><?php echo wp_kses_post($store['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (empty($stores_data)): ?>
                <p class="no-stores-message"><?php _e('No stores found. Please add some stores first.', 'mall-directory'); ?></p>
            <?php endif; ?>
        </div>

    </div>

</div>

<!-- Store Data for JavaScript -->
<script type="application/json" id="storesData">
    <?php echo wp_json_encode($stores_data); ?>
</script>
