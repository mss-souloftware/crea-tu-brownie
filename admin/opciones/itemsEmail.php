<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
function emailItemsOutput(){
    
    ?>
     <h2>Coloque los parametros para la salida de email.</h2>
     <span>Valla hacia su servidor de correos para obtener estos datos</span><br>
     
     <!-- <form id="pcondicionales"> -->
       <table class="opstionsEmailOutput ChocoletrasBackendOpciones"  cellspacing="0" cellpadding="0">
       
       
  <thead class="opstionsEmailOutput__thead">
    <tr class="opstionsEmailOutput__thead-tr">
      <th class="opstionsEmailOutput__thead-th">Description</th>
      <th class="opstionsEmailOutput__thead-th">Valor</th>
    </tr>
  </thead>

  <tbody class="opstionsEmailOutput__tbody">
    <tr class="opstionsEmailOutput__tbody-tr">
      <td class="opstionsEmailOutput__tbody-th">Host</td>
      <td class="opstionsEmailOutput__tbody-th"> <div> <input type="text" maxlength="30" name="ouputCltHost" value="<?php 
      echo get_option('ouputCltHost') ? get_option('ouputCltHost') : ''; ?>"> </div></td>
    </tr>
  <!--  -->
    <tr class="opstionsEmailOutput__tbody-tr">
      <td>Port</td>
      <td class="opstionsEmailOutput__tbody-td"> <div> <input type="text" maxlength="30" name="ouputCltPort" value="<?php 
      echo get_option('ouputCltPort') ? get_option('ouputCltPort') : ''; ?>"> </div></td>
    </tr>
      <!--  -->
    <tr class="opstionsEmailOutput__tbody-tr">
      <td>Secure</td>
      <td class="opstionsEmailOutput__tbody-td"> <div> <input type="text" maxlength="30" name="ouputCltSecure" value="<?php 
      echo get_option('ouputCltSecure') ? get_option('ouputCltSecure') : ''; ?>"> </div></td>
    </tr>
     <!--  -->
     <tr class="opstionsEmailOutput__tbody-tr">
       <td>Email</td>
      <td class="opstionsEmailOutput__tbody-td"> <div> <input type="text" maxlength="30" name="ouputCltemail" value="<?php 
      echo get_option('ouputCltemail') ? get_option('ouputCltemail') : ''; ?>"> </div></td>
    </tr>
    <!--  -->
    <tr class="opstionsEmailOutput__tbody-tr">
      <td class="opstionsEmailOutput__tbody-td">Pasword</td>
      <td class="opstionsEmailOutput__tbody-td"> <div> <input type="text" maxlength="30" name="ouputCltPass" value="<?php 
      echo get_option('ouputCltPass') ? get_option('ouputCltPass') : ''; ?>"> </div></td>
    </tr>
       <tr class="outputEmailBody">
        <td   colspan="2"> <div class="outputEmailBody-submit"> <input type="submit" value="Enviar" id="itemsEmaiBtn">  </div></td>
       </tr>
  </tbody>
</table>
     <!-- </form> -->
    <?php
  }