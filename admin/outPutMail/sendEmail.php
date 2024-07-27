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
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->SMTPDebug = 0;
  $mail->Host = get_option("ouputCltHost"); //'smtp.ionos.es'; 
  $mail->Port = get_option("ouputCltPort"); // 587; 
  $mail->SMTPSecure = get_option("ouputCltSecure"); //'tls'; 
  $mail->SMTPAuth = true;
  $mail->Username = get_option("ouputCltemail"); //"ricardo@lavour.es"
  $mail->Password = get_option("ouputCltPass"); // "Brembo3030"; 
  $mail->SetFrom(get_option("ouputCltemail"), 'Chocoletra'); // $mail->SetFrom('ricardo@lavour.es', 'Chocoletras'); 
  $result;

  switch ($upcomingData['status']) {
    case 'nuevo':

      $mail->AddAddress($upcomingData['email'], 'User');
      $mail->Subject = 'Nuevo Pedido';
      $mail->MsgHTML(modelemail('nuevo'));
      $mail->AltBody = 'Your product is in production!';
      if (!$mail->Send()) {
        $result = "Error: " . $mail->ErrorInfo;
      } else {
        $result = 'sucessfull';
      }
      break;

    case 'proceso':

      $mail->AddAddress($upcomingData['email'], 'User');
      $mail->Subject = 'En proceso';
      $mail->MsgHTML(modelemail('proceso'));
      $mail->AltBody = 'Your product is in production!';
      if (!$mail->Send()) {
        $result = "Error: " . $mail->ErrorInfo;
      } else {
        $result = 'sucessfull';
      }
      break;
    case 'envio':

      $mail->AddAddress($upcomingData['email'], 'User');
      $mail->Subject = 'Chocoletra';
      $mail->MsgHTML(modelemail('envio'));
      $mail->AltBody = 'Your product was send!';
      if (!$mail->Send()) {
        $result = "Error: " . $mail->ErrorInfo;
      } else {
        $result = 'sucessfull';
      }
      break;
    case 'eliminar':

      $result = 'sucessfull';

      break;
  }
  // return $result;
}

if (isset($_GET['payment']) && $_GET['payment'] == 'true') {
  // Check if the cookie "chocoletraOrderData" is set
  if (isset($_COOKIE['chocoletraOrderData'])) {
    // Decode the JSON data from the cookie
    $getOrderData = json_decode(stripslashes($_COOKIE['chocoletraOrderData']), true);

    // Extract the user's email from the decoded data
    if (isset($getOrderData['email'])) {
      $upcomingData = [
        'email' => $getOrderData['email'],
        'status' => 'nuevo' // or 'envio' based on your logic
      ];

      // Send the email
      $result = sendEmail($upcomingData);
      echo $result;
    } else {
      echo "User email not found in the cookie.";
    }
  } else {
    echo "Order data cookie not found.";
  }
}
//else {
//   echo "Payment parameter not set or not true.";
// }
