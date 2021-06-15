<?php
/**
 * @package views
 * @author MA AMD
 */
sendHeader($screen->getTitulo()); ?>

<!-- nuevo disenio  -->
<div class="container py-3">
    <div class="row">
        <div class="mx-auto col-sm-8">
                     <div class="card">
                        <div class="card-header">
                        <h4 class="mb-0"><?php echo $screen->getTitulo(); ?></h4>
 						</div>
                    	<div class="card-body">
							<form name='abm-form' id='abm-form' method='POST' action='alta.php?cmd=save'
	                         <?php echo $screen->isEncTypeMultipart() ? 'enctype="multipart/form-data" ' : ''; ?> >
							<input type="hidden" name="screenid" class="form" value="<?php echo $screen->getId(); ?>">

							<?php if (! is_null($screen->getMsgInfo())) : ?>
									<div class="alert alert-info" role="alert">
											<?php echo $screen->getMsgInfo(); ?>
									</div>

							<?php endif; ?>

 							      <div class="row">                 
       
                                    <?php
                                    $ts=0;
                                    //print_r($screen->getInputs());
                                    	foreach ($screen->getInputs()->getAll() as $input) {
                                    	    if($input->getSize()!=0){
                                    	        if(($ts+$input->getSize())<=12) {

                                    	            $col = "col-lg-".$input->getSize();
                                    	              
                                    	            echo "<div class='form-group $col'>";
                                    	             
                                    	            echo $input->getHtml();
                                    	            
                                    	            echo "</div>";
                                    	        
                                    	        }else{
                                    	            
                                    	            echo "</div>";
                                    	            echo "<div class='row'>";    
                                    	            $ts=0;
                                    	             $col = "col-lg-".$input->getSize();
                                    	               echo "<div class='form-group $col'>";
                                    	             	            echo $input->getHtml();
                                    	            
                                    	               echo "</div>";
                                    	        }
                                    	    }else{
                                    	        
                                    	        echo $input->getHtml();
                                    	    }
                                    		
                                    		   
                                    	    $ts = $ts + $input->getSize();
                                    	    //echo $ts; 
                                    	}
                                    ?>
                                 </div>	

                                    <?php echo $screen->getHtml(); ?>

								<br><br>
                                 <div class="form-group row">
                                           <label class="col-lg-3 col-form-label form-control-label"></label>
                                      <div class="col-lg-9">
                                    
                                  			<input class="btn btn-primary btn-xs h btn-aceptar"
                                                                    <?php if ($screen->isConfirmable() ){?>
                                												type="button"	onClick="javascript: if (confirm('<?php echo $screen->getMsgConfirmar(); ?>')){ $('abm-form').submit();}"
                                                                    <?php 
                                                                          }else { ?>
                                												type="submit" 
                                                                    <?php 
                                                                          }
                                                                        ?>
                                 							value="<?php echo $screen->getBtnAceptar(); ?>"> 
                                  
                                	<input class="btn btn-secondary btn-xs h btn-cancelar" name='btn-cancel' id='btn-cancel'
                                
                                                <?php 
                                                if ($screen->isConfirmableCancel() ){
                                                ?>
                                                type="button" onClick="javascript: if (confirm('<?php echo $screen->getMsgConfirmarCancel(); ?>')){ $('btn-cancel').type='submit';
                                                																									$('abm-form').action='alta.php?cmd=close';}"
                                                <?php 
                                                }
                                                else {
                                                ?>
                                                type="submit" onclick="$('abm-form').action='alta.php?cmd=close';"
                                                <?php 
                                                }
                                                
                                
                                                if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
                                                value="<?php echo $screen->getBtnCancelar(); ?>" >
                                
                                
                                	<button class='btn btn-secondary btn-circle btn-xl h' type='button' value='' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Ayuda" /></button>
                                <?php endif; ?>		
                                
                                <?php if (!$screen->isValid()) :?>
                             
                                  <!-- <div class="alert-danger "> -->
                                	<?php if (strlen($screen->getError())>0) :?>
                                	<?php //echo strlen($screen->getError())."----"; ?>
                                	<!--</div> -->
                                	<?php echo '<script>'. MSG_STYLE.' toastr.error("'.$screen->getError().'") </script>';?>
                                   <?php endif; ?> 	
                                	
                                
                                <?php endif; ?>
                           </div>       
                                </form>
</div>
</div>
</div>
</div>
</div>
<script type="text/javascript">

<?php
  
 /*
  * Pongo en foco el input que tenga el flag correspondiente.  
  */
  foreach ($screen->getInputs()->getAll() as $i) 
  {
		if ($i->hasFocus()) {
			$id = $i->getSelectableId();
			echo "\$('$id').select();\n";
			echo "if (\$('$id').focus)\n \$('$id').focus();";	
		}
  }

?>

/*
 *  Esta función envía el formulario en background para que se puedan guardar
 *  los valores de los inputs en el objeto AltaScreen y luego hace un 
 *  post normal del form a otra dirección. 
 */	
	function saveValuesAndGoto(urlToGoAfter)
	{
		$('abm-form').action = 'alta.php?cmd=parse'; 
		var request = $('abm-form').request({
			method: 'post',
			onSuccess: function (transport) {
				$('abm-form').action = urlToGoAfter; 
				$('abm-form').submit();  
			}
		});	
	}

	function eventHook (method)
	{
		$('abm-form').action = 'alta.php?cmd=eventHook&method=' + method; 
		$('abm-form').submit(); 
		$('abm-form').innerHTML = "<img src='imagenes/cambiando.gif' alt='Cambiando...'>";
	}



	//var $j = jQuery.noConflict();

	 //$j(document).ready(function(){

	   // $j("#btn-cancel").click(function(){     
	    //	event.preventDefault();   
            
	    	//$j(".req").removeAttr("required")
            //alert("estoy aca ");
	    	//$j("#abm-form").submit(); // Submit the form

	    //});

	//});
</script>

<script type="text/javascript">
<?php 
	foreach ($screen->getInputs()->getAll() as $input) 
	{
		echo $input->getJavaScript(); 		
	}
	
	echo $screen->getJavaScript();
?>	
<?php if ($screen->getPreventDoubleSubmit()): ?>
/**
 * Script para evitar que el form se envíe 2 veces.
 */
var lastSubmitted = null;
Event.observe ('abm-form', 'submit', function(event) {
	var nowTS = new Date()+0;
	if (lastSubmitted
		<?php if ($auxT = $screen->getPreventDoubleSubmitTime()): ?>
		&& (lastSubmitted + <?php echo $auxT;?> > nowTS)
		<?php endif; ?>
		) 
	{
		var auxM = '<?php echo $screen->getPreventDoubleSubmitMsg();?>';
		if (auxM != '') alert(auxM);
		console.log('Stopping');
		event.stop();			
		return false;	
	}	
	lastSubmitted = nowTS;
	return false;
});
<?php endif; ?>
</script>


<?php sendFooter(); ?>


