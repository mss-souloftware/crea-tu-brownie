<?php

/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

function createAllTables()
{
  $errorClTables = "errorRegisterTables";
  global $wpdb;

  if (get_option($errorClTables) != null) {
    return;
  } else {
    try {
      $table_report = $wpdb->prefix . "reportes_errores";
      $table_plugin = $wpdb->prefix . "chocoletras_plugin";
      $charset_collate = $wpdb->get_charset_collate();

      $createTableReport = "CREATE TABLE $table_report (
                id int(11) NOT NULL AUTO_INCREMENT,
                nombre varchar(150) NOT NULL, 
                email varchar(150) NOT NULL,
                reporte varchar(500) NOT NULL,
                fecha timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY  (id)
              ) $charset_collate;";

      $createTablePlugin = "CREATE TABLE $table_plugin  (
              id int(11) NOT NULL AUTO_INCREMENT,
              nombre varchar(150) NOT NULL,
              frase varchar(1000) NOT NULL,
              chocotype varchar(150) NOT NULL,
              email varchar(150) NOT NULL,
              telefono varchar(150) NOT NULL,
              cp varchar(50) NOT NULL,
              ciudad varchar(150) NOT NULL,
              province varchar(150) NOT NULL,
              message varchar(550) NOT NULL,
              direccion varchar(150) NOT NULL,
              enProceso tinyint(1) NOT NULL DEFAULT 0,
              enviado tinyint(1) NOT NULL DEFAULT 0,
              pagoRealizado tinyint(1) NOT NULL DEFAULT 0,
              cart tinyint(1) NOT NULL DEFAULT 0,
              fechaEntrega date NOT NULL,
              id_venta varchar(150) NOT NULL DEFAULT 'null',
              nonce varchar(50) NOT NULL,
              fecha timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              precio float NOT NULL,
              express varchar(3) NOT NULL,
              uoi varchar(150) NOT NULL,
              coupon varchar(50) NOT NULL,
              screens LONGTEXT,
              featured LONGTEXT,
              payment VARCHAR(50),
              PRIMARY KEY  (id)
            ) $charset_collate;";

      require_once ABSPATH . "wp-admin/includes/upgrade.php";
      dbDelta($createTableReport);
      dbDelta($createTablePlugin);

      update_option($errorClTables, true);
    } catch (\Throwable $error) {
      error_log($error->getMessage());
    }
  }
}

function deactivationSetNull()
{
  update_option('errorRegisterTables', null);
}

function removeAllTables()
{
  $errorClTables = "errorRegisterTables";
  $optionsToDelete = [
    "precLetra",
    "precCoraz",
    "precEnvio",
    "expressShiping",
    "maxCaracteres",
    "gastoMinimo",
    "pluginPage",
    "termCond",
    "ouputCltHost",
    "ouputCltPort",
    "ouputCltSecure",
    "ouputCltemail",
    "ouputCltPass",
    "publishableKey",
    "secretKey",
    "errorRegisterTables"
  ];

  global $wpdb;
  $table_report = $wpdb->prefix . "reportes_errores";
  $table_plugin = $wpdb->prefix . "chocoletras_plugin";

  try {
    $removal_report = "DROP TABLE IF EXISTS {$table_report}";
    $removal_pluginDatabase = "DROP TABLE IF EXISTS {$table_plugin}";
    $remResult = $wpdb->query($removal_report);
    $remResult2 = $wpdb->query($removal_pluginDatabase);
    update_option($errorClTables, null);

    foreach ($optionsToDelete as $options_value) {
      if (get_option($options_value)) {
        delete_option($options_value);
      }
    }

    return $remResult . "::" . $remResult2;
  } catch (\Throwable $error) {
    error_log($error->getMessage());
  }

}