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
$path = $_SERVER['DOCUMENT_ROOT'];

include_once $path . '/wordpress/wp-config.php';
include_once $path . '/wordpress/wp-load.php';

require_once plugin_dir_path(__FILE__) .'../savestripeoption/stripeSession.php'; 
include_once 'updatepaiment.php';
function paymentfinish($customer){
    // updatePaymentStatus($id);
    return deleteCookie($customer);
}


function deleteCookie($customer){
    
    if( updatePaymentStatus(getIdUser(), $customer) === 1){
          $deleteCookie =  delete_option($_COOKIE['chocol_cookie']) ; 
          return $deleteCookie;
    }
    
}


