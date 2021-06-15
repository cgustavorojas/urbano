<?php
/**
 * @package views
 */

sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>


<form name='abm-form' id='abm-form' method='POST' action='import.php?cmd=close'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<div class="abm-msg-ok">
	<?php echo $screen->getMsgOk(); ?>
</div>

<input type="submit" class="btn btn-secondary btn-xs h btn-cancelar" value="Volver"/>

</form>

<?php sendFooter(); ?>

