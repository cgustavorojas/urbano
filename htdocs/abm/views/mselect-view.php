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

	<div id='abm-query-filtros'>
	
		<table>
		<tr>
		<?php for ($i = 1 ; $i <= $screen->getFiltros()->getPosiciones() ; $i++) : ?>
			<td class='abm-query-fposicion'>
				<table>
					<?php echo $screen->getFiltros()->getHtml($i); ?>
				</table>
			</td>
		<?php endfor; ?>
		</tr>
		</table>
	</div>


<br>


	<input class="abm-boton abm-btn-refrescar" type="submit" value="Ejecutar" onclick="$('abm-form').action='mselect.php?cmd=filtrar';">&nbsp; 
<?php if ($rs->getRowCount() > 0) : ?>
	<input class="abm-boton abm-btn-ok" type="submit" value="Aceptar">&nbsp; 
	<input class="abm-boton abm-btn-cancel" type="submit" value="Cancelar" onclick="$('abm-form').action='mselect.php?cmd=close';">
<?php endif; ?>
<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_ABM_HELP')):?>
	<input class='abm-boton abm-btn-help' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>">
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
			echo "<table class='abm-grid' cellspacing='2' cellpadding='0'>\n";
		
			echo "<tr class='abm-grid-header'>\n";
			
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
				echo "<tr class='$cssClass'>\n";

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
	<input class="abm-boton abm-btn-ok" type="submit" value="Aceptar">&nbsp;
<?php endif; ?> 
<input class="abm-boton abm-btn-cancel" type="submit" value="Cancelar" onclick="$('abm-form').action='mselect.php?cmd=close';">

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>

<script type="text/javascript">
Event.observe ('multicheck', 'click', function() {
	valor = $('multicheck').checked;

	$$('.mselect-checkbox').each(function(c) {c.checked = valor;});
});

</script>

<?php sendFooter(); ?>


