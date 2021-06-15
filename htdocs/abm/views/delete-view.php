<?php
/**
 * @package views
 * @author   
 */
 
sendHeader($screen->getTitulo()); ?>

<style>
</style>


<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' id='abm-form' method='POST' action='delete.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">


<?php if ($screen->isConfirmable() || ! $screen->isValid()) : ?>
	<div class="abm-msg-confirmar">
	<?php echo $screen->getMsgConfirmar(); ?>
	</div>
	
	<br>
	<input class="abm-boton abm-btn-ok" type="submit" value="Aceptar">&nbsp; 
	<input class="abm-boton abm-btn-cancel" type="submit" value="Cancelar" onclick="$('abm-form').action='delete.php?cmd=close';">
	
<?php endif; ?>
	
<?php if (! $screen->isConfirmable() && $screen->isValid()) : ?>
	Eliminando registro ... 
	<script type="text/javascript">
		$('abm-form').submit();
	</script>
<?php endif; ?>
		
</form>

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

<?php sendFooter(); ?>


