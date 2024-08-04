<?php

/**
 * 
 * author: M. Sufyan Shaikh
 * description: process form and send info to cookie
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 * 
 */

require_once plugin_dir_path(__FILE__) . '../emailModels/modelemail.php';

use PHPMailer\PHPMailer2\Exception;
use PHPMailer\PHPMailer2\PHPMailer;
use PHPMailer\PHPMailer2\SMTP;

require_once plugin_dir_path(__FILE__) . '../PHPMailer-master/src/Exception.php';
require_once plugin_dir_path(__FILE__) . '../PHPMailer-master/src/PHPMailer.php';
require_once plugin_dir_path(__FILE__) . '../PHPMailer-master/src/SMTP.php';

function sendEmail($upcomingData)
{
    global $wpdb;

    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0; // Change to 2 for detailed debug info
    $mail->Host = get_option("ouputCltHost");
    $mail->Port = get_option("ouputCltPort");
    $mail->SMTPSecure = get_option("ouputCltSecure");
    $mail->SMTPAuth = true;
    $mail->Username = get_option("ouputCltemail");
    $mail->Password = get_option("ouputCltPass");
    $mail->SetFrom(get_option("ouputCltemail"), 'Chocoletra');

    // Prepare email content and subject based on the status
    switch ($upcomingData['status']) {
        case 'abandoned':
            // Fetch data from the database based on the row ID
            $tablename = $wpdb->prefix . 'chocoletras_plugin';
            $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %d", $upcomingData['rowID']);
            $result = $wpdb->get_row($query);

            if (!$result) {
                return 'No data found for the given row ID.';
            }

            $mail->AddAddress($result->email, 'User');
            $mail->Subject = 'Continuar Compra!';
            $emailContent = modelemail('abandoned', $result);
            $mail->AltBody = 'Your product is in production!';
            break;

        case 'nuevo':
            // Fetch data from the database based on the row ID
            $tablename = $wpdb->prefix . 'chocoletras_plugin';
            $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %d", $upcomingData['rowID']);
            $result = $wpdb->get_row($query);

            if (!$result) {
                return 'No data found for the given row ID.';
            }

            $mail->AddAddress($result->email, 'User');
            $mail->Subject = 'Nuevo Pedido';
            $emailContent = modelemail('nuevo', $result);
            $mail->AltBody = 'Your product is in production!';
            break;

        case 'proceso':
            $mail->AddAddress($upcomingData['email'], 'User');
            $mail->Subject = 'En proceso';
            $emailContent = modelemail('proceso'); // No database data needed
            $mail->AltBody = 'Your product is in production!';
            break;

        case 'envio':
            $mail->AddAddress($upcomingData['email'], 'User');
            $mail->Subject = 'Chocoletra';
            $emailContent = modelemail('envio'); // No database data needed
            $mail->AltBody = 'Your product was sent!';
            break;

        case 'eliminar':
            $mail->AddAddress($upcomingData['email'], 'User');
            $mail->Subject = 'Order Removed';
            $emailContent = 'Your order has been removed from the system.';
            $mail->AltBody = 'Order removed notification.';
            break;

        default:
            return 'Invalid status provided.';
    }

    $mail->MsgHTML($emailContent);

    if (!$mail->Send()) {
        return "Error: " . $mail->ErrorInfo;
    } else {
        return 'Successful';
    }
}
