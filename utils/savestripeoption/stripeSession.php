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


function tryTosaveStripeOption(){
               $output = ["result" => getIdForSaveData() ];
   echo json_encode($output);
   exit;
}
 
function getIdForSaveData(){
   if(isset($_POST['id']) && isset($_POST['intent'])){
       $id = $_POST['id'];
       $intent = $_POST['intent'];   
   }  

   // $getNonce = explode('-',  $_COOKIE['chocol_cookie'])[0];


   global $wpdb; 

   try {
      $tablename = $wpdb->prefix.'chocoletras_plugin';
      $result = $wpdb->query( 
         $wpdb->prepare( "UPDATE $tablename
                          SET id_venta = '".$id."' 
                         WHERE id = '".getIdUser() ."'  " ));   
         //  return array("id" => $result, "intent" => $intent, "upcoming" => getIdUser());  
          return array("id" => $result);  
      
   } catch (\Throwable $error) {

      return array("error" => $error);  
   }

  
}

function getIdUser(){ 
   
   $getNonce = explode('-',  $_COOKIE['chocol_cookie'])[0];

   $userfrase = explode('_', get_option($getNonce.'-chocol_price'))[1]; 

   $userTelf = explode('_', get_option($getNonce.'-chocol_price'))[2]; 

   $output;
   global $wpdb;  
   $tablename = $wpdb->prefix.'chocoletras_plugin';
 $result = $wpdb->get_results("SELECT id FROM $tablename 
           WHERE frase = '".$userfrase."' AND telefono = '".$userTelf."' ", OBJECT);   
foreach ($result as $key => $value) { 
      $output = $value->id; 
}
   return  $output;  
   // return array("frase" => $userfrase, "ID" => $getNonce);  
}