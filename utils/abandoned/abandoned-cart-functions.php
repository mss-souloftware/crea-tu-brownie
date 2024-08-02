<?php
require_once plugin_dir_path(__FILE__) . '../../admin/outPutMail/sendEmail.php';

function check_abandoned_cart()
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'chocoletras_plugin';

    // Log that the function is being called
    error_log("check_abandoned_cart function called");

    // Get orders that are unpaid, cart is not set, and older than 1 minute
    $results = $wpdb->get_results("SELECT * FROM $tablename WHERE pagoRealizado = 0 AND cart = 0 AND TIMESTAMPDIFF(MINUTE, fecha, NOW()) > 1");

    // Log the query results
    if (empty($results)) {
        error_log("No unpaid orders found");
    } else {
        error_log("Found " . count($results) . " unpaid orders");
    }

    foreach ($results as $result) {
        // Prepare email data
        $upcomingData = [
            'email' => $result->email, // Adjust as necessary
            'status' => 'nuevo', // or 'envio' based on your logic
            'rowID' => $result->id
        ];

        // Log the email data
        error_log("Sending email to: " . $result->email . " for order ID: " . $result->id);

        // Send the email
        $emailResult = sendEmail($upcomingData);
        error_log("Email result: " . $emailResult);

        // Update the cart column to prevent resending the email
        $wpdb->update(
            $tablename,
            array('cart' => 1),
            array('id' => $result->id),
            array('%d'),
            array('%d')
        );
    }
}
add_action('check_abandoned_cart', 'check_abandoned_cart');
