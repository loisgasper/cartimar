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
        <a href="<?php echo esc_url(home_url('/')); ?>" class="cart-nav__logo">
            <?php if (has_custom_logo()): ?>
                <?php the_custom_logo(); ?>
            <?php else: ?>
                <span class="logo-c">c</span>artimar
            <?php endif; ?>
        </a>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="cart-nav__cta">Contact us</a>
    </div>
</nav>
