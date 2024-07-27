<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wordpress/wp-config.php';
include_once $path . '/wordpress/wp-load.php';


  
function finishProcessTripe(){ 
    $checkout_session;
    require_once plugin_dir_path(__FILE__) .'../../vendor/stripe/stripe-php/init.php'; 
    \Stripe\Stripe::setApiKey(get_option('secretKey')); 
    try {
        $checkout_session = \Stripe\Checkout\Session::retrieve(getIdVentaFromUser()); 
    } catch(Exception $e) {  
        $checkout_session = $e->getMessage(); 
    } 
   
   return $checkout_session; 
}

function getIdVentaFromUser(){ 
    require_once plugin_dir_path(__FILE__) .'../savestripeoption/stripeSession.php'; 
    $output;
    global $wpdb;  
    $tablename = $wpdb->prefix.'chocoletras_plugin';
  $result = $wpdb->get_results("SELECT id_venta FROM $tablename
            WHERE id = '".getIdUser()."' ", OBJECT);   
 foreach ($result as $key => $value) { 
       $output = $value->id_venta; 
 }
    return  $output;  
 }