<?php
/**
 * Single store card. Included by directory-shortcode.php for the initial
 * server-rendered batch; directory.js's renderStoreItem() renders the same
 * markup client-side for everything after that (see store-item.php note in
 * directory-shortcode.php).
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<div class="store-item"
     data-store-id="<?php echo esc_attr($store['id']); ?>"
     data-store-name="<?php echo esc_attr($store['title']); ?>"
     data-store-location="<?php echo esc_attr($store['location']); ?>"
     data-store-stall="<?php echo esc_attr($store['stall']); ?>"
     data-store-phone="<?php echo esc_attr($store['phone']); ?>"
     data-map-area="<?php echo esc_attr($store['map_area']); ?>"
     data-categories="<?php echo esc_attr(json_encode(array_column($store['categories'], 'id'))); ?>">

    <div class="store-item-thumb">
        <?php if ($store['logo']): ?>
            <img src="<?php echo esc_url($store['logo']); ?>" alt="<?php echo esc_attr($store['title']); ?>" loading="lazy" width="56" height="56">
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
        <?php if (!empty($store['stall'])): ?>
            <p class="store-item-stall">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v6H3V3zm0 8h8v10H3V11zm10 0h8v10h-8V11z"/></svg>
                <?php echo esc_html($store['stall']); ?>
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
