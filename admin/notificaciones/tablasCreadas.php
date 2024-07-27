
<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
function activationMessage(){

 

$path = $_SERVER['DOCUMENT_ROOT']; 
include_once $path . '/wordpress/wp-config.php';
include_once $path . '/wordpress/wp-load.php';
 
 global $wpdb; 
 

 $table_name = $wpdb->prefix."reportes_errores";
$table_plugin = $wpdb->prefix."chocoletras_plugin";

 if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name &&
 $wpdb->get_var("SHOW TABLES LIKE '$table_plugin'") == $table_plugin ) {
     add_action( 'admin_notices', 'my_error_notice' );
 }  
 function my_error_notice() {
    $table_plugin = $wpdb->prefix."chocoletras_plugin";
   $table_reportes = $wpdb->prefix."reportes_errores";
     ?>
     <div id="message" class="updated notice is-dismissible">
         <p><?php echo "Las tablas ". $table_plugin .' , '.$table_reportes . " Han sido creadas con exito!" ; ?></p>
     </div>
     <?php 
   }

}