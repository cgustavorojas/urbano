<?php
/**
 * @package views
 * @author   
 */
/** */
?>
<?php 
sendHeader($screen->getTitulo()); ?>


<h2><?php echo $screen->getTitulo(); ?></h2>

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<form name='grid1' id='grid1' method='POST'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

	<div id='abm-query-filtros'>
	
		<table>
		<tr>
		<?php for ($i = 1 ; $i <= $screen->getFiltros()->getPosiciones() ; $i++) : ?>
			<td class='abm-query-fposicion'>
				<table>
					<?php echo $screen->getFiltros()->getHtml($i); ?>
				</table>
			</td>
		<?php endfor; ?>
		</tr>
		<tr><td colspan='<?php echo $screen->getFiltros()->getPosiciones(); ?>'>
			<input class='abm-boton abm-btn-export' type='submit' value='Exportar' onclick="$('grid1').action='export.php?cmd=exportar';">
			
			<?php if ($screenManager->getCount() > 1):?>
				<input class='abm-boton abm-btn-return' type='submit' value='Volver' onclick="$('grid1').action='export.php?cmd=close';">
			<?php endif; ?>
		</td></tr>	
		
		</table>
		
	</div>
	
</form>
	
<script type="text/javascript">

	function eventHook (method)
	{
		$('grid1').action = 'export.php?cmd=eventHook&method=' + method; 
		$('grid1').submit(); 
		$('grid1').innerHTML = "<img src='imagenes/cambiando.gif' alt='Cambiando...'>";
	}
</script>
	
<?php sendFooter(); ?>


