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
		//echo "<div class='row'>";
		echo "<div class='form-group col-lg-12'><input class='form-check-input' name='option' type='radio' $checked value='$i'>$opcion</div>"; 		
		$i++;
		//echo "</div>";
	}
?>
</table>

<br>
<input class="btn btn-primary btn-xs h btn-aceptar" type="submit" value="Aceptar">&nbsp; 
<input class="btn btn-secondary btn-xs h btn-cancelar" type="submit" value="Cancelar" onclick="$('abm-form').action='alta.php?cmd=close';">
<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
	<button class='btn btn-secondary btn-circle btn-xl h' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Actualizar" /></button>
<?php endif; ?>		

<?php if (!$screen->isValid()) :?>
	<div class="alert alert-danger">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>

<?php sendFooter(); ?>


