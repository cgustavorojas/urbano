<?php
/**
 * @package views
 * @author   
 */
 
sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' id='abm-form' method='POST' action='alta.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<table>
<?php 
	foreach ($screen->getInputs()->getAll() as $input) {
		echo $input->getHtml(); 		
	}
?>
</table>		

<br>
<input class="abm-boton abm-btn-ok" type="submit" value="<?php echo $screen->getBtnAceptar(); ?>">&nbsp; 
<input class="abm-boton abm-btn-cancel" type="submit" value="<?php echo $screen->getBtnCancelar(); ?>" onclick="$('abm-form').action='alta.php?cmd=close';">

	<input class='abm-boton abm-btn-help' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>">
		

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>

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
</script>

<script type="text/javascript">
<?php 
	foreach ($screen->getInputs()->getAll() as $input) 
	{
		echo $input->getJavaScript(); 		
	}
	
	echo $screen->getJavaScript();
?>	
</script>


<?php sendFooter(); ?>


