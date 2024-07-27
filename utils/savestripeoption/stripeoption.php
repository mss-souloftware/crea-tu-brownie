<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/

function ouputStripeOptions(){
    // $statusStripe = saveStripeOptions(); 
    $statusStripe = array("results"=> saveStripeOptions()); 
    echo json_encode($statusStripe); 
    exit;
}

function saveStripeOptions(){
    $savedOptions = array();
 if(isset($_POST['publishablekey']) && isset($_POST['secretKey'])){
      if(savingOptionStripe("publishablekey", $_POST['publishablekey']) == true){
        $savedOptions['publishablekey'] = true;
      }
      if(savingOptionStripe("secretKey", $_POST['secretKey']) == true){
        $savedOptions['secretKey'] = true;
      }
  }
  return $savedOptions;
}

 
function savingOptionStripe($optionName, $optionValue){
    $confirm = '';
    if (  !get_option($optionName) ){
        $confirm = add_option( $optionName, $optionValue ); 
   } elseif( get_option($optionName) ){
     $confirm = update_option( $optionName, $optionValue );
   } else {
     $confirm = false;
   }
   return $confirm;
}

