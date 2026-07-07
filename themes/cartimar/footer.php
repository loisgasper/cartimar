
<section class="cart-contact-cta">
    <div class="cart-contact-cta__bg"></div>
    <div class="container">
        <h2>Get In Touch</h2>
        <p>Whether you have a question or need assistance, our team is here to help.</p>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--outline-white">Contact Us</a>
    </div>
</section>

<footer class="cart-footer">
    <div class="cart-footer__inner">
        <div class="cart-footer__top">
            <div class="cart-footer__brand">
                <?php if (has_custom_logo()): ?>
                    <div class="cart-footer__logo"><?php the_custom_logo(); ?></div>
                <?php else: ?>
                    <div class="cart-footer__logo cart-footer__logo--text">
                        <span class="logo-c">c</span>artimar
                    </div>
                <?php endif; ?>

                <p class="cart-footer__stay-connected">Stay Connected</p>
                <div class="cart-footer__social">
                    <a href="#" aria-label="Facebook" class="social-link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </a>
                    <a href="#" aria-label="TikTok" class="social-link">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82s.51.5 0 0A4.278 4.278 0 0 1 15.54 3h-3.09v12.4a2.592 2.592 0 0 1-2.59 2.5c-1.42 0-2.6-1.16-2.6-2.6 0-1.72 1.66-3.01 3.37-2.48V9.66c-3.45-.46-6.47 2.22-6.47 5.64 0 3.33 2.76 5.7 5.69 5.7 3.14 0 5.69-2.55 5.69-5.7V9.01a7.35 7.35 0 0 0 4.3 1.38V7.3s-1.88.09-3.24-1.48z"/></svg>
                    </a>
                </div>
            </div>

            <nav class="cart-footer__nav">
                <a href="<?php echo esc_url(home_url('/')); ?>">HOME</a>
                <a href="<?php echo esc_url(home_url('/#directory')); ?>">FIND A STORE</a>
                <a href="<?php echo esc_url(home_url('/#news')); ?>">WHAT'S HAPPENING</a>
                <a href="<?php echo esc_url(home_url('/#about')); ?>">ABOUT US</a>
                <a href="<?php echo esc_url(home_url('/contact')); ?>">CONTACT US</a>
            </nav>
        </div>

        <div class="cart-footer__bottom">
            <p class="cart-footer__copy">&copy; <?php echo date('Y'); ?> Cartimar. All rights reserved.</p>
            <div class="cart-footer__legal">
                <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a>
                <a href="<?php echo esc_url(home_url('/terms-and-conditions')); ?>">Terms and Conditions</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
