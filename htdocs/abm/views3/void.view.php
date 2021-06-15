<?php
/**
 * @package views
 */
 
sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' class='abm-screen-option' id='abm-form' method='POST' action='void.php?cmd=element'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>
<br>
<table class='abm-option-list'>
<?php


foreach ($screen-> getElemets() as $element)  {
   
    echo "<tr><td>";
        echo $element;
	echo "<br><br><br></td></tr>\n";     
				
	  
	}
?>
</table>

<br>

<input class="btn btn-secondary btn-xs h btn-cancelar" type="submit" value="Cancelar" onclick="$('abm-form').action='void.php?cmd=close';">
<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
	<button class='btn btn-secondary btn-circle btn-xl h'' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Actualizar" /></button>
<?php endif; ?>		

<?php if (!$screen->isValid()) :?>
	<div class="abm-error-screen">
		<?php echo $screen->getError(); ?>
	</div>
<?php endif; ?>

</form>


<?php sendFooter(); ?>


