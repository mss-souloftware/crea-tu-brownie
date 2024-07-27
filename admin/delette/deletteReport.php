<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
function deletteReport(){
    $salida =  ['Result' => confirmDelete()];
    echo json_encode($salida ); 
    exit;
 }

 function confirmDelete()
 {  
     $result; 
     $_POST[$key];
     try{
       $tablename = $wpdb->prefix.'chocoletras_plugin';
        $result = $wpdb->query( 
            $wpdb->prepare( "DELETE FROM $tablename  
              WHERE id = '".$dataToSave['id']."' " )); 

     } catch (\Throwable $erro) {
    $result = array( "Status" => $error);
   }
     
       
       return $result; 
 }
   