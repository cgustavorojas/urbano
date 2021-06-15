<?php
/**
 * @package views
 */

sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>


<form name='abm-form' id='abm-form' method='POST' action='alta.php?cmd=close'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<div class="abm-msg-ok">
	<?php echo $screen->getMsgOk(); ?>
</div>

<input type="submit" class="abm-boton abm-btn-return" value="Volver"/>

</form>

<?php sendFooter(); ?>

