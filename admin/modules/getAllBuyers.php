<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */

require_once plugin_dir_path(__FILE__) . '../callAlldata/get_userPaiment.php';
require_once plugin_dir_path(__FILE__) . '../callAlldata/userdata.php';

function chocoletraMenu_ftn()
{
  global $wpdb;

  $database = $wpdb->prefix . 'chocoletras_plugin';

  $paginationValue = isset($_GET['select']) ? $_GET['select'] : null;
  global $wp;
  $url_actual = home_url(add_query_arg(array()));
  $url = add_query_arg($wp->query_vars, home_url());
  $rebuildUrl = explode("select=", $url_actual);

  $payment = new allPayment($paginationValue, $url, $database);
  $allusers = $payment->tryGetAllPayment();
  $elementsPagination = $payment->paginationElements();
  ?>
  <div class="AdministracionVentas">
    <div class="deleteWrapper">
      <h2>Todos los detalles de los pedidos</h2>
      <button type="button" id="deleteAll">Eliminar elegir productos</button>
    </div>
    <section class="AdministracionVentas-table">
      <div class="AdministracionVentas-table-thead">
        <ul>
          <li class="AdministracionVentas-table-thead_id">
            <input id="selectAll" type="checkbox">
          </li>
          <li class="AdministracionVentas-table-thead-frase"><?php echo esc_html('Nombre'); ?></li>
          <li class="AdministracionVentas-table-thead_enviado"><?php echo esc_html('Teléfono'); ?></li>
          <li class="AdministracionVentas-table-thead_fecha"><?php echo esc_html('Fecha'); ?></li>
          <li class="AdministracionVentas-table-thead_precio"><?php echo esc_html('Precio'); ?></li>
          <li class="AdministracionVentas-table-thead_payment"><?php echo esc_html('Estado de pago'); ?></li>
          <li class="AdministracionVentas-table-thead_frasTotal"><?php echo esc_html('Pagar con'); ?></li>
          <li class="AdministracionVentas-table-thead_proceso"><?php echo esc_html('Estado'); ?></li>
          <li class="AdministracionVentas-table-thead_proceso"><?php echo esc_html('Fecha de Entrega'); ?></li>
          <li class="AdministracionVentas-table-thead_express"><?php echo esc_html('Envío'); ?></li>
        </ul>
      </div>
      <div class="AdministracionVentas-table-tbody">
        <?php
        foreach ($allusers as $value) {
          // echo '<pre>';
          // print_r($value);
          // echo '</pre>';
          $payment = $value->pagoRealizado == 0 ? 'No Pagado' : 'Pagado';
          $paymentClass = $value->pagoRealizado == 0 ? 'notPaid' : 'paid';
          // $proceso = $value->enProceso == 0 ? '...' : 'Procesando';
          // $enviado = $value->enviado == 0 ? '...' : 'Enviado';
          if ($value->enProceso == 0 && $value->enviado == 0) {
            $proceso = '...';
          } elseif ($value->enviado == 1) {
            $proceso = 'Enviado';
          } elseif ($value->enProceso == 1) {
            $proceso = 'Procesando';
          }
          $repareFrase = json_decode($value->frase, true);
          $isExpress = $value->express == "on" ? "Envío Express" : "Envío Normal";
          $expressStatus = $value->express == "on" ? "express" : "normal";

          if (is_array($repareFrase)) {
            $fraseCount = count($repareFrase);
          } else {
            $fraseCount = 0;
          }

          echo '<ul id="openPannel_' . $value->id . '">';
          echo '<li class="AdministracionVentas-table-tbody_id"><input id="' . $value->id . '" type="checkbox" value="' . $value->id . '"></li>';
          echo '<li class="AdministracionVentas-table-tbody-frase">' . $value->nombre . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_proceso">' . $value->telefono . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_fecha">' . $value->fecha . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_precio">' . $value->precio . '€</li>';
          echo '<li class="AdministracionVentas-table-tbody_payment"><span class="' . $paymentClass . '">' . $payment . '</span></li>';
          echo '<li class="AdministracionVentas-table-tbody_enviado">' . $value->payment . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_payment">' . $proceso . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_fecha">' . $value->fechaEntrega . '</li>';
          echo '<li class="AdministracionVentas-table-tbody_express"><span class="' . $expressStatus . '">' . $isExpress . '</span></li>';
          echo '</ul>';
          echo '<li id="infoPannel_' . $value->id . '" class="AdministracionVentas-table-tbody_hidenInfo"> ';
          // echo userdata($value);
          echo '<div class="infoPanelInnn">
                <h2>Detalles Pedido</h2> 
                <span><b>Nombre: </b>' . $value->nombre . '</span>
                <span><b>Email: </b>' . $value->email . '</span>
                <span><b>Telefono: </b>' . $value->telefono . '</span>
                <span><b>Frases: (' . $fraseCount . ')</b></span>';
          if (is_array($repareFrase)) {
            foreach ($repareFrase as $frase) {
              echo '<span>' . htmlspecialchars($frase) . '</span>';
            }
          } else {
            echo '<span>' . htmlspecialchars($value->frase) . '</span>';

          }
          if ($value->message != "") {
            echo getMessage($value->message);
          } else {
            echo '<span><b>No Mensaje</b></span>';
          }
          echo '</div>';

          echo '<div class="infoPanelInnn">
                  <h2>Detalles Envio</h2> 
                  <span><b>Solicitar ID:</b> ' . $value->uoi . '</span>
                  <span><b>Direccion: </b>' . $value->direccion . '</span>
                  <span><b>Ciudad: </b>' . $value->ciudad . '</span>
                  <span><b>Provincia: </b>' . $value->province . '</span>
                  <span><b>Codigo Postal: </b>' . $value->cp . '</span>
                  <span><b>Fecha de Entrega: </b>' . $value->fechaEntrega . '</span>
                  <span><b>Pagado: </b>' . $value->payment . '</span>';
                if ($value->cart == 0 && $value->pagoRealizado == 0) {
                  echo '<span><b>Estado Abandonado: </b>Pendiente 🕐</span>';
                } else if ($value->cart == 1 && $value->pagoRealizado == 0) {
                  echo '<span><b>Estado Abandonado: </b>Expedido ✔</span>';
                } else if ($value->cart == 1 && $value->pagoRealizado == 1) {
                  echo '<span><b>Estado Abandonado: </b>Expedido ✔</span>';
                } else {
                  echo '';
                }

          if ($value->coupon) {
            echo '<span><b>Cupón: </b>' . $value->coupon . '</span>';
          }
          echo '</div>';

          echo '<div class="infoPanelInnn">
          <h2>Estado del pedido</h2> ';
          echo useractions($value->id, $value->email, $value->enProceso, $value->enviado);
          echo '</div>';
          echo ' </li>';
        }
        ?>
      </div>
    </section>
    <section class="AdministracionVentas__pagination">
      <?php
      //  $paginationValue = isset($_GET['select']) ? $_GET['select'] : null; 
      $getPages = $elementsPagination / 10;
      for ($b = 0; $b < $getPages; $b++) {
        $sum = $b + 1;
        $isDisabled = '';
        if (!$paginationValue && $sum == 1 || $paginationValue == $sum) {
          $isDisabled = 'disabled';
        }


        echo '<a href="' . $rebuildUrl[0] . '&select=' . $sum . '"><button ' . $isDisabled . ' >' . $sum . ' </button></a>';
      }
      ?>
    </section>

  </div>

  <?php
}
