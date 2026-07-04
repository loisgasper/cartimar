
<section class="cart-contact-cta">
    <div class="container">
        <h2>Get In Touch</h2>
        <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn--outline-white">Contact us</a>
    </div>
</section>

<footer class="cart-footer">
    <div class="cart-footer__inner">
        <div class="cart-footer__brand">
            <div class="cart-footer__logo">
                <span class="logo-c">c</span>artimar
            </div>
            <div class="cart-footer__social">
                <a href="#" aria-label="Facebook" class="social-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                </a>
                <a href="#" aria-label="Instagram" class="social-link">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
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

        <p class="cart-footer__copy">&copy; <?php echo date('Y'); ?> Cartimar. All rights reserved.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
