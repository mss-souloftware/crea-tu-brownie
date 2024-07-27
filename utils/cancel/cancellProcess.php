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
 require_once plugin_dir_path(__FILE__) .'../savestripeoption/stripeSession.php'; 
    function resultcancellProcess(){
        $saveStatus = array("result"=>deletteProcess() , "id" => getIdUser());
      echo json_encode($saveStatus); 
      exit;
  }

    function deletteOption(){ 
                
 

        if( isset( $_COOKIE['chocol_cookie'] )){
          $havepost =   delete_option($_COOKIE['chocol_cookie'] ) ;  
        }  
 

        return  $havepost;
    }
    

        function deletteProcess()
        {
        // return deletteOption() === 1 ? true : false;
        return deletteOption();
        }
        
    
       