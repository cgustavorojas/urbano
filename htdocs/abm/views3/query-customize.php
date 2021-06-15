<?php
/**
 * @package views
 * @author MA AMD
 */
?>
<?php 
sendHeader($screen->getTitulo() . ' - Personalizar'); 

/**
 * Función auxiliar que genera código HTML de un elemento SELECT y sus elementos OPTION
 * para el caso particular de un combo Sí/No.  
 */
function boolSelect($name, $value)
{
	$html = "<select class='form-control input-sm' name='$name'>";
	
	if ($value)	{
		$html = $html . "<option selected value='true'>Sí</option><option value='false'>No</option>";
	} else {
		$html = $html . "<option value='true'>Sí</option><option selected value='false'>No</option>";
	}
	$html = $html . "</select>";
	return $html;
}

?>

<style>
</style>

<script type="text/javascript">

</script>

<h2>Personalización de la consulta</h2>

<form id="f" name="f" method="post" action="query.php?cmd=customizeSave">
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

	
<div class='col-lg-8'>	


  <h3 class="category-heading">Pantalla : <?php echo $screen->getTitulo(); ?> </h3>

  
	<h3>Filtros</h3>

	<table class='table'>
	<tr class="thead-light">
		<th>Filtro</th>
		<th>Leyenda</th>
		<th>Activo?</th>
		<th>Mostrar?</th>
	</tr>
	<?php 
		foreach ($screen->getFiltros()->getAll() as $f) 
		{
			$id = $f->getId(); 
			$txt = $f->getTxt(); 
			$active = $f->isActive();
			$printable = $f->isPrintable();
			
			echo "<tr>";
			echo "<td><b>$id</h2></b>";
			echo "<td><input class='form-control input-sm' type='text' name='fTxt[]' value='$txt'></td>";	
			echo "<td>" . boolSelect('fActive[]', $active) . "</td>";
			echo "<td>" . boolSelect('fPrintable[]', $printable) . "</td>";
			echo "</tr>";
		}	
	?>
	</table>

	<h3>Columnas</h3>
	
	<table class='table'>
	<tr class="thead-light">
		<th>Campo</th>
		<th>Leyenda</th>
		<th>Activa?</th>
		<th>Long. Máx</th>
		<th>Ancho (pdf)</th>
	</tr>
	<?php 
		foreach ($screen->getCols()->getAll() as $c) 
		{
			$id  = $c->getId(); 
			$txt = $c->getTxt(); 
			$active = $c->isActive();
			$maxLength = is_null($c->getMaxLength()) ? 0 : $c->getMaxLength();
			$printWidth = $c->getPrintWidth();
			
			echo "<tr>";
			echo "<td><b>$id</h2></b>";
			echo "<td><input class='form-control input-sm' type='text' name='cTxt[]' value='$txt'></td>";	
			echo "<td>" . boolSelect('cActive[]', $active) . "</td>";
			echo "<td><input class='form-control input-sm' size='4' maxlength='4' type='text' name='cMaxLength[]' value='$maxLength'></td>";	
			echo "<td><input class='form-control input-sm' size='4' maxlength='4' type='text' name='cPrintWidth[]' value='$printWidth'></td>";	
			echo "</tr>";
		}	
	?>
	</table>

	<input class="btn btn-primary" type="submit" value="Guardar">
	<input class="btn btn-secondary" type="submit" value="Cancelar" onclick="$('f').action='query.php?cmd=return';"/>
<?php 

	if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
	<input class="btn btn-warning" type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen('help_custom_query'); ?>">
<?php endif; ?>
		
</form>
</div>	


<?php sendFooter(); ?>


