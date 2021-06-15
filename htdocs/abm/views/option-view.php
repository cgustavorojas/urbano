<?php
/**
 * @package views
 */
 
sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' class='abm-screen-option' id='abm-form' method='POST' action='option.php?cmd=select'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<table class='abm-option-list'>
<?php
	$i = 0; 
	foreach ($screen->getOpciones() as $opcion) {
		$checked = $i == 0 ? 'checked' : ''; 
		echo "<tr><td><input name='option' type='radio' $checked value='$i'></td><td>$opcion</td></tr>\n"; 		
		$i++;
	}
?>
</table>

<br>
<input class="abm-boton abm-btn-ok" type="submit" value="Aceptar">&nbsp; 
<input class="abm-boton abm-btn-cancel" type="submit" value="Cancelar" onclick="$('abm-form').action='alta.php?cmd=close';">
<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_ABM_HELP')):?>
	<input class='abm-boton abm-btn-help' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>">
<?php endif; ?>		

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>

<?php sendFooter(); ?>


