<?php
/**
 * 
 * Plugin Name: Crea Tu Brownie
 * plugin URI: https://syntechtia.com/
 * version:1.0.0
 * Text Domail: lic. Christian 
 * Description: plugin for get control of chocoletra. For activate add this shorcode [brownie]
 * Author: Syntechtia
 * Author URI: https://syntechtia.com/
*/
 
// first need do somethings beffore call all the functions like create databases and tables like this time
 

require_once plugin_dir_path(__FILE__) . './admin/activationPlugin/activatePlugin.php'; 
  // ACTIIVATION PLUGIN FUNCTION -CREATE TABLES -
  register_activation_hook( __FILE__, 'createAllTables' );
  
  register_deactivation_hook( __FILE__, 'deactivationSetNull' );
  
  register_uninstall_hook( __FILE__, 'removeAllTables' );

 

// Include mfp-functions.php, use require_once to stop the script if mfp-functions.php is not found
require_once plugin_dir_path(__FILE__) . 'utils/clt_functions.php';

// include_once plugin_dir_path(__FILE__) . 'admin/notificaciones/tablasCreadas.php';
 

 
 