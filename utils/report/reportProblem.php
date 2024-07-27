<?php
/**
 * 
 * @package Crea Tu Frase
 * @subpackage M. Sufyan Shaikh
 * 
*/
function reportProblemStructure()
{
?> <div class='reportAproblem reportClosed'>
    <div class='reportAproblem_wrapper reportClosedApplyOpacity'>
    <span><?php echo _e( 'Ayúdanos a mejorar si tienes algun problema a la hora de crear tu frase, o sugiriendo alguna idea.' ); ?>
      </span>  
    <form id="reportFormId" action='reportForm'>
    <div class='reportAproblem_wrapper-mailName'>
         <input type='text' name='nombreReport' placeholder='nombre' required/>
         <input type="email" name="emailReport" placeholder='email' required/>
       </div>
         <textarea name='reporte' placeholder="Escriba su reporte aquí" required></textarea>
        <div class='reportAproblem_wrapper_quiz'>  
            <div class='reportAproblem_wrapper_quiz_solve'>
               <div class='reportAproblem_wrapper_quiz_solve_js'>
               <img id="ramdomNumberOne" src="" />
               <span>+</span>
               <img id="ramdomNumberTwo" src="" />
               <span>=</span>
               </div>
               <input id="ramdomNumberResults" type='text' pattern="[0-9]{1,2}" >
             </div>
             <div class='reportAproblem_wrapper_quiz_send'>
               <button type='submit'> <img src="<?php echo plugins_url( '../../img/sendIcon.png', __FILE__ ); ?>" /></button>
             </div>
         </div>
         <div class='reportAproblem_wrapper_response'>
           <span class='reportAproblem_wrapper_response_span'>
            
           </span>
         </div>
        </form>
  </div>
  <span class='reportAproblem_wrapper_triguer'>📢</span>
   
       </div>
       </div>
  <?php
}


     ?>