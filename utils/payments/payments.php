<?php
require_once plugin_dir_path(__FILE__) . '../../admin/outPutMail/sendEmail.php';

$redsysAPIwoo = WP_PLUGIN_DIR . '/redsyspur/apiRedsys/apiRedsysFinal.php';
require_once ($redsysAPIwoo);

$miObj = new RedsysAPI;

$version = $_POST["Ds_SignatureVersion"];
$params = $_POST["Ds_MerchantParameters"];
$signatureRecibida = $_POST["Ds_Signature"];

$decodec = $miObj->decodeMerchantParameters($params);
$decodedParams = json_decode($decodec, true);

$codigoRespuesta = $decodedParams["Ds_Response"];
$payerID = $decodedParams["Ds_Order"];
$rowID = $decodedParams["Ds_MerchantData"];
$paymentType = $decodedParams["Ds_TransactionType"];


$claveModuloAdmin = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
$signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

if ($signatureCalculada === $signatureRecibida) {
    if ($codigoRespuesta == "0000") {
        global $wpdb;
        $tablename = $wpdb->prefix . 'chocoletras_plugin';

        $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %s", $rowID);
        $result = $wpdb->get_row($query);

        if ($result) {
            $paymentDescription = ($paymentType == "0") ? "Redsys" : (($paymentType == "7") ? "Bizum" : $paymentType);

            $update_query = $wpdb->prepare(
                "UPDATE $tablename SET uoi = %s, pagoRealizado = 1, payment = %s WHERE id = %s",
                $payerID,
                $paymentDescription,
                $rowID
            );
            $wpdb->query($update_query);

            // Prepare email data
            $upcomingData = [
                'email' => $result->email, // Adjust as necessary
                'status' => 'nuevo', // or 'envio' based on your logic
                'rowID' => $result->id
            ];

            // Send the email
            $emailResult = sendEmail($upcomingData);
            echo $emailResult;


        }

        ?>
        <script>
            document.cookie = `chocol_cookie=; Secure; Max-Age=-35120; path=/`;
            document.cookie = `chocoletraOrderData=; Secure; Max-Age=-35120; path=/`;
            document.cookie = `paypamentType=; Secure; Max-Age=-35120; path=/`;
        </script>
        <?php
    }
}


if (isset($_COOKIE['chocoletraOrderData'])) {
    $getOrderData = json_decode(stripslashes($_COOKIE['chocoletraOrderData']), true);
}

if (isset($_GET['payment']) && $_GET['payment'] == true) {
    ?>
    <script>
        document.cookie = `chocol_cookie=; Secure; Max-Age=-35120; path=/`;
        document.cookie = `chocoletraOrderData=; Secure; Max-Age=-35120; path=/`;
        document.cookie = `paypamentType=; Secure; Max-Age=-35120; path=/`;
        console.log("Payment True");
    </script>
<?php }
function paymentFrontend()
{
    if (isset($_GET['abandoned'])) {
        global $wpdb;
        $tablename = $wpdb->prefix . 'chocoletras_plugin';

        $abandonedProd = $_GET['abandoned'];

        $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %s", $abandonedProd);
        $result = $wpdb->get_row($query, ARRAY_A);
    }

    if (isset($_COOKIE['chocoletraOrderData'])) {
        $getOrderData = json_decode(stripslashes($_COOKIE['chocoletraOrderData']), true);
    }
    ?>

    <div style="display:none;" class="chocoletrasPlg__wrapperCode-payment-buttons-left">
        <?php
        $plugin_page = get_option('ctf_settings')['plugin_page'];
        $plugin_payment = get_option('ctf_settings')['plugin_payment'];
        $thank_you_page = get_option('ctf_settings')['thank_you_page'];

        // PayPal Configuration
        // define('PAYPAL_EMAIL', 'chocoletra2020@gmail.com');
        define('PAYPAL_EMAIL', 'sb-hjjsi25330300@business.example.com');
        define('RETURN_URL', "$plugin_page?payment=true");
        define('CANCEL_URL', $plugin_payment);
        define('NOTIFY_URL', "$thank_you_page?payment=true");
        define('PAYPAL_CURRENCY', 'EUR');
        define('SANDBOX', TRUE); // TRUE or FALSE
        define('LOCAL_CERTIFICATE', FALSE); // TRUE or FALSE
    
        if (SANDBOX === TRUE) {
            $paypal_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        } else {
            $paypal_url = "https://www.paypal.com/cgi-bin/webscr";
        }
        define('PAYPAL_URL', $paypal_url);

        // Handle IPN
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['txn_id'])) {
            // Database connection
            global $wpdb;

            // Read POST data
            $raw_post_data = file_get_contents('php://input');
            $raw_post_array = explode('&', $raw_post_data);
            $myPost = array();
            foreach ($raw_post_array as $keyval) {
                $keyval = explode('=', $keyval);
                if (count($keyval) == 2) {
                    $myPost[$keyval[0]] = urldecode($keyval[1]);
                }
            }

            // Read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
            $req = 'cmd=_notify-validate';
            foreach ($myPost as $key => $value) {
                $value = urlencode($value);
                $req .= "&$key=$value";
            }

            // Post IPN data back to PayPal to validate the IPN data is genuine
            $ch = curl_init(PAYPAL_URL);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));

            $res = curl_exec($ch);
            curl_close($ch);

            // Inspect IPN validation result and act accordingly
            if (strcmp($res, "VERIFIED") == 0) {
                // Payment data variables
                $payment_status = $_POST['payment_status'];
                $txn_id = $_POST['txn_id'];
                $payer_email = $_POST['payer_email'];
                $item_number = $_POST['item_number']; // Assuming item_number contains your custom order ID
    
                if ($payment_status == "Completed") {
                    // Database update logic
                    $tablename = $wpdb->prefix . 'chocoletras_plugin';
                    $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %s", $item_number);
                    $result = $wpdb->get_row($query);

                    if ($result) {
                        // Assume paymentType is derived from PayPal data
                        $paymentType = 'PayPal'; // or other logic to determine payment type
                        $paymentDescription = ($paymentType == "0") ? "Redsys" : (($paymentType == "7") ? "Bizum" : $paymentType);

                        $update_query = $wpdb->prepare(
                            "UPDATE $tablename SET uoi = %s, pagoRealizado = 1, payment = %s WHERE id = %s",
                            $txn_id,
                            $paymentDescription,
                            $item_number
                        );
                        $wpdb->query($update_query);

                        // Prepare email data
                        $upcomingData = [
                            'email' => $result->email,
                            'status' => 'nuevo', // or 'envio' based on your logic
                            'rowID' => $result->id
                        ];

                        // Send the email
                        $emailResult = sendEmail($upcomingData);
                        error_log($emailResult);
                    }
                }
            } else if (strcmp($res, "INVALID") == 0) {
                error_log("Invalid IPN: $req");
            }
            exit;
        }

        function generateRandomOrderNumberRedsys(int $lengthRedsys = 10): string
        {
            $charactersRedsys = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomStringRedsys = '';
            for ($i = 0; $i < $lengthRedsys; $i++) {
                $randomStringRedsys .= $charactersRedsys[rand(0, strlen($charactersRedsys) - 1)];
            }
            return $randomStringRedsys;
        }

        $orderNumberRedsys = generateRandomOrderNumberRedsys();

        function generateRandomOrderNumberBizum(int $lengthBizum = 10): string
        {
            $charactersBizum = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomStringBizum = '';
            for ($i = 0; $i < $lengthBizum; $i++) {
                $randomStringBizum .= $charactersBizum[rand(0, strlen($charactersBizum) - 1)];
            }
            return $randomStringBizum;
        }

        $orderNumberBizum = generateRandomOrderNumberBizum();


        function generateRandomOrderNumberGoogle(int $lengthGoogle = 10): string
        {
            $charactersGoogle = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomStringGoogle = '';
            for ($i = 0; $i < $lengthGoogle; $i++) {
                $randomStringGoogle .= $charactersGoogle[rand(0, strlen($charactersGoogle) - 1)];
            }
            return $randomStringGoogle;
        }

        $orderNumberGoogle = generateRandomOrderNumberGoogle();

        $redsysAPIwoo = WP_PLUGIN_DIR . '/redsyspur/apiRedsys/apiRedsysFinal.php';

        require_once ($redsysAPIwoo);

        $miObj = new RedsysAPI;
        if (!empty($result)) {
            $amount = $result['precio'];
            $insertedID = $result['id'];
        } else {
            $amount = $getOrderData['priceTotal'];
            $insertedID = $getOrderData['inserted_id'];
        }
        echo 'checkingamount' . $amount;
        $amount = $amount ? str_replace('.', '', $amount) : 'null';
        $amount = $amount ? explode('_', $amount)[0] : 'null';

        // Check the length of the amount
        if (strlen($amount) == 3) {
            // Add "0" at the end
            $amount = $amount . "0";
        } elseif (strlen($amount) == 2) {
            // Add "00" at the end
            $amount = $amount . "00";
        }


        $miObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
        $miObj->setParameter("DS_MERCHANT_ORDER", $orderNumberRedsys);
        $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", "340873405");
        $miObj->setParameter("DS_MERCHANT_CURRENCY", "978");
        $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "0");
        $miObj->setParameter("DS_MERCHANT_TERMINAL", "001");
        $miObj->setParameter("DS_MERCHANT_MERCHANTDATA", $insertedID);
        $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $plugin_page);
        $miObj->setParameter("DS_MERCHANT_URLOK", "$plugin_payment?payment=true");
        $miObj->setParameter("DS_MERCHANT_URLKO", $thank_you_page);

        $params = $miObj->createMerchantParameters();
        // $claveSHA256 = 'qdBg81KwXKi+QZpgNXoOMfBzsVhBT+tm';
        $claveSHA256 = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
        $firma = $miObj->createMerchantSignature($claveSHA256);
        ?>
        <form id="payRedsys" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
            <!-- <form id="payRedsys" action="https://sis.redsys.es/sis/realizarPago" method="POST"> -->
            <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" />
            <input type="hidden" name="Ds_MerchantParameters" value="<?php echo $params; ?>" />
            <input type="hidden" name="Ds_Signature" value="<?php echo $firma; ?>" />
            <button type="submit"><span>
                    <?php echo _e('Pagar con Tarjeta '); ?>
                </span><img src="https://chocoletra.com/wp-content/uploads/2024/03/redsys-tarjetas.png"
                    alt="<?php echo _e('Chocoletra'); ?>"></button>
        </form>
    </div>
    <div style="display:none;" class="chocoletrasPlg__wrapperCode-payment-buttons-left">
        <?php
        // echo $lastCookieVal;
        $bizumObj = new RedsysAPI;

        // $bizumObj->setParameter("DS_MERCHANT_AMOUNT", 10);
        $bizumObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
        $bizumObj->setParameter("DS_MERCHANT_ORDER", $orderNumberBizum);
        $bizumObj->setParameter("DS_MERCHANT_MERCHANTCODE", "340873405");
        $bizumObj->setParameter("DS_MERCHANT_CURRENCY", "978");
        $bizumObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "7");
        $bizumObj->setParameter("DS_MERCHANT_TERMINAL", "001");
        $bizumObj->setParameter("DS_MERCHANT_PAYMETHODS", "z");
        $bizumObj->setParameter("DS_MERCHANT_MERCHANTDATA", $insertedID);
        $bizumObj->setParameter("DS_MERCHANT_MERCHANTURL", $plugin_page);
        $bizumObj->setParameter("DS_MERCHANT_URLOK", "$plugin_payment?payment=true");
        $bizumObj->setParameter("DS_MERCHANT_URLKO", $thank_you_page);

        $bizumparams = $bizumObj->createMerchantParameters();
        // $bizumclaveSHA256 = 'qdBg81KwXKi+QZpgNXoOMfBzsVhBT+tm';
        $bizumclaveSHA256 = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
        $bizumfirma = $bizumObj->createMerchantSignature($bizumclaveSHA256);

        ?>
        <form id="payBizum" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
            <!-- <form id="payBizum" action="https://sis.redsys.es/sis/realizarPago" method="POST"> -->
            <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" />
            <input type="hidden" name="Ds_MerchantParameters" value="<?php echo $bizumparams; ?>" />
            <input type="hidden" name="Ds_Signature" value="<?php echo $bizumfirma; ?>" />
            <button type="submit"><span>
                    <?php echo _e('Pagar con Bizum '); ?>
                </span><img src="https://chocoletra.com/wp-content/uploads/2024/03/Bizum.svg.png"
                    alt="<?php echo _e('Chocoletra'); ?>"></button>
        </form>

    </div>

    <div style="display:none;" class="chocoletrasPlg__wrapperCode-payment-buttons-left">
        <?php
        // echo $lastCookieVal;
        $goggleObj = new RedsysAPI;

        // $goggleObj->setParameter("DS_MERCHANT_AMOUNT", 10);
        $goggleObj->setParameter("DS_MERCHANT_AMOUNT", $amount);
        $goggleObj->setParameter("DS_MERCHANT_ORDER", $orderNumberGoogle);
        $goggleObj->setParameter("DS_MERCHANT_MERCHANTCODE", "340873405");
        $goggleObj->setParameter("DS_MERCHANT_CURRENCY", "978");
        $goggleObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", "7");
        $goggleObj->setParameter("DS_MERCHANT_TERMINAL", "001");
        $goggleObj->setParameter("DS_MERCHANT_PAYMETHODS", "xpay");
        $goggleObj->setParameter("DS_MERCHANT_MERCHANTDATA", $insertedID);
        $goggleObj->setParameter("DS_MERCHANT_MERCHANTURL", $plugin_page);
        $goggleObj->setParameter("DS_MERCHANT_URLOK", "$plugin_payment?payment=true");
        $goggleObj->setParameter("DS_MERCHANT_URLKO", $thank_you_page);

        $goggleparams = $goggleObj->createMerchantParameters();
        // $goggleclaveSHA256 = 'qdBg81KwXKi+QZpgNXoOMfBzsVhBT+tm';
        $goggleclaveSHA256 = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
        $goggleirma = $goggleObj->createMerchantSignature($goggleclaveSHA256); ?>
        <form id="payGoogle" action="https://sis-t.redsys.es:25443/sis/realizarPago" method="POST">
            <!-- <form id="payGoogle" action="https://sis.redsys.es/sis/realizarPago" method="POST"> -->
            <input type="hidden" name="Ds_SignatureVersion" value="HMAC_SHA256_V1" />
            <input type="hidden" name="Ds_MerchantParameters" value="<?php echo $goggleparams; ?>" />
            <input type="hidden" name="Ds_Signature" value="<?php echo $goggleirma; ?>" />
            <button type="submit"><span>
                    <?php echo _e('Pagar con Bizum '); ?>
                </span><img src="https://chocoletra.com/wp-content/uploads/2024/03/Bizum.svg.png"
                    alt="<?php echo _e('Chocoletra'); ?>"></button>
        </form>

    </div>

    <div style="display:none;" class="chocoletrasPlg__wrapperCode-payment-buttons-left">
        <form id="payPayPal" action="https://www.sandbox.paypal.com/cgi-bin/webscr<?php // echo PAYPAL_URL; ?>"
            method="post">
            <!-- PayPal business email to collect payments -->
            <input type='hidden' name='business' value="<?php echo PAYPAL_EMAIL; ?>">

            <input type="hidden" name="item_name" value="<?php echo $getOrderData['fname']; ?>">
            <input type="hidden" name="item_number" value="<?php echo $getOrderData['uoi']; ?>">
            <input type="hidden" name="amount" value="<?php echo $getOrderData['priceTotal']; ?>">
            <input type="hidden" name="currency_code" value="<?php echo PAYPAL_CURRENCY; ?>">
            <input type='hidden' name='no_shipping' value='1'>
            <input type="hidden" name="lc" value="" />
            <input type="hidden" name="no_note" value="1" />
            <input type="hidden" name="page_style" value="paypal" />
            <input type="hidden" name="charset" value="utf-8" />

            <!-- PayPal return, cancel & IPN URLs -->
            <input type='hidden' name='return' value="<?php echo RETURN_URL; ?>">
            <input type='hidden' name='cancel_return' value="<?php echo CANCEL_URL; ?>">
            <input type='hidden' name='notify_url' value="<?php echo NOTIFY_URL; ?>">

            <!-- Specify a Pay Now button. -->
            <input type="hidden" name="cmd" value="_xclick">




            <!-- Display the payment button. -->
            <?php // echo $lastCookieVal;   ?>
            <Button type="submit"><span>
                    <?php echo _e('Pagar con PayPal '); ?>
                </span><img
                    src="https://chocoletra.com/wp-content/uploads/2024/03/new-PayPal-Logo-horizontal-full-color-png.png"
                    alt="<?php echo _e('Chocoletras'); ?>"></Button>

        </form>

    </div>

<?php } ?>