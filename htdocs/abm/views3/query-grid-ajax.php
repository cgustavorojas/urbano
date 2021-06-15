<?php
/**
 * Código HTML para mostrar la grilla en una pantalla de query. 
 * @package abm
 */

	$prefs = $screen->getUserPrefs();
	$cols = 0;  

	if ($screen->getRowCount() > 0)
	{
	    echo "<div class='table-responsive'>";
			echo "<table class='table  table-view' >\n";
		
			echo "<tr>\n";
			
				if ($screen->isMultiSelect()) {
					echo "<th><input id='multicheck' name='multicheck' type='checkbox' onclick='doAjaxSelUnSelAll(this);'></th>";
					$cols++;
				}
			
				if (count($screen->getAccionesItem()) > 0)	{
					echo "<th>&nbsp;</th>\n";
					$cols++;
				}
				
				foreach ($screen->getCols()->getAll() as $col) {
					if ($col->isActive() and $col->isEnabled()) {
						echo "<th nowrap>" . $col->getTxt() . "</th>\n";
						$cols++; 
					}
				}
			
			echo "</tr>\n";
		
			$campoMultiSelect = $screen->getCampoMultiSelect(); 
			
			$parImpar = -1; 
			while ($row = $rs->getRow())
			{
				$parImpar = $parImpar * (-1);
				$cssClass = $parImpar == 1 ? 'abm-grid-impar' : 'abm-grid-par';
				echo "<tr >\n";
				
				if (! is_null($campoMultiSelect))
				{
					$value_sel = $row[$campoMultiSelect];
					$checked = $screen->isSelected($value_sel) ? 'checked' : '';
					echo "<td nowrap><input type='checkbox' $checked class='mselect-checkbox' value='$value_sel' onclick='doAjaxSelUnSel(this);'></td>";
				}
				
				if (count($screen->getAccionesItem()) > 0)
				{
					echo "<td nowrap>";
					foreach ($screen->getAccionesItem() as $a)
					{
						if ($a->isEnabledForThisRow($row)) {
							$href   = $a->buildHref($row); 
							$src    = $a->getImg();
							if (strpos($src, '/') === false) {
								$src    = '../imagenes/' . $src;
							}
							$alt    = $a->getTxt(); 
							$title  = $a->getTxt(); 
							$cssClass = $a->getCssClass();
							$imgtag = "<img src='$src' alt='$alt' title='$title'></img>";
							
							// Si no hay imagen pongo el title en lugar del tag <img ...></img> 
							if (is_null($a->getImg())) { $imgtag = $a->getTxt(); }
							
							$msgConfirm = is_null($a->getMsgConfirm()) ? '' : $a->getMsgConfirm(); 
							$target     = is_null($a->getTarget())     ? '' : $a->getTarget();
							$targetOpts = is_null($a->getTargetOpts())     ? '' : $a->getTargetOpts();
							$noMarkDouble = is_null($a->getMarkDoubleSubmit()) || $a->getMarkDoubleSubmit()  ? 'false' : 'true';
							
							echo "<a class='abm-action-item $cssClass' href='#' onclick=\"doActionItem('$href', '$msgConfirm', '$target', '$targetOpts', $noMarkDouble); return false;\">$imgtag</a>";
						}
					}
					echo "</td>\n"; 
				} // si hay acciones a nivel de ítem
				
				
				foreach ($screen->getCols()->getAll() as $col)
				{
					if ($col->isActive() and $col->isEnabled())
					{
						$value = $col->getValueHtml($row, $prefs);
						$cssClass = ''; 
						$style = '';
						$style = $col->getAlign() == Column::CENTER ? $style . 'text-align:center;' : $style;
						$style = $col->getAlign() == Column::LEFT   ? $style . 'text-align:left;'   : $style;
						$style = $col->getAlign() == Column::RIGHT  ? $style . 'text-align:right;'  : $style; 
						
						echo "<td class='$cssClass' style='$style' nowrap>$value</td>\n";
					}			
				} // foreach col
				
				
				echo "</tr>\n";
				
			} // while $row
		
			?>
			<tr class='abm-grid-footer'>
				<td colspan='<?php echo $cols; ?>'>
					<table width='100%' cellpadding='0' cellspacing='0'>
					<tr>
					
						<td class='abm-grid-flechas'>
							<?php if ($screen->getPageCount() > 1) : ?>
								<?php if ($screen->getCurrentPage() > 1) : ?>
									<a href='javascript:doAjaxMove(1)' title='1er. página'>&lt;&lt;</a>
									<a href='javascript:doAjaxMove(<?php echo $screen->getCurrentPage()-1;?>)' title='Retroceder'>&lt;</a>
								<?php endif; ?>	
								<?php if ($screen->getCurrentPage() == 1) : ?>
									<span>&lt;&lt;</span>
									<span>&lt;</span>
								<?php endif; ?>	
								<span><?php echo $screen->getCurrentPage() . '/' . $screen->getPageCount(); ?></span>						
								<?php if ($screen->getCurrentPage() < $screen->getPageCount()) : ?>
									<a href='javascript:doAjaxMove(<?php echo $screen->getCurrentPage()+1;?>)' title='Avanzar'>&gt;</a>
									<a href='javascript:doAjaxMove(<?php echo $screen->getPageCount();?>)' title='Última página'>&gt;&gt;</a>
								<?php endif; ?>							
								<?php if ($screen->getCurrentPage() == $screen->getPageCount()) : ?>
									<span>&gt;&gt;</span>
									<span>&gt;</span>
								<?php endif; ?>							
							<?php endif; ?>
						</td>
						
						<td class='abm-grid-totales'><b>
							Registros: <?php echo $screen->getRowCount(); ?>
							<?php 
								foreach ($screen->getTotales() as $total) {
									echo '<br>' . $total['txt'] . ': ' . $prefs->toString($total['valor'], Tipo::MONEY);
								}
							?>
						</b></td>
						
						<td>&nbsp;</td>
						
					</tr>
					</table>
				</td>
			</tr>
			
			<?php 
			
		
			echo "</table>\n"; 
		echo "</div>";	
			
	} else {		 //si había al menos 1 registros.
		 	
		echo '<h2>' . $screen->getMsgEmpty() . '</h2>';
		
		$filtros = $screen->getFiltros()->getAll();
	
		foreach ($filtros as $filtro){

			if ($filtro->getError() !=''){
			 echo "<b class='alert alert-danger'>Error Filtro: ".$filtro->getTxt()." --> ".$filtro->getError()."</b> </br>";
			}

		}


	}
		
	