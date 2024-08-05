<?php

/**
 * 
 * @package Chocoletras
 * @subpackage M. Sufyan Shaikh
 * 
 */



require_once plugin_dir_path(__FILE__) . 'shortcode.php';
require_once plugin_dir_path(__FILE__) . 'checkoutStripe.php';
require_once plugin_dir_path(__FILE__) . 'clt_saveInfo.php';
require_once plugin_dir_path(__FILE__) . '../admin/outputBackend.php';
require_once plugin_dir_path(__FILE__) . '../admin/statuschange/setStatus.php';
require_once plugin_dir_path(__FILE__) . '../admin/opciones/submenu.php';
require_once plugin_dir_path(__FILE__) . '../admin/calander/calander.php';
require_once plugin_dir_path(__FILE__) . '../admin/coupons/coupons.php';
require_once plugin_dir_path(__FILE__) . '../admin/payments/payments.php';
require_once plugin_dir_path(__FILE__) . '../admin/abandoned/abandoned.php';
require_once plugin_dir_path(__FILE__) . '../admin/opciones/itemsEmail.php';
require_once plugin_dir_path(__FILE__) . '../admin/opciones/reportsPage.php';
require_once plugin_dir_path(__FILE__) . '../admin/opciones/stripe.php';
require_once plugin_dir_path(__FILE__) . '../admin/opciones/saveOptions.php';
require_once plugin_dir_path(__FILE__) . '../admin/emailOutputOption/emailOptions.php';
require_once plugin_dir_path(__FILE__) . './cancel/cancellProcess.php';
require_once plugin_dir_path(__FILE__) . './savestripeoption/stripeoption.php';
require_once plugin_dir_path(__FILE__) . './savestripeoption/stripeoption.php';
require_once plugin_dir_path(__FILE__) . './savestripeoption/stripeSession.php';
require_once plugin_dir_path(__FILE__) . './report/saveReportToDatabase.php';
require_once plugin_dir_path(__FILE__) . './report/deletteReport.php';

require_once (plugin_dir_path(__FILE__) . '/abandoned/abandoned-cart-functions.php');

add_filter('cron_schedules', 'add_custom_cron_intervals');

function add_custom_cron_intervals($schedules) {
    $interval = get_option('abandoned_cart_minutes', 1) * 60; // Minutes to seconds

    // Add a custom interval
    $schedules['custom_interval'] = array(
        'interval' => $interval,
        'display' => __('Custom Interval')
    );

    return $schedules;
}

// Schedule event on init hook
function schedule_abandoned_cart_check() {
  $abandoned_cart_enable = get_option('abandoned_cart_enable', 0);
  
  // If abandoned cart is disabled, unschedule the event
  if (!$abandoned_cart_enable) {
      if ($timestamp = wp_next_scheduled('check_abandoned_cart')) {
          wp_unschedule_event($timestamp, 'check_abandoned_cart');
      }
      return;
  }

  // Get the interval from settings, default to 60 seconds if not set
  $interval = get_option('abandoned_cart_minutes', 1) * 60;

  if (!wp_next_scheduled('check_abandoned_cart')) {
      wp_schedule_event(time(), 'custom_interval', 'check_abandoned_cart');
  }
}
add_action('init', 'schedule_abandoned_cart_check');

function clt_admin_style()
{
  wp_enqueue_style('faltpickrForPluginBackend', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), false);
  wp_enqueue_script('flatpcikrScriptForBackend', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), '1.0.0', true);
  wp_enqueue_style('backendStyleForClt', plugins_url('../src/css/clt_style.css', __FILE__), array(), false);
  wp_enqueue_script('backendScript', plugins_url('../src/clt_script.js', __FILE__), array(), '1.0.0', true);
  wp_enqueue_script('backendCustomScript', plugins_url('../src/b_script.js', __FILE__), array('jquery', 'flatpcikrScriptForBackend'), '1.0.0', true);

  wp_localize_script(
    'backendCustomScript',
    'calendarSettings',
    array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('calendar_settings_nonce')
    )
  );

  wp_localize_script(
    'backendScript',
    'ajax_variables',
    array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('my-ajax-nonce'),
      'action' => 'proceso'
    )
  );
}
add_action('admin_enqueue_scripts', 'clt_admin_style');


add_action('wp_ajax_get_calendar_settings', 'get_calendar_settings');
add_action('wp_ajax_nopriv_get_calendar_settings', 'get_calendar_settings');

function get_calendar_settings()
{
  $disable_dates = get_option('disable_dates', []);
  $disable_days = get_option('disable_days', []);
  $disable_days_range = get_option('disable_days_range', '');
  $disable_months_days = get_option('disable_months_days', ['months' => [], 'days' => []]);

  // Convert array to comma-separated string
  $disable_dates_string = implode(',', $disable_dates);

  $response = array(
    'disable_dates' => $disable_dates_string,
    'disable_days' => $disable_days,
    'disable_days_range' => $disable_days_range,
    'disable_months_days' => $disable_months_days,
  );

  wp_send_json($response);
}



function chocoletrasInsertScripts()
{
  // wp_enqueue_script('chocoletrasScript', plugins_url('../src/main.js', __FILE__), array(), '1.0.0', true);
  wp_enqueue_style('pluginStylesClt', plugins_url('../src/css/clt_style.css', __FILE__), array(), false);

  if (is_page('crea-tu-frase')) {
    wp_enqueue_style('bootstrapForPlugin', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array(), false);
  }

  wp_enqueue_style('faltpickrForPlugin', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), false);

  wp_enqueue_style('styleForFrontend', plugins_url('../src/css/frontend-style.css', __FILE__), array(), false);

  wp_enqueue_script('flatpcikrScriptForFrontend', 'https://cdn.jsdelivr.net/npm/flatpickr', array(), '1.0.0', true);
  wp_enqueue_script('flatpcikrScriptForLanguage', 'https://npmcdn.com/flatpickr@4.6.13/dist/l10n/es.js', array(), '1.0.0', true);
  wp_enqueue_script('screencaptureOrder', 'https://cdn.jsdelivr.net/npm/html2canvas@1.3.2/dist/html2canvas.min.js', array(), '1.0.0', true);
  wp_enqueue_script('scriptForFrontend', plugins_url('../src/script.js', __FILE__), array(), '1.0.0', true);

  wp_localize_script(
    'scriptForFrontend',
    'ajax_variables',
    array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce' => wp_create_nonce('my-ajax-nonce'),
      'action' => 'event-list',
      'plgPage' => get_option('pluginPage'),
      'stripe' => isset($_COOKIE['stripeLoaded']) ? true : false,
      'publicKy' => get_option("publishablekey"),
      'precLetra' => get_option('precLetra'),
      'precCoraz' => get_option('precCoraz'),
      'precEnvio' => get_option('precEnvio'),
      'maxCaracteres' => get_option('maxCaracteres'),
      'gastoMinimo' => get_option('gastoMinimo'),
      'express' => get_option('expressShiping'),
      'pluginUrl' => plugin_dir_url(__DIR__),
      'pluginPageUrl' => get_option('ctf_settings')['plugin_page'],
    )
  );
}
add_action('wp_enqueue_scripts', 'chocoletrasInsertScripts');

// Register AJAX actions
function register_coupon_ajax()
{
  add_action('wp_ajax_validate_coupon', 'validate_coupon');
  add_action('wp_ajax_nopriv_validate_coupon', 'validate_coupon');
}
add_action('init', 'register_coupon_ajax');

// Validate Coupon Function
function validate_coupon()
{
  if (!isset($_POST['coupon']) || empty($_POST['coupon'])) {
    wp_send_json_error(['message' => 'Coupon code is required']);
  }

  $coupon_code = sanitize_text_field($_POST['coupon']);
  $coupons = get_option('coupons', []);

  foreach ($coupons as &$coupon) {
    if ($coupon['name'] === $coupon_code) {
      // Check if the coupon has expired
      if (!empty($coupon['expiration']) && strtotime($coupon['expiration']) < time()) {
        wp_send_json_error(['message' => 'This coupon has expired']);
      }

      // Check usage limit
      if (isset($coupon['usage_limit']) && $coupon['usage_count'] >= $coupon['usage_limit']) {
        wp_send_json_error(['message' => 'This coupon has reached its usage limit']);
      }

      // If valid, increment usage count
      $coupon['usage_count'] += 1;
      update_option('coupons', $coupons); // Update the option with the new usage count

      $remaining_usage = $coupon['usage_limit'] - $coupon['usage_count'];

      wp_send_json_success([
        'message' => 'Coupon is valid',
        'discount' => $coupon['value'],
        'type' => $coupon['type'],
        'remaining_usage' => $remaining_usage
      ]);
    }
  }

  wp_send_json_error(['message' => 'Invalid coupon code']);
}


function delete_rows_callback()
{
  if (isset($_POST['ids']) && is_array($_POST['ids'])) {
    global $wpdb;
    $ids = $_POST['ids'];
    $placeholders = implode(',', array_fill(0, count($ids), '%d'));
    $table_name = $wpdb->prefix . 'chocoletras_plugin';

    $query = "DELETE FROM $table_name WHERE id IN ($placeholders)";
    $result = $wpdb->query($wpdb->prepare($query, $ids));

    if ($result !== false) {
      wp_send_json_success();
    } else {
      wp_send_json_error();
    }
  } else {
    wp_send_json_error();
  }
}

add_action('wp_ajax_delete_rows', 'delete_rows_callback');



// vincule data script to php file //
add_action('wp_ajax_nopriv_test_action', 'responseForm');
add_action('wp_ajax_test_action', 'responseForm');

// change status process
add_action('wp_ajax_nopriv_proces', 'resultProcess');
add_action('wp_ajax_proces', 'resultProcess');

// delette report
add_action('wp_ajax_nopriv_dellReport', 'deletteAnythings');
add_action('wp_ajax_dellReport', 'deletteAnythings');

// change conditionales
add_action('wp_ajax_nopriv_conditionales', 'saveConditionales');
add_action('wp_ajax_conditionales', 'saveConditionales');

// cancell process
add_action('wp_ajax_nopriv_cancelProcess', 'resultcancellProcess');
add_action('wp_ajax_cancelProcess', 'resultcancellProcess');

// save stripe keys  
add_action('wp_ajax_nopriv_saveStripekeys', 'ouputStripeOptions');
add_action('wp_ajax_saveStripekeys', 'ouputStripeOptions');

//new stripev3
// save stripe keys  
add_action('wp_ajax_nopriv_stripeCreateSession', 'responseStripe');
add_action('wp_ajax_stripeCreateSession', 'responseStripe');

// save stripe session saveStripeSectionId
add_action('wp_ajax_nopriv_saveStripeSectionId', 'tryTosaveStripeOption');
add_action('wp_ajax_saveStripeSectionId', 'tryTosaveStripeOption');


// save email admin option
add_action('wp_ajax_nopriv_saveOptionsEmail', 'outputSavedOptionsEmail');
add_action('wp_ajax_saveOptionsEmail', 'outputSavedOptionsEmail');

// save reportForm
add_action('wp_ajax_nopriv_reportForm', 'saveReportData');
add_action('wp_ajax_reportForm', 'saveReportData');

//=============================================================//
define('PROCESS_FRASE', plugins_url('clt_process_form.php', __FILE__));

add_shortcode('brownie', 'chocoletras_shortCode');

// chocoletras admin menu
add_action('admin_menu', 'clt_adminMenu');
function clt_adminMenu()
{
  add_menu_page(
    'Todas las órdenes',
    'Pedidos',
    'install_plugins',
    'clt_amin',
    'chocoletraMenu_ftn',
    plugins_url('../img/logo.svg', __FILE__),
    27
  );
}

add_action('admin_menu', 'addSubmenuChocoletras');
function addSubmenuChocoletras()
{
  add_submenu_page(
    'clt_amin',
    'Todos los ajustes',
    'Ajustes',
    'install_plugins',
    'set_options',
    'submenuOutput',
    2
  );
  add_submenu_page(
    'clt_amin', // Parent slug
    'Calendario', // Page title
    'Calendario', // Menu title
    'manage_options',
    'calendar_settings',
    'calanderOutput',
    3
  );
  add_submenu_page(
    'clt_amin', // Parent slug
    'Cupones', // Page title
    'Cupones', // Menu title
    'manage_options',
    'coupons_settings',
    'coupon_settings_page',
    4
  );
}


// add_action('admin_menu', 'addSubmenuStrypeKeys');
// function addSubmenuStrypeKeys()
// {
//   add_submenu_page(
//     'clt_amin',
//     'Stripe setUp',
//     'Stripe Keys',
//     'install_plugins',
//     'set_stripe_keys',
//     'stripeOptions',
//     2
//   );
// }

add_action('admin_menu', 'addSubmenupaymentOutput');
function addSubmenupaymentOutput()
{
  add_submenu_page(
    'clt_amin',
    'Correos electrónicos',
    'Correos electrónicos',
    'install_plugins',
    'set_email_items',
    'emailItemsOutput',
    5
  );
}

add_action('admin_menu', 'addSubmenuEmailOptions');
function addSubmenuEmailOptions()
{
  add_submenu_page(
    'clt_amin',
    'Ajustes Payment',
    'Ajustes Payment',
    'install_plugins',
    'payment_settings',
    'paymentOutput',
    6
  );
}


add_action('admin_menu', 'addSubmenuAbandoned');
function addSubmenuAbandoned()
{
  add_submenu_page(
    'clt_amin',
    'Carro Abandonado',
    'Carro Abandonado',
    'install_plugins',
    'abandoned_cart',
    'abandonedOutput',
    6
  );
}

// add_action('admin_menu', 'listOfReportProblems');
// function listOfReportProblems()
// {
//   add_submenu_page(
//     'clt_amin',
//     'Error Reports',
//     'Reportes',
//     'install_plugins',
//     'set_report_errors',
//     'reportsPage',
//     2
//   );
// }

// ACTIIVATION PLUGIN FUNCTION

//   register_activation_hook( __FILE__, 'createAllTables' );

//  function createAllTablesp(){
//   exit('=================');
// }

// register_deactivation_hook( __FILE__, 'tata');
// function tata(){

// }
