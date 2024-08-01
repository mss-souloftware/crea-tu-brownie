<?php
if (isset($_COOKIE['chocoletraOrderData'])) {
    $getOrderData = json_decode(stripslashes($_COOKIE['chocoletraOrderData']), true);
}


$redsysAPIwoo = WP_PLUGIN_DIR . '/redsyspur/apiRedsys/apiRedsysFinal.php';

require_once($redsysAPIwoo);

$miObj = new RedsysAPI;

// Capture the parameters from the notification
$version = $_POST["Ds_SignatureVersion"];
$params = $_POST["Ds_MerchantParameters"];
$signatureRecibida = $_POST["Ds_Signature"];

// Decode the Merchant Parameters
$decodec = $miObj->decodeMerchantParameters($params);

// Get the response code
$codigoRespuesta = $miObj->getParameter("Ds_Response");

// Validate the signature
$claveModuloAdmin = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
$signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

if ($signatureCalculada === $signatureRecibida) {
    // Signature is correct, proceed with further processing
    echo "FIRMA OK. Realizar tareas en el servidor";

    if ($codigoRespuesta == "0000") {
        // Payment was successful
        echo "Payment Successful";
        // Perform your logic here, e.g., update the order status in the database
        $payerID = $getOrderData['uoi'];
        $paymentType = $getOrderData['payment'];
        
        global $wpdb;
        $tablename = $wpdb->prefix . 'chocoletras_plugin';
        $query = $wpdb->prepare("SELECT * FROM $tablename WHERE uoi = %s", $payerID);
        $result = $wpdb->get_row($query);

        if ($result) {
            $getOrderData['payment'];
            $update_query = $wpdb->prepare(
                "UPDATE $tablename SET pagoRealizado = 1, payment = %s WHERE uoi = %s",
                $paymentType,
                $payerID
            );
            $wpdb->query($update_query);
        }

    } else {
        // Payment failed
        echo "Payment Failed. Response code: " . $codigoRespuesta;
    }
} else {
    // Invalid signature
    echo "FIRMA KO. Error, firma inválida";
}
