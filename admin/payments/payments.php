<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function paymentOutput() {
    // Check if the form is submitted
    if (isset($_POST['ctf_settings_submit'])) {
        // Verify nonce for security
        if (isset($_POST['ctf_settings_nonce']) && wp_verify_nonce($_POST['ctf_settings_nonce'], 'ctf_save_settings')) {
            // Sanitize and save the data
            $payment_methods = [
                'paypal' => isset($_POST['ctf_payment_methods']['paypal']) ? 1 : 0,
                'redsys' => isset($_POST['ctf_payment_methods']['redsys']) ? 1 : 0,
                'bizum' => isset($_POST['ctf_payment_methods']['bizum']) ? 1 : 0,
                'googlepay' => isset($_POST['ctf_payment_methods']['googlepay']) ? 1 : 0,
                'applepay' => isset($_POST['ctf_payment_methods']['applepay']) ? 1 : 0,
            ];

            $ctf_settings = [
                'payment_methods' => $payment_methods,
                'plugin_page' => sanitize_text_field($_POST['ctf_plugin_page']),
                'plugin_payment' => sanitize_text_field($_POST['ctf_plugin_payment']),
                'thank_you_page' => sanitize_text_field($_POST['ctf_thank_you_page']),
            ];

            update_option('ctf_settings', $ctf_settings);
        }
    }

    // Retrieve saved settings
    $options = get_option('ctf_settings');
    $payment_methods = isset($options['payment_methods']) ? $options['payment_methods'] : [];
    $plugin_page = isset($options['plugin_page']) ? $options['plugin_page'] : '';
    $plugin_payment = isset($options['plugin_payment']) ? $options['plugin_payment'] : '';
    $thank_you_page = isset($options['thank_you_page']) ? $options['thank_you_page'] : '';
    ?>

    <div class="wrap">
        <h1><?php _e('Payment Settings', 'ctf'); ?></h1>
        <form method="post" action="">
            <?php wp_nonce_field('ctf_save_settings', 'ctf_settings_nonce'); ?>

            <h2><?php _e('Select Payment Methods', 'ctf'); ?></h2>
            <label>
                <input type="checkbox" name="ctf_payment_methods[paypal]" value="1" <?php checked(1, isset($payment_methods['paypal']) ? $payment_methods['paypal'] : 0); ?> />
                <?php _e('Paypal', 'ctf'); ?>
            </label><br/>
            <label>
                <input type="checkbox" name="ctf_payment_methods[redsys]" value="1" <?php checked(1, isset($payment_methods['redsys']) ? $payment_methods['redsys'] : 0); ?> />
                <?php _e('Redsys', 'ctf'); ?>
            </label><br/>
            <label>
                <input type="checkbox" name="ctf_payment_methods[bizum]" value="1" <?php checked(1, isset($payment_methods['bizum']) ? $payment_methods['bizum'] : 0); ?> />
                <?php _e('Bizum', 'ctf'); ?>
            </label><br/>
            <label>
                <input type="checkbox" name="ctf_payment_methods[googlepay]" value="1" <?php checked(1, isset($payment_methods['googlepay']) ? $payment_methods['googlepay'] : 0); ?> />
                <?php _e('Google Pay', 'ctf'); ?>
            </label><br/>
            <label>
                <input type="checkbox" name="ctf_payment_methods[applepay]" value="1" <?php checked(1, isset($payment_methods['applepay']) ? $payment_methods['applepay'] : 0); ?> />
                <?php _e('Apple Pay', 'ctf'); ?>
            </label><br/>

            <h2><?php _e('Page Settings', 'ctf'); ?></h2>
            <label>
                <?php _e('Plugin Page:', 'ctf'); ?><br/>
                <input type="text" name="ctf_plugin_page" value="<?php echo esc_attr($plugin_page); ?>" />
            </label><br/>
            <label>
                <?php _e('Plugin Payment:', 'ctf'); ?><br/>
                <input type="text" name="ctf_plugin_payment" value="<?php echo esc_attr($plugin_payment); ?>" />
            </label><br/>
            <label>
                <?php _e('Thank You Page:', 'ctf'); ?><br/>
                <input type="text" name="ctf_thank_you_page" value="<?php echo esc_attr($thank_you_page); ?>" />
            </label><br/>
            <br/>
            <input type="submit" name="ctf_settings_submit" value="<?php _e('Save Settings', 'ctf'); ?>" class="button-primary" />
        </form>
    </div>
    <?php
}
?>
