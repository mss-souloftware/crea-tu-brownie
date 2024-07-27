<?php 
/**
 * 
 * author: M. Sufyan Shaikh
 * description: process form and send info to cookie
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * @info: this sen the info to js->payment->paymentWhitStripe.js
 * 
*/  
 
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wordpress/wp-config.php';
include_once $path . '/wordpress/wp-load.php';

  require_once plugin_dir_path(__FILE__) .'../vendor/stripe/stripe-php/init.php'; 
 
    
  \Stripe\Stripe::setApiKey(get_option('secretKey')); 


  function responseStripe(){

    if(isset($_COOKIE['chocol_cookie']) &&  get_option($_COOKIE['chocol_cookie']) ){ 
      define( 'PRICE', explode('_', get_option($_COOKIE['chocol_cookie']))[0] ); 
      
      if( count(explode('.', PRICE))  > 1 && strlen(intval(explode('.', PRICE)[1])) < 2){
           define('DOUBLEPRICE', intval(explode('.', PRICE)[0]) . intval(explode('.', PRICE)[1]) . 0 );
      }elseif(count(explode('.', PRICE))  > 1 && strlen(intval(explode('.', PRICE)[1])) > 1){
        define('DOUBLEPRICE', intval(explode('.', PRICE)[0]) . intval(explode('.', PRICE)[1]) );
       }else{
        $singPrice = intval(PRICE). 0 . 0;
           define('DOUBLEPRICE',  $singPrice );
       } 
    }


    
  try { 
   $outputChechout; 
    $outputChechout = \Stripe\Checkout\Session::create([ 
        'line_items' => [[ 
          'price_data' => [ 
                'product_data' => [ 
                    'name' => 'Antes de continuar, Confirme que el monto de su pago y frase son correctos!', 
                    'metadata' => [ 
                        'pro_id' => null
                    ] 
                ], 
                'unit_amount' => DOUBLEPRICE,  
                'currency' => 'eur', 
            ], 
            'quantity' => 1, 
            'description' =>  'A pagar por la Frase: '. explode('_', get_option($_COOKIE['chocol_cookie']))[1], 
        ]], 
        'mode' => 'payment', 
        'success_url' =>  get_option('siteurl'). get_option('pluginPage') .'/?payment=true', 
        'cancel_url' => get_option('siteurl'). get_option('pluginPage') . '/?paymentProcess=error', 
    ]); 
} catch(Exception $e) {  
    $outputChechout = $e->getMessage();  
} 
 

echo json_encode($outputChechout); 
 exit;

  }  