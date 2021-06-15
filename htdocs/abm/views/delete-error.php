<?php
/**
 * @package views
 * @author   
 */
 
sendHeader($screen->getTitulo() . ' - ERROR'); ?>

<style>
</style>


<h2><?php echo $screen->getTitulo(); ?></h2>

<h3>Error</h3>

<div class="abm-error-screen">
	<?php echo $screen->getError(); ?>
</div>

<br>

<form name='abm-form' id='abm-form' method='POST' action='delete.php?cmd=close'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">
	<input class="abm-boton abm-btn-close" type="submit" value="Cerrar">
</form>

<?php sendFooter(); ?>


