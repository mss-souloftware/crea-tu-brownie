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
    $getData = array('mainText', 'chocoType', 'priceTotal', 'fname', 'email', 'tel', 'postal', 'city', 'address', 'province', 'message', 'picDate', 'shippingType', 'nonce', 'uoi', 'coupon', 'screens', 'featured');

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
        "INSERT INTO $tablename (frase, chocotype, precio, nombre, email, telefono, cp, ciudad, province, message, direccion, nonce, fechaEntrega, express, uoi, coupon, screens, featured) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
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
        $sanitizeData['featured']
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

    } catch (Exception $error) {
        error_log($error->getMessage()); // Log the error message
        throw new Exception("Database error: " . $error->getMessage());
    }

    // $confirmSaveCookie;
    // $combinatedNameOption = $wpdb->insert_id . $sanitizeData['nonce'];
    // if ($result === 1) {
    //     // Saving data to cookie
    //     $cookieData = $sanitizeData['priceTotal'] . '_' . $sanitizeData['mainText'] . '_' . $sanitizeData['tel'] . '_' . $sanitizeData['uoi'];
    //     if (get_option($combinatedNameOption . '-chocol_price')) {
    //         $confirmSaveCookie = update_option($combinatedNameOption . '-chocol_price', $cookieData);
    //     } else {
    //         $confirmSaveCookie = add_option($combinatedNameOption . '-chocol_price', $cookieData);
    //     }
    // }

    return $result === 1 ? array(
        "Status" => true,
        "inserted_id" => $inserted_id,
        // "nonce" => $combinatedNameOption . '-chocol_price',
        "amount" => $sanitizeData['priceTotal'],
        "frase" => $sanitizeData['mainText'],
        "telef" => $sanitizeData['tel'],
        "femail" => $sanitizeData['email'],
        "fname" => $sanitizeData['fname'],
        "fcity" => $sanitizeData['city'],
        "faddress" => $sanitizeData['address'],
        "fuoi" => $sanitizeData['uoi'],
        "fcoupon" => $sanitizeData['coupon'],
        // "cookie" => $confirmSaveCookie
    ) : array("Status" => 400);
}
