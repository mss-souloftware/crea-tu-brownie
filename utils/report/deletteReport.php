<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
 

function deletteAnythings(){
    $deletteResult = deletteResult(); 
  echo json_encode($deletteResult); 
  exit;
}


function deletteResult(){
    global $wpdb; 
    $tablename = $wpdb->prefix.'reportes_errores';
    
    $result;
    if(isset($_POST['id'])){
        $id = $_POST['id']; 
        //$_POST['email'] 
    } else {
        return ['result' => 'No id enviado'];
    }
     
        $result = $wpdb->query( $wpdb->prepare( "DELETE FROM $tablename
             WHERE id = $id " ));  
         return ['result' => $result];                                        

}