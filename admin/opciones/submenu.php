<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
 */
function submenuOutput()
{

  ?>
  <h2>Coloque los parametros condicionales.</h2>
  <!-- <form id="pcondicionales"> -->

  <table class="ChocoletrasBackendOpciones" cellspacing="0" cellpadding="0">


    <thead>
      <tr>
        <th>Valor</th>
        <th>Description</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo ('Coloque el precio por cada letra'); ?></td>
        <td>
          <div> <input type="number" maxlength="10" name="conditionalSubmit_precLetras" value="<?php
          echo get_option('precLetra') ? get_option('precLetra') : ''; ?>"> </div>
        </td>
      </tr>
      <tr>
        <td><?php echo ('Coloque el precio por cada ♥ Y ✯'); ?></td>
        <td>
          <div> <input type="number" maxlength="10" name="conditionalSubmit_precCorazon" value="<?php
          echo get_option('precCoraz') ? get_option('precCoraz') : ''; ?>"> </div>
        </td>
      </tr>
      <tr>
        <td><?php echo ('Coloque el precio por envio'); ?></td>
        <td>
          <div> <input type="number" maxlength="10" name="conditionalSubmit_precEnvio" value="<?php
          echo get_option('precEnvio') ? get_option('precEnvio') : ''; ?>"> </div>
        </td>
      </tr>
      <tr>
        <td><?php echo ('Coloque el maximo de caracteres'); ?></td>
        <td>
          <div> <input type="number" maxlength="10" name="conditionalSubmit_maximoC" value="<?php
          echo get_option('maxCaracteres') ? get_option('maxCaracteres') : ''; ?>"> </div>
        </td>
      </tr>
      <tr>
        <td><?php echo ('Coloque gasto minimo'); ?></td>
        <td>
          <div> <input type="number" maxlength="10" name="conditionalSubmit_Gminimo" value="<?php
          echo get_option('gastoMinimo') ? get_option('gastoMinimo') : ''; ?>"> </div>
        </td>
      </tr>
      <div style="display:none;">
        <tr>
          <td><?php echo ('Coloque la pagina del plugin'); ?></td>
          <td>
            <div> <input type="text" maxlength="50" name="conditionalSubmit_Page" value="<?php
            echo get_option('pluginPage') ? get_option('pluginPage') : ''; ?>"> </div>
          </td>
        </tr>
        <tr>
          <td><?php echo esc_html('página terminos y condiciones'); ?> </td>
          <td>
            <div> <input type="text" maxlength="50" name="termCondlSubmit_Page" value="<?php
            echo get_option('termCond') ? get_option('termCond') : ''; ?>"> </div>
          </td>
        </tr>
      </div>
      <tr>
        <td><?php echo esc_html('Coloque el precio de: envio express'); ?> </td>
        <td>
          <div> <input type="number" maxlength="150" name="expressShipinglSubmit_Page" value="<?php
          echo get_option('expressShiping') ? intval(get_option('expressShiping')) : ''; ?>"> </div>
        </td>
      </tr>
      <tr>
        <td><?php echo esc_html('Sábado Gastos de envío'); ?> </td>
        <td>
          <div> <input type="number" maxlength="150" name="saturdayShipinglSubmit_Page" value="<?php
          echo get_option('saturdayShiping') ? intval(get_option('saturdayShiping')) : ''; ?>"> </div>
        </td>
      </tr>
      <tr class="ChocoletrasBackendOpciones-trSubmit">
        <td colspan="2">
          <div class="ChocoletrasBackendOpciones-submit"> <input type="submit" value="Enviar" id="conditionalSubmit">
          </div>
        </td>
      </tr>
    </tbody>
  </table>
  <!-- </form> -->
  <?php
}