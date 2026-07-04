<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav class="cart-nav" id="cartNav">
    <div class="cart-nav__inner">
        <?php if (has_custom_logo()): ?>
            <div class="cart-nav__logo"><?php the_custom_logo(); ?></div>
        <?php else: ?>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="cart-nav__logo">
                <span class="logo-c">c</span>artimar
            </a>
        <?php endif; ?>
        <?php wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'cart-nav__menu',
            'fallback_cb'    => false,
            'depth'          => 1,
        ]); ?>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="cart-nav__cta">Contact us</a>
    </div>
</nav>
