<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function coupon_settings_page()
{
    // Handle form submissions
    if (isset($_POST['coupon_nonce']) && wp_verify_nonce($_POST['coupon_nonce'], 'save_coupon')) {
        $coupons = get_option('coupons', []);
        $coupon = [
            'name' => sanitize_text_field($_POST['coupon_name']),
            'type' => sanitize_text_field($_POST['coupon_type']),
            'value' => floatval($_POST['coupon_value']),
            'expiration' => sanitize_text_field($_POST['coupon_expiration']),
            'usage_limit' => intval($_POST['coupon_usage_limit']),
            'usage_count' => 0,
        ];
        $coupons[] = $coupon;
        update_option('coupons', $coupons);
    }

    // Handle delete coupon action
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['index'])) {
        $coupons = get_option('coupons', []);
        unset($coupons[intval($_GET['index'])]);
        update_option('coupons', array_values($coupons));

        wp_safe_redirect(admin_url('admin.php?page=coupons_settings'));
        exit;
    }

    $coupons = get_option('coupons', []);

    ?>
    <div class="wrap">
        <h2>Coupon Settings</h2>
        <form method="post" action="">
            <?php wp_nonce_field('save_coupon', 'coupon_nonce'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Coupon Name</th>
                    <td><input type="text" name="coupon_name" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Type of Deduction</th>
                    <td>
                        <select name="coupon_type" required>
                            <option value="percentage">Percentage</option>
                            <option value="fixed">Fixed Price</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Value</th>
                    <td><input type="number" name="coupon_value" step="0.01" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Expiration Date</th>
                    <td><input type="date" name="coupon_expiration" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Usage Limit</th>
                    <td><input type="number" name="coupon_usage_limit" required /></td>
                </tr>
            </table>
            <?php submit_button('Add Coupon'); ?>
        </form>

        <h2>All Coupons</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Expiration</th>
                    <th>Usage Limit</th>
                    <th>Usage Count</th>
                    <th>Remaining Usage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($coupons)): ?>
                    <?php foreach ($coupons as $index => $coupon): ?>
                        <tr>
                            <td><?php echo esc_html($coupon['name']); ?></td>
                            <td><?php echo esc_html($coupon['type']); ?></td>
                            <td><?php echo esc_html($coupon['value']); ?></td>
                            <td><?php echo esc_html($coupon['expiration']); ?></td>
                            <td><?php echo esc_html($coupon['usage_limit']); ?></td>
                            <td><?php echo esc_html($coupon['usage_count']); ?></td>
                            <td><?php echo esc_html($coupon['usage_limit'] - $coupon['usage_count']); ?></td>
                            <td>
                                <a href="<?php echo add_query_arg(['action' => 'delete', 'index' => $index]); ?>"
                                    onclick="return confirm('Are you sure you want to delete this coupon?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No coupons found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
