<?php
if (!defined('ABSPATH')) exit;

function cartimar_contact_form_shortcode() {
    ob_start();
    $submitted = isset($_GET['submitted']);
    $error     = isset($_GET['form_error']) ? sanitize_key($_GET['form_error']) : '';
    ?>
    <div class="contact-form-wrap">
        <?php if ($submitted): ?>
            <div class="contact-form__notice contact-form__notice--success">
                Thanks for reaching out! We'll get back to you as soon as we can.
            </div>
        <?php elseif ($error === 'required'): ?>
            <div class="contact-form__notice contact-form__notice--error">
                Please fill in a valid email and mobile number before submitting.
            </div>
        <?php elseif ($error === 'spam'): ?>
            <div class="contact-form__notice contact-form__notice--error">
                Something went wrong. Please try again.
            </div>
        <?php endif; ?>

        <form class="contact-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="cartimar_contact_form">
            <?php wp_nonce_field('cartimar_contact_form', 'cartimar_contact_form_nonce'); ?>
            <div class="contact-form__hp">
                <label for="cf_website">Website</label>
                <input type="text" id="cf_website" name="cf_website" tabindex="-1" autocomplete="off">
            </div>

            <div class="contact-form__row">
                <div class="contact-form__field">
                    <label for="cf_first_name">First Name</label>
                    <input type="text" id="cf_first_name" name="first_name" placeholder="First Name">
                </div>
                <div class="contact-form__field">
                    <label for="cf_last_name">Last Name</label>
                    <input type="text" id="cf_last_name" name="last_name" placeholder="Last Name">
                </div>
            </div>

            <div class="contact-form__row">
                <div class="contact-form__field">
                    <label for="cf_email">Email*</label>
                    <input type="email" id="cf_email" name="email" placeholder="Email" required>
                </div>
                <div class="contact-form__field">
                    <label for="cf_mobile">Mobile Number*</label>
                    <input type="tel" id="cf_mobile" name="mobile_number" placeholder="Mobile Number" required>
                </div>
            </div>

            <div class="contact-form__field">
                <label for="cf_message">Message</label>
                <textarea id="cf_message" name="message" rows="5" placeholder="Type your message here..."></textarea>
            </div>

            <button type="submit" class="btn btn--dark">Submit</button>

            <p class="contact-form__legal">
                This site is protected by our
                <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a> and
                <a href="<?php echo esc_url(home_url('/terms-and-conditions')); ?>">Terms and Conditions</a>.
            </p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cartimar_contact_form', 'cartimar_contact_form_shortcode');

function cartimar_handle_contact_form() {
    $redirect_to = wp_get_referer() ?: home_url('/contact');

    if (
        !isset($_POST['cartimar_contact_form_nonce']) ||
        !wp_verify_nonce($_POST['cartimar_contact_form_nonce'], 'cartimar_contact_form')
    ) {
        wp_safe_redirect(add_query_arg('form_error', 'spam', $redirect_to));
        exit;
    }

    // Honeypot — real visitors never fill this hidden field in.
    if (!empty($_POST['cf_website'])) {
        wp_safe_redirect(add_query_arg('form_error', 'spam', $redirect_to));
        exit;
    }

    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $mobile     = sanitize_text_field($_POST['mobile_number'] ?? '');
    $message    = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($email) || !is_email($email) || empty($mobile)) {
        wp_safe_redirect(add_query_arg('form_error', 'required', $redirect_to));
        exit;
    }

    $to      = get_option('admin_email');
    $subject = 'New Contact Form Submission from Cartimar Website';
    $body    = "You've received a new message from the Cartimar website contact form:\n\n"
        . "Name: {$first_name} {$last_name}\n"
        . "Email: {$email}\n"
        . "Mobile Number: {$mobile}\n\n"
        . "Message:\n{$message}\n";
    $headers = ["Reply-To: {$first_name} {$last_name} <{$email}>"];

    wp_mail($to, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('submitted', '1', $redirect_to));
    exit;
}
add_action('admin_post_cartimar_contact_form', 'cartimar_handle_contact_form');
add_action('admin_post_nopriv_cartimar_contact_form', 'cartimar_handle_contact_form');
