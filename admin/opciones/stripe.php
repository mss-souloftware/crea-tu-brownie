<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
function stripeOptions(){
    
    ?>
     <h2>Coloque los datos de strype</h2>
     <!-- <form id="pcondicionales"> -->
       <table class="stripedata" cellspacing="0" cellpadding="0">
       
       
  <thead>
    <tr>
      <th><span>Valor</span></th>
      <th><span>Description</span></th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <th><span>Valor</span></th>
      <th><span>Description</span></th>
    </tr>
  </tfoot>
  <tbody>
    <tr>
      <td> <div> <input type="text"  name="publishableKey" value="<?php 
      echo get_option('publishableKey') ? get_option('publishableKey') : ''; ?>"> </div></td>
      <td><b>Llave pública</b></td>
    </tr>
    <tr>
      <td> <div> <input type="text" name="secretKey" value="<?php 
      echo get_option('secretKey') ? get_option('secretKey') : ''; ?>">  </div></td>
      <td><b>Llave secreta</b></td>
    </tr>
     
        
        
       <tr class="">
        <td   colspan="2"> <div class=""> <input type="submit" value="Enviar" id="stripeSubmit">  </div></td>
       </tr>
  </tbody>
</table>
     <!-- </form> -->
    <?php
  }