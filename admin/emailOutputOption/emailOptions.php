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
 
function outputSavedOptionsEmail(){
    
    echo json_encode( getInfoPostEmailOptions() );
    exit;
}
 
 function getInfoPostEmailOptions(){
    $results = [];
      $items = ['ouputCltHost', 'ouputCltPort', 'ouputCltSecure', 'ouputCltemail', 'ouputCltPass']; 
      
      foreach($items as $i => $item ) { 
            if(isset($_POST[$item])){      
              if(get_option($item) != null){
                  update_option($item, $_POST[$item]) ? $results .= $item : null ; 
                }else{
                  add_option($item, $_POST[$item]) ? $results .= $item : null ; 
               }
            } else { 
              $results .= $item.'_notDataGet';  
            } 

       }
     
    return $results;
 }