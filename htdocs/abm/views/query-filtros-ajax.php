<?php
/**
 * Muestra la sección de filtros en una pantalla QueryScreen. Puede ser llamado via ajax en los onChange() de los filtros
 * 
 * @package abm
 */
?>
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
		</table>

		<?php if ($screen->getBtnRefrescar()) : ?>
		<input class='abm-boton abm-btn-refresh' type='submit' value='Ejecutar' onclick="doAjaxFiltrar(); return false;">
		<?php endif; ?>

		<?php 
			foreach ($screen->getAcciones() as $a)
			{
				if ($a->isEnabled())
				{
					$href = $a->buildHref(); 
					$txt  = $a->getTxt(); 
					$cssClass = $a->getCssClass();
					$sendFiltros = $a->getSendFiltros() ? 'true' : 'false';
					$msgConfirm = is_null($a->getMsgConfirm()) ? '' : $a->getMsgConfirm(); 
					$onclick = "doActionGlobal('$href', $sendFiltros, '$msgConfirm');";
					echo "<input type='button' class='abm-boton abm-btn-action $cssClass' value='$txt' onclick=\"$onclick\">&nbsp;";
				}
			}
		?>

		
		<?php if ($screenManager->getCount() > 1):?>
			<input class='abm-boton abm-btn-return' type='submit' value='Volver' onclick="$('grid1').action='query.php?cmd=close';">
		<?php endif; 
		
		
		?>
		
		

		<?php if (count($screen->getOrders()) > 0) :?>
			Ordenar por: 
			<select name="orderBy" id="orderBySelect" class="abm-combo" onchange="doAjaxFiltrar()">
				<?php echo Utils::htmlOptions ($screen->getOrders(), $screen->getOrderBy()); ?>
			</select>
		<?php endif; ?>
		<?php if ($screen->getBtnPageSize()) : ?>
			Tamaño de pág.: 
			<select name="pageSize" id="pageSizeSelect" class="abm-combo" onchange="doAjaxFiltrar()">
				<?php echo Utils::htmlOptions ($screen->getPageSizes(), $screen->getPageSize()); ?>
			</select>
		<?php endif; ?>
		
		
		