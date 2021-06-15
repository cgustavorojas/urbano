<?php
/**
 * @package views
 */
 
sendHeader($screen->getTitulo()); 

$prefs = $screen->getUserPrefs();

?>

<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' id='abm-form' method='POST' action='mselect.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<div class="row">	
<?php 
  $ts=0;
                                    //print_r($screen->getInputs());
       foreach ($screen->getFiltros()->getAll() as $filtro) {
            if($filtro->getSize()!=0){
                  if(($ts+$filtro->getSize())<=12) {
                          $col = "col-lg-".$filtro->getSize();
                           echo "<div class='form-group $col'>";
                                     echo $filtro->getHtml();
                           echo "</div>";
                                    	        
                   }else{
                           echo "</div>";
                           echo "<div class='row'>";    
                           $ts=0;
                           $col = "col-lg-".$filtro->getSize();
                           echo "<div class='form-group $col'>";
                           echo $filtro->getHtml();
                           echo "</div>";
                                    	        }
              }else{
                  echo $filtro->getHtml();
              }
                                    		
                                    		   
              $ts = $ts + $filtro->getSize();
                                    	    //echo $ts; 
      }

      ?>

<br>


	<button class='btn btn-primary btn-xs h myButton' type="submit" value="Actualizar" onclick="$('abm-form').action='mselect.php?cmd=filtrar';"><img src="../icon/actualizar.png" alt="Actualizar" /></button> 
<?php if ($rs->getRowCount() > 0) : ?>
	<button class='btn btn-primary btn-xs h myButton' type="submit" value="Aceptar"><img src="../icon/desactivar.png" alt="volver" /></button> 
	<button class='btn btn-primary btn-xs h myButton' type="submit" value="Cancelar" onclick="$('abm-form').action='mselect.php?cmd=close';"><img src="../icon/cancelar.png" alt="volver" /></button>
<?php endif; ?>
<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
	<button class='btn  btn-primary btn-xs h myButton' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Ayuda" /></button>
		<?php endif; ?>

	<br><br>

<!--<fieldset style=" width:350px;">-->

<table>
<?php 
	foreach ($screen->getInputs()->getAll() as $input) {
		echo $input->getHtml(); 		
	}
?>
</table>	
<!--  </fieldset>-->

<!-- registros -->

<?php 
	$cols = $rs->getNumFields() - 1;  

	if ($rs->getRowCount() > 0)
	{
			echo "<table class='table table-responsive table-striped' cellspacing='2' cellpadding='0'>\n";
		
			echo "<tr>\n";
			
				//echo "<th>&nbsp;</th>\n";
				echo "<th><input id='multicheck' name='multicheck' type='checkbox'></th>";
				$cols++;
				
				for ($i = 1 ; $i < $cols ; $i++)
				{
					echo "<th>" . $rs->getFieldName($i) . "</th>\n";
				}
			
			echo "</tr>\n";
		
			$parImpar = -1; 
			while ($row = $rs->getRowWithIndexes())
			{
				$parImpar = $parImpar * (-1);
				$cssClass = $parImpar == 1 ? 'abm-grid-impar' : 'abm-grid-par';
				echo "<tr >\n";

				$rowID = $row[0];
				
				// este valor es el que se usa para que el checkbox esté seleccionado por defecto al inicio
				// se agrega en la query el valor 
				$seleccionados = $screen->getSelected();
				
				$checked= "";
				if (in_array($rowID,$seleccionados)){ $checked="checked='yes'";}
				
				echo "<td><input class='mselect-checkbox' name='mselect-inputs[]' type='checkbox' value='$rowID' $checked></td>";
				
				for ($i = 1 ; $i < $cols ; $i++)
				{
					$cssClass = '';
					$style = ''; 
					$value = $row[$i];	//TODO: mostrar según preferencias del usuario y tipo de datos

					echo "<td class='$cssClass' style='$style'>$value</td>\n";
				} 
				
				echo "</tr>\n";
				
				
				
			} // while $row
		
		
			echo "</table>\n"; 
			
	} else {		 //si había al menos 1 registros.
		 	
		echo '<h2>' . $screen->getMsgEmpty() . '</h2>';
	}
?>
<!--  fin registros -->

<br>

<?php if ($rs->getRowCount() > 0) : ?>
	<input class='btn btn-primary btn-xs h btn-aceptar' type="submit" value="Aceptar">&nbsp;
<?php endif; ?> 
<input class='btn btn-secondary btn-xs h btn-cancelar' type="submit" value="Cancelar" onclick="$('abm-form').action='mselect.php?cmd=close';">
<p></p>
<?php if (!$screen->isValid()) :?>
                             
                                  <!-- <div class="alert-danger ">-->
                                	<?php if (strlen($screen->getError())>0) :?>
                                	<?php //echo strlen($screen->getError())."----"; ?>
                                	<!--</div> -->
                                	<?php echo '<script>'. MSG_STYLE.' toastr.error("'.$screen->getError().'") </script>';?>
                                   <?php endif; ?> 	
                                	
                                
                                <?php endif; ?>
</form>

<script type="text/javascript">
Event.observe ('multicheck', 'click', function() {
	valor = $('multicheck').checked;

	$$('.mselect-checkbox').each(function(c) {c.checked = valor;});
});

</script>

<?php sendFooter(); ?>


