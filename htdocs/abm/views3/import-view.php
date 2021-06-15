<?php
/**
 * @package views
 */
 
sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' id='abm-form' enctype="multipart/form-data" method='POST' action='import.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<table>
	<tr>
		<td>Archivo</td>
		<td><input name="uploadedfile" size="50" type="file"> </td>
	</tr>
<?php 
	foreach ($screen->getInputs()->getAll() as $input) {
		echo $input->getHtml(); 		
	}
?>
</table>		

<br>
<input class="abm-boton abm-btn-ok" type="submit" value="Aceptar">&nbsp; 
<input class="abm-boton abm-btn-cancel" type="submit" value="Cancelar" onclick="$('abm-form').action='alta.php?cmd=close';">
		

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>


<script type="text/javascript">
</script>


<?php sendFooter(); ?>


