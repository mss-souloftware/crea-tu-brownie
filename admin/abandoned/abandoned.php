<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function abandonedOutput()
{
    // Handle form submissions
    if (isset($_POST['abandoned_cart_nonce']) && wp_verify_nonce($_POST['abandoned_cart_nonce'], 'save_abandoned_cart_settings')) {
        update_option('abandoned_cart_email_subject', sanitize_text_field($_POST['email_subject']));
        update_option('abandoned_cart_email_body', sanitize_textarea_field($_POST['email_body']));
        update_option('abandoned_cart_coupon', sanitize_text_field($_POST['coupon']));
        update_option('abandoned_cart_enable', isset($_POST['enable_abandoned_cart']) ? 1 : 0);
        update_option('abandoned_cart_minutes', intval($_POST['cart_minutes']));

        // Re-schedule the cron job
        schedule_abandoned_cart_check();
    }

    // Retrieve current settings
    $email_subject = get_option('abandoned_cart_email_subject', '');
    $email_body = get_option('abandoned_cart_email_body', '');
    $selected_coupon = get_option('abandoned_cart_coupon', '');
    $enable_abandoned_cart = get_option('abandoned_cart_enable', 0);
    $cart_minutes = get_option('abandoned_cart_minutes', 0);

    // Get all coupons

    $coupons = get_option('coupons', []);
    ?>
    <div class="wrap">
        <h2>Abandoned Cart Settings</h2>
        <form method="post" action="">
            <?php wp_nonce_field('save_abandoned_cart_settings', 'abandoned_cart_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Enable Abandoned Cart</th>
                    <td><input type="checkbox" name="enable_abandoned_cart" <?php checked($enable_abandoned_cart, 1); ?> />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Cart Abandonment Time (minutes)</th>
                    <td><input type="number" name="cart_minutes" value="<?php echo esc_attr($cart_minutes); ?>" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Email Subject</th>
                    <td><input type="text" name="email_subject" value="<?php echo esc_attr($email_subject); ?>" required />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Email Body</th>
                    <td><textarea name="email_body" rows="10" cols="50"
                            required><?php echo esc_textarea($email_body); ?></textarea></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Select Coupon</th>
                    <td>
                        <select name="coupon" required>
                            <option value="" selected>Select Coupon</option>
                            <?php foreach ($coupons as $coupon): ?>
                                <option value="<?php echo esc_attr($coupon['name']); ?>" <?php selected($selected_coupon, $coupon['name']); ?>><?php echo esc_html($coupon['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Settings'); ?>
        </form>
    </div>
    <?php
}
?>