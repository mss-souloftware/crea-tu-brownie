<?php
/**
 * 
 * @package Crea Tu Brownie
 * @subpackage M. Sufyan Shaikh
 * 
 */

function affiliate_adminpanel()
{
    // Define the tabs and corresponding pages
    $tabs = [
        'affiliate_users' => 'Affiliate Users',
        'affc_settings' => 'Settings'
    ];

    // Get the current tab, defaulting to 'affiliate_users'
    $current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'affiliate_users';

    ?>
    <div class="wrap">
        <h2 class="nav-tab-wrapper">
            <?php foreach ($tabs as $tab_key => $tab_label): ?>
                <a href="?page=socios_afiliados&tab=<?php echo esc_attr($tab_key); ?>"
                    class="nav-tab <?php echo $current_tab === $tab_key ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($tab_label); ?>
                </a>
            <?php endforeach; ?>
        </h2>

        <div class="tab-content">
            <?php
            // Display content based on the current tab
            switch ($current_tab) {
                case 'affiliate_users':
                    affiliate_users_page();
                    break;

                case 'affc_settings':
                    affc_settings_page();
                    break;

                default:
                    affiliate_users_page();
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

function affiliate_users_page()
{
    global $wpdb;

    // Get the commission percentage from the settings
    $commission_percentage = get_option('yith_wcaf_general_rate', 0);

    // Get all users with the role 'affiliate_chocoletra'
    $args = [
        'role' => 'yith_affiliate',
        'orderby' => 'user_nicename',
        'order' => 'ASC'
    ];

    $affiliate_users = get_users($args);

    // Output table with affiliate users, number of orders, total sales, join date, and commission amount
    ?>
    <h2>Affiliate Users</h2>
    <?php if (!empty($affiliate_users)): ?>
        <table class="widefat">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Number of Orders</th>
                    <th>Total Sale</th>
                    <th>Commission</th>
                    <th>Join Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($affiliate_users as $user): ?>
                    <?php
                    // Query the number of orders and total sale for this user
                    $number_of_orders = $wpdb->get_var($wpdb->prepare(
                        "SELECT COUNT(*) FROM {$wpdb->prefix}chocoletras_plugin WHERE affiliate_id = %d",
                        $user->ID
                    ));

                    $total_sale = $wpdb->get_var($wpdb->prepare(
                        "SELECT SUM(precio) FROM {$wpdb->prefix}chocoletras_plugin WHERE affiliate_id = %d",
                        $user->ID
                    ));

                    // Calculate the commission amount
                    $commission_amount = ($total_sale * $commission_percentage) / 100;

                    // Format the join date
                    $join_date = date('Y-m-d', strtotime($user->user_registered));
                    ?>
                    <tr>
                        <td><?php echo esc_html($user->user_login); ?></td>
                        <td><?php echo esc_html($user->display_name); ?></td>
                        <td><?php echo esc_html($user->user_email); ?></td>
                        <td><?php echo esc_html($number_of_orders); ?></td>
                        <td><?php echo esc_html(number_format($total_sale, 2)); ?>€</td>
                        <td><?php echo esc_html(number_format($commission_amount, 2)); ?>€</td>
                        <td><?php echo esc_html($join_date); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No affiliate users found.</p>
    <?php endif; ?>
<?php
}

function affc_settings_page()
{
    // Check if the form has been submitted
    if (isset($_POST['submit'])) {
        // Update the options in the database
        update_option('affc_commission_percentage', sanitize_text_field($_POST['affc_commission_percentage']));
        update_option('affc_min_withdraw_limit', sanitize_text_field($_POST['affc_min_withdraw_limit']));
    }

    // Retrieve the current values from the database
    $commission_percentage = get_option('affc_commission_percentage', '');
    $min_withdraw_limit = get_option('affc_min_withdraw_limit', '');

    // Output the settings form
    ?>
    <div class="wrap">
        <h2>Affiliate Settings</h2>
        <form method="post" action="">
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">
                        <label for="affc_commission_percentage">Commission Percentage (%)</label>
                    </th>
                    <td>
                        <input type="number" name="affc_commission_percentage"
                            value="<?php echo esc_attr($commission_percentage); ?>" step="0.01" min="0" max="100" />
                        <p class="description">Set the commission percentage for affiliates.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="affc_min_withdraw_limit">Minimum Withdraw Limit</label>
                    </th>
                    <td>
                        <input type="number" name="affc_min_withdraw_limit"
                            value="<?php echo esc_attr($min_withdraw_limit); ?>" step="0.01" min="0" />
                        <p class="description">Set the minimum amount an affiliate can withdraw.</p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}