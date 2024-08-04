<?php

require_once plugin_dir_path(__FILE__) . '../../admin/outPutMail/sendEmail.php';

function check_abandoned_cart()
{
    global $wpdb;
    $tablename = $wpdb->prefix . 'chocoletras_plugin';

    // Get orders that are unpaid, cart is not set, and older than 1 minute
    $results = $wpdb->get_results("SELECT * FROM $tablename WHERE pagoRealizado = 0 AND cart = 0 AND TIMESTAMPDIFF(MINUTE, fecha, NOW()) > 1");


    foreach ($results as $result) {
        // Prepare email data
        $upcomingData = [
            'email' => $result->email, // Adjust as necessary
            'status' => 'abandoned', // or 'envio' based on your logic
            'rowID' => $result->id
        ];

        // Send the email
        $emailResult = sendEmail($upcomingData);

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
