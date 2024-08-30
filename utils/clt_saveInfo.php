<?php

/**
 * Author: M. Sufyan Shaikh
 * Description: Process form and send info to cookie
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 */

function responseForm()
{
    try {
        $response = array('Datos' => confirmAllIsReady());
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        echo json_encode(array('error' => $e->getMessage()));
        exit;
    }
}

function confirmAllIsReady()
{
    setcookie('chocol_price', '', time() - 3600);
    $getData = array('mainText', 'chocoType', 'priceTotal', 'fname', 'email', 'tel', 'postal', 'city', 'address', 'province', 'message', 'picDate', 'shippingType', 'nonce', 'uoi', 'coupon', 'screens', 'featured', 'affiliateID');

    $confirm_error = array();

    foreach ($getData as $key) {
        if (isset($_POST[$key])) {
            $confirm_error[$key] = $_POST[$key];
        } else {
            throw new Exception("Missing required field: $key");
        }
    }

    return saveDataInDatabase($confirm_error);
}

function confirmViolationOfSequirity($incomingfrase)
{
    $confirmSequirity = preg_match('/[$^\*\(\)=\{\]\{\{\<\>\:\;]/', $incomingfrase);
    if ($confirmSequirity > 0) {
        throw new Exception('Invalid characters in frase');
    } else {
        return $incomingfrase;
    }
}

function saveDataInDatabase($datos)
{
    $sanitizeData = array();

    foreach ($datos as $info => $val) {
        switch ($info) {
            case 'mainText':
                $chocofraseArray = json_decode(stripslashes($datos[$info]), true);
                foreach ($chocofraseArray as $index => $frase) {
                    $chocofraseArray[$index] = confirmViolationOfSequirity($frase);
                }
                $sanitizeData[$info] = json_encode($chocofraseArray);
                break;
            case 'screens':
                $sanitizeData[$info] = stripslashes($datos[$info]); // No need to sanitize JSON strings
                break;
            default:
                $sanitizeData[$info] = sanitize_text_field($datos[$info]);
                break;
        }
    }

    global $wpdb;
    $tablename = $wpdb->prefix . 'chocoletras_plugin';

    $query = $wpdb->prepare(
        "INSERT INTO $tablename (frase, chocotype, precio, nombre, email, telefono, cp, ciudad, province, message, direccion, nonce, fechaEntrega, express, uoi, coupon, screens, featured, affiliate_id) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
        $sanitizeData['mainText'],
        $sanitizeData['chocoType'],
        $sanitizeData['priceTotal'],
        $sanitizeData['fname'],
        $sanitizeData['email'],
        $sanitizeData['tel'],
        $sanitizeData['postal'],
        $sanitizeData['city'],
        $sanitizeData['province'],
        $sanitizeData['message'],
        $sanitizeData['address'],
        $sanitizeData['nonce'],
        $sanitizeData['picDate'],
        $sanitizeData['shippingType'],
        $sanitizeData['uoi'],
        $sanitizeData['coupon'],
        $sanitizeData['screens'],
        $sanitizeData['featured'],
        $sanitizeData['affiliateID']
    );

    try {
        // Execute the query and get the result
        $result = $wpdb->query($query);

        // Check if the query executed successfully
        if ($result === false) {
            throw new Exception("Database error: " . $wpdb->last_error);
        }

        // Retrieve the ID of the last inserted row
        $inserted_id = $wpdb->insert_id;

        // Check if the ID was set
        if (empty($inserted_id)) {
            throw new Exception("Failed to retrieve inserted ID.");
        }

        // Fetch affiliate ID from wp_yith_wcaf_affiliates table based on affiliateID
        $affiliate_table = $wpdb->prefix . 'yith_wcaf_affiliates';
        $affiliate_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $affiliate_table WHERE user_id = %d",
            $sanitizeData['affiliateID']
        ));

        // Check if affiliateID is provided
        if (!empty($sanitizeData['affiliateID'])) {
            // Fetch affiliate ID from wp_yith_wcaf_affiliates table based on affiliateID
            $affiliate_table = $wpdb->prefix . 'yith_wcaf_affiliates';
            $affiliate_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM $affiliate_table WHERE user_id = %d",
                $sanitizeData['affiliateID']
            ));

            if (empty($affiliate_id)) {
                throw new Exception("Affiliate ID not found.");
            }

            // Insert data into wp_yith_wcaf_commissions table
            $commission_table = $wpdb->prefix . 'yith_wcaf_commissions';
            $last_line_item_id = $wpdb->get_var("SELECT MAX(line_item_id) FROM $commission_table");

            $line_item_id = $last_line_item_id ? $last_line_item_id + 1 : 1;
            $rate = get_option('yith_wcaf_general_rate', 0);
            $amount = $sanitizeData['priceTotal'] * ($rate / 100);
            $current_date = current_time('mysql');

            $commission_query = $wpdb->prepare(
                "INSERT INTO $commission_table (order_id, line_item_id, product_id, product_name, affiliate_id, rate, line_total, amount, refunds, status, created_at, last_edit) 
        VALUES (%d, %d, %d, %s, %d, %f, %f, %f, %d, %s, %s, %s)",
                $inserted_id,
                $line_item_id,
                $inserted_id,
                $sanitizeData['mainText'],
                $affiliate_id, // Use the fetched affiliate_id
                $rate,
                $sanitizeData['priceTotal'],
                $amount,
                0, // refunds
                'not-confirmed', // status
                $current_date, // created_at
                $current_date  // last_edit
            );

            // Execute the commission query and check for errors
            $commission_result = $wpdb->query($commission_query);
            if ($commission_result === false) {
                throw new Exception("Failed to insert commission data: " . $wpdb->last_error);
            }
        }


    } catch (Exception $error) {
        error_log($error->getMessage()); // Log the error message
        throw new Exception("Database error: " . $error->getMessage());
    }

    return $result === 1 ? array(
        "Status" => true,
        "inserted_id" => $inserted_id,
        "amount" => $sanitizeData['priceTotal'],
        "frase" => $sanitizeData['mainText'],
        "telef" => $sanitizeData['tel'],
        "femail" => $sanitizeData['email'],
        "fname" => $sanitizeData['fname'],
        "fcity" => $sanitizeData['city'],
        "faddress" => $sanitizeData['address'],
        "fuoi" => $sanitizeData['uoi'],
        "fcoupon" => $sanitizeData['coupon']
    ) : array("Status" => 400);
}