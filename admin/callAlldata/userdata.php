<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/ 
 function userdata($data){
    $repareFrase = str_replace('?','♥', $data->frase);  
     $out = '<div class="AdministracionVentas-table-tbody_hidenInfo-sections">';
     $out .= '<span><b>Nombre: </b>'. str_replace('-',' ',$data->nombre) .'</span>';
     $out .= '<span><b>Email: </b>'. str_replace('-',' ',$data->email) .'</span>';
     $out .= '<span><b>Telefono: </b>'. str_replace('-',' ',$data->telefono) .'</span>'; 
     $out .= '<span><b>Fecha de Entrega: </b>'. str_replace('-',' ',$data->fechaEntrega) .'</span>';
     $out .= '<span><b>Direccion: </b>'. str_replace('-',' ',$data->direccion) .'</span>';
     $out .= '<span><b>Ciudad: </b>'. str_replace('-',' ',$data->ciudad) .'</span>';
     $out .= '<span><b>Provincia: </b>'. str_replace('-',' ',$data->province) .'</span>';
     $out .= '<span><b>Codigo Postal: </b>'. str_replace('-',' ',$data->cp) .'</span>';
     $out .= '<span><b>Frase: </b>'. str_replace('-',' ',$repareFrase) .'</span>';
     $out .= '<span><b>Id Venta: </b>'. str_replace('-',' ',$data->id_venta) .'</span>';
     $out .= '</div>';
     return $out;
 }
 function useractions($id, $email, $proceso, $enviado){
     $proceso_btn_disabled = $proceso != 0 ? "disabled": null;
     $enviado_btn_disabled = $enviado != 0 ? "disabled": null;
        $actions = '<div class="AdministracionVentas-table-tbody_hidenInfo-sections_2">';
        $actions .= '<span><b>Cambiar estado de proceso: </b><button '.$proceso_btn_disabled.' id="proceso_statusProces_'.$id.'_'.solveErrorInEmail($email).'" class="proceso">'. esc_html( 'Cambiar estado' ).'
        <img style="display:none" class="AdministracionVentas-table-tbody_hidenInfo-sections_2-reload" width="15px" 
        src="'.plugins_url( '../../img/reload.png', __FILE__ ).'" /></button></span>';
        $actions .= '<span><b>Cambiar estado de envio: </b><button '.$enviado_btn_disabled.' id="envio_statusProces_'.$id.'_'.solveErrorInEmail($email).'" class="envio">'. esc_html( 'Cambiar estado' ).'
        <img style="display:none" class="AdministracionVentas-table-tbody_hidenInfo-sections_2-reload" width="15px" 
        src="'.plugins_url( '../../img/reload.png', __FILE__ ).'" /></button></span>';
        $actions .= '<span><b>Eliminar este usuario: </b><button id="eliminar_statusProces_'.$id.'" class="eliminar">'. esc_html( 'Eliminar usuario' ).'
        <img style="display:none" class="AdministracionVentas-table-tbody_hidenInfo-sections_2-reload" width="15px" 
        src="'.plugins_url( '../../img/reload.png', __FILE__ ).'" /></button></span>';
        $actions .= '</div>'; 
    return $actions;
}

function getMessage($message){ 
     $out = '<div class="AdministracionVentas-table-tbody_hidenInfo-sections_1">';
     $out .= '<span><b>Mensaje:</b></span><br>'; 
     $out .= '<b>'. str_replace('-',' ',$message) .'</b>';
     $out .= '</div>';
     return $out;
 }


function solveErrorInEmail($email){
    return str_replace("_","*", $email );
}