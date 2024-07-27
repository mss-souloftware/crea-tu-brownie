<?php 
/**
 * 
 * author: M. Sufyan Shaikh
 * description: process form and send info to cookie
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * @note: this send info to js->action->utils->ajaxSubmitForm
 * 
*/  
  
function saveReportData() { 
    $arr = array('Response' => responseResult()? 1 : 0 );

    echo json_encode($arr); 
    exit;
  }

  function responseResult()
  {
    $getData = array("nombreReport", "emailReport", "reporte");
    $reportItems = array(); 
   foreach ($getData as $key ) { 
    if(isset($_POST[$key]) && $_POST[$key] != ''){
     $reportItems[$key] = $_POST[$key];
     } else{
       $reportItems = 0;
     }
 }  
 return $reportItems === 0 ? 'Todos los datos son necesarios!': saveReportInDatabase($reportItems) ; 
  }

  function saveReportInDatabase($items)
  {
    $sanitizeData = array();
       
    foreach ($items as $info => $val) {
         switch ($info) {
         case 'nombreReport':
         $sanitizeData[$info] = sanitize_file_name($items[$info]);
         break;
         case 'reporte':
         $sanitizeData[$info] = sanitize_file_name(  $items[$info] );
         break;
         case 'emailReport':
         $sanitizeData[$info] = sanitize_email(  $items[$info] );
         break;
      
       } 
  }

  return saveOnDatabase($sanitizeData);
}

function saveOnDatabase($sanitizeData){
    global $wpdb; 
    try {
      $database = $wpdb->prefix.'reportes_errores';
     $result = $wpdb->query( $wpdb->prepare( 
       "INSERT INTO $database( `nombre`,`email`,`reporte`) 
      VALUES ( '".$sanitizeData['nombreReport'] ."',
               '".$sanitizeData['emailReport'] ."',
               '".$sanitizeData['reporte'] ."' 
                )" )); 
 
    } catch (\Throwable $erro) {
     $result = $erro;
    }
    return $result;
}