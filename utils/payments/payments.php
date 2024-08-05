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
$paidAmount = $decodedParams["Ds_Amount"];
$paymentType = $decodedParams["Ds_TransactionType"];

$formattedAmount = number_format($paidAmount / 100, 2, '.', '');

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
                "UPDATE $tablename SET uoi = %s, pagoRealizado = 1, payment = %s, precio = %f WHERE id = %s",
                $payerID,
                $paymentDescription,
                $formattedAmount,
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

        function log_ipn($message)
        {
            $file = fopen('ipn_log.txt', 'a');
            fwrite($file, date('[Y-m-d H:i e] ') . $message . PHP_EOL);
            fclose($file);
        }

        log_ipn("IPN script started");

        // PayPal Configuration
        define('PAYPAL_EMAIL', 'sb-hjjsi25330300@business.example.com');
        define('RETURN_URL', "$plugin_page?payment=true");
        define('CANCEL_URL', $plugin_payment);
        define('NOTIFY_URL', "$thank_you_page");
        define('PAYPAL_CURRENCY', 'EUR');
        define('SANDBOX', TRUE);
        define('LOCAL_CERTIFICATE', FALSE);

        $paypal_url = SANDBOX ? "https://ipnpb.sandbox.paypal.com/cgi-bin/webscr" : "https://ipnpb.paypal.com/cgi-bin/webscr";
        define('PAYPAL_URL', $paypal_url);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['txn_id'])) {
            log_ipn("Received POST request with txn_id: " . $_POST['txn_id']);

            global $wpdb;

            // Log all POST data
            foreach ($_POST as $key => $value) {
                log_ipn("Array data: $key => $value");
            }

            $raw_post_data = file_get_contents('php://input');
            $raw_post_array = explode('&', $raw_post_data);
            $myPost = [];
            foreach ($raw_post_array as $keyval) {
                $keyval = explode('=', $keyval);
                if (count($keyval) == 2) {
                    $myPost[$keyval[0]] = urldecode($keyval[1]);
                }
            }

            $req = 'cmd=_notify-validate';
            foreach ($myPost as $key => $value) {
                $value = urlencode($value);
                $req .= "&$key=$value";
            }

            $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Connection: Close']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10); // Max number of redirects to follow
    
            // cURL debug information
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            $verbose = fopen('curl_debug.txt', 'w+');
            curl_setopt($ch, CURLOPT_STDERR, $verbose);

            $res = curl_exec($ch);
            if ($res === FALSE) {
                log_ipn("cURL error: " . curl_error($ch));
            } else {
                log_ipn("cURL response: " . $res);
            }
            fclose($verbose);
            curl_close($ch);


            if (strcmp($res, "VERIFIED") == 0) {

                $payment_status = $_POST['payment_status'];
                $txn_id = $_POST['txn_id'];
                $payer_email = $_POST['payer_email'];
                $item_number = $_POST['item_number'];
                $paidPrice = $_POST['mc_gross'];


                if ($payment_status == "Completed") {
                    $tablename = $wpdb->prefix . 'chocoletras_plugin';
                    $query = $wpdb->prepare("SELECT * FROM $tablename WHERE id = %s", $item_number);
                    $result = $wpdb->get_row($query);

                    if ($result) {
                        $paymentType = 'PayPal';
                        $paymentDescription = $paymentType;

                        $update_query = $wpdb->prepare(
                            "UPDATE $tablename SET uoi = %s, pagoRealizado = 1, payment = %s, precio = %f WHERE id = %s",
                            $txn_id,
                            $paymentDescription,
                            $paidPrice,
                            $item_number
                        );

                        $wpdb->query($update_query);
                        log_ipn("Database updated for order ID: " . $item_number);

                        $upcomingData = [
                            'email' => $result->email,
                            'status' => 'nuevo',
                            'rowID' => $result->id
                        ];

                        $emailResult = sendEmail($upcomingData);
                        log_ipn("Email result: " . $emailResult);
                    } else {
                        log_ipn("Order ID not found: " . $item_number);
                    }
                } else {
                    log_ipn("Payment status not completed: " . $payment_status);
                }
            } else if (strcmp($res, "INVALID") == 0) {
                log_ipn("IPN INVALID: " . $req);
            }
            exit;
        }
        log_ipn("No POST data or txn_id not set");



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

        $priceTotal = $result['precio'];

        // Retrieve coupon parameter from URL
        $couponParam = isset($_GET['coupon']) ? sanitize_text_field($_GET['coupon']) : '';

        // Check and apply coupon discount
        if ($couponParam) {
            $discount = getCouponDiscount($couponParam);
            if ($discount) {
                $priceTotal = applyCouponDiscount($priceTotal, $discount['type'], $discount['value']);
            }
        }
        if (!empty($result)) {
            $amount = $priceTotal;
            $insertedID = $result['id'];
        } else {
            $amount = $getOrderData['priceTotal'];
            $insertedID = $getOrderData['inserted_id'];
        }
        // echo 'checkingamount' . $amount;
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

        echo 'final ammount' . $amount;

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

    <?php
    if (!empty($result)) {
        $payPalamount = $priceTotal;
    } else {
        $payPalamount = $getOrderData['priceTotal'];
    }
    ?>

    <div style="display:none;" class="chocoletrasPlg__wrapperCode-payment-buttons-left">
        <form id="payPayPal" action="https://ipnpb.sandbox.paypal.com/cgi-bin/webscr<?php // echo PAYPAL_URL; ?>"
            method="post">
            <!-- PayPal business email to collect payments -->
            <input type='hidden' name='business' value="<?php echo PAYPAL_EMAIL; ?>">

            <input type="hidden" name="item_name" value="<?php echo $getOrderData['fname']; ?>">
            <input type="hidden" name="item_number" value="<?php echo $getOrderData['inserted_id']; ?>">
            <input type="hidden" name="amount" value="<?php echo $payPalamount; ?>">

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