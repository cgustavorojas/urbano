<?php
/**
 * Vista de un TAB de tipo TabQuery dentro de una pantalla ViewScreen. 
 * La pantalla ViewScreen permite definir tabs. Cada tipo de tab muestra un
 * contenido diferente del resto y cada uno tiene una vista asociada. En particular,
 * el tab de tipo TabQuery muestra un query (similar a QueryScreen) que se refresca via ajax
 * (view-tabquery-ajax.php). 
 * 
 * @package views
 * @author 
 */
?>

<form name='grid1' id='grid1' method='POST'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

	<?php if (! is_null($screen->getTab()->getMsgInfo())) : ?>
	
		<div class="abm-msg-info">
			<?php echo $screen->getTab()->getMsgInfo(); ?>
		</div>
	
	<?php endif; ?>	
	
	<div class='abm-div-actions'>
	<?php 
		$row = $screen->getRow();
		
		foreach ($screen->getTab()->getAcciones() as $a)
		{
			if ($a->isEnabledForThisRow($row))
			{
				$href = $a->buildHref($row); 
				$txt  = $a->getTxt(); 
				$cssClass = $a->getCssClass();
				$msgConfirm = Utils::coalesce($a->getMsgConfirm(), '');
				
				// fQueryActions está definido en la página principal del view (view-main.php)
				echo "<input type='button' class='abm-boton abm-btn-action $cssClass' value='$txt' onclick=\"doAction('$href', '$msgConfirm');\">&nbsp;";
			}
		}
	?>
	</div>
	
	<!-- El contenido de divGrid se actualiza via ajax cuando se filtra o se avanza por las páginas -->
	<div id="abm-div-ajax">	
		<?php include ('view-tabquery-ajax.php'); ?>
	</div><!-- abm-div-ajax -->

</form>
		

		