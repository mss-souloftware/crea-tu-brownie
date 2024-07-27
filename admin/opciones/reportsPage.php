<?php 
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/

function reportsPage()
{

    require_once plugin_dir_path(__FILE__) . '../callAlldata/get_userPaiment.php';


    global $wpdb; 
 
            $database = $wpdb->prefix.'reportes_errores';
 
            $paginationValue = isset($_GET['select']) ? $_GET['select'] : null; 
            global $wp;
            $url_actual = home_url( add_query_arg( array() ) );
            $url = add_query_arg( $wp->query_vars, home_url() );
            $rebuildUrl = explode("select=", $url_actual);

            $payment = new allPayment($paginationValue, $url, $database); 
            $allreports = $payment->tryGetAllPayment();
            $elementsPagination = $payment->paginationElements();

             
            
    ?>
     <h2>Lista con los errores reportados por los usuarios</h2>
     <span>🦺 Se recomienda eliminar los errores ya resueltos e investigados de la lista! </span><br>
     
     <!-- <form id="pcondicionales"> -->
       <table class="reportsTable" cellspacing="0" cellpadding="0">
       
       
  <thead class="reportsTable__thead">
      <tr class="reportsTable__thead-tr">
      <th class="reportsTable__thead-th"><?php echo esc_html( 'ID' ); ?></th> 
      <th class="reportsTable__thead-th"><?php echo esc_html( 'Reportado en:' ); ?></th> 
      <th class="reportsTable__thead-th"><?php echo esc_html( 'Nombre' ); ?></th> 
      <th class="reportsTable__thead-th"><?php echo esc_html( 'eMail' ); ?></th> 
      <th class="reportsTable__thead-th"><?php echo esc_html( 'Reporte' ); ?></th> 
    </tr>
  </thead> 
  <tbody class="reportsTable__tbody">
  <?php  
   
   foreach ($allreports as $value) {
     echo '<tr class="reportsTable__tbody-tr">';
      echo '<th class="reportsTable__tbody-th">'.$value->id.'</th>';
      echo '<th class="reportsTable__tbody-th">'.$value->fecha.'</th>';
      echo '<th class="reportsTable__tbody-th">'.$value->nombre.'</th>';
      echo '<th class="reportsTable__tbody-th">'.$value->email.'</th>';
      echo '<th class="reporteTH reportsTable__tbody-th">'.$value->reporte. '<buttom class="deletteReport dell_'.$value->id.'">x</buttom></th>';
     echo '</tr>';
   }
    ?>
  </tbody>
</table>
<section class="reportsTable__pagination">


<?php
          //  $paginationValue = isset($_GET['select']) ? $_GET['select'] : null; 
           $getPages = $elementsPagination / 10;
           for ($b=0; $b < $getPages; $b++) { 
            $sum = $b + 1;
            $isDisabled = '';
             if(!$paginationValue && $sum == 1 || $paginationValue == $sum){
                 $isDisabled = 'disabled'; 
               } 
             
              echo '<a href="'.$rebuildUrl[0].'&select='.$sum.'"><button '.$isDisabled.' >'. $sum .' </button></a>'; 
           }
           ?>
           </section>
    <?php
  }