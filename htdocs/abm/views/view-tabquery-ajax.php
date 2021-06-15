<?php
/**
 * Código HTML para mostrar la grilla en un TAB de una pantalla de query. 
 * @package abm
 */

	$prefs = $screen->getUserPrefs();
	$cols = 0;  
	$tab = $screen->getTab(); 

	if ($rs->getRowCount() > 0)
	{
			echo "<table class='abm-grid' cellspacing='2' cellpadding='0'>\n";
		
			echo "<tr class='abm-grid-header'>\n";
			
				if (count($tab->getAccionesItem()) > 0)	{
					echo "<th>&nbsp;</th>\n";
					$cols++;
				}
				
				foreach ($tab->getCols()->getAll() as $col) {
					if ($col->isActive()) {
						echo "<th>" . $col->getTxt() . "</th>\n";
						$cols++; 
					}
				}
			
			echo "</tr>\n";
		
			$parImpar = -1; 
			while ($row = $rs->getRow())
			{
				$parImpar = $parImpar * (-1);
				$cssClass = $parImpar == 1 ? 'abm-grid-impar' : 'abm-grid-par';
				echo "<tr class='$cssClass'>\n";
				
				if (count($tab->getAccionesItem()) > 0)
				{
					echo "<td>";
					foreach ($tab->getAccionesItem() as $a)
					{
						if ($a->isEnabledForThisRow($row)) {
							$href  = $a->buildHref($row);
							$src   = $a->getImg(); 
							if (strpos($src, '/') === false) { 
								$src   = 'imagenes/' . $src;
							}
							$alt   = $a->getTxt(); 
							$title = $a->getTxt(); 

							$msgConfirm = Utils::coalesce($a->getMsgConfirm(), '');
							
							echo "  <a class='abm-action-item' href='#' onclick=\"doAction('$href', '$msgConfirm'); return false;\"><img src='$src' alt='$alt' title='$title'></img></a>\n";
						}
					}
					echo "</td>"; 
				} // si hay acciones a nivel de ítem
				
				
				foreach ($tab->getCols()->getAll() as $col)
				{
					if ($col->isActive())
					{
						$value = $col->getValueHtml($row, $prefs);
						$cssClass = ''; 
						$style = '';
						$style = $col->getAlign() == Column::CENTER ? $style . 'text-align:center;' : $style;
						$style = $col->getAlign() == Column::LEFT   ? $style . 'text-align:left;'   : $style;
						$style = $col->getAlign() == Column::RIGHT  ? $style . 'text-align:right;'  : $style; 
						
						echo "<td class='$cssClass' style='$style'>$value</td>\n";
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
							<?php if ($tab->getPageCount() > 1) : ?>
								<?php if ($tab->getCurrentPage() > 1) : ?>
									<a href='javascript:doAjaxMove(1)' title='1er. página'>&lt;&lt;</a>
									<a href='javascript:doAjaxMove(<?php echo $tab->getCurrentPage()-1;?>)' title='Retroceder'>&lt;</a>
								<?php endif; ?>	
								<?php if ($tab->getCurrentPage() == 1) : ?>
									<span>&lt;&lt;</span>
									<span>&lt;</span>
								<?php endif; ?>	
								<span><?php echo $tab->getCurrentPage() . '/' . $tab->getPageCount(); ?></span>						
								<?php if ($tab->getCurrentPage() < $tab->getPageCount()) : ?>
									<a href='javascript:doAjaxMove(<?php echo $tab->getCurrentPage()+1;?>)' title='Avanzar'>&gt;</a>
									<a href='javascript:doAjaxMove(<?php echo $tab->getPageCount();?>)' title='Última página'>&gt;&gt;</a>
								<?php endif; ?>							
								<?php if ($tab->getCurrentPage() == $tab->getPageCount()) : ?>
									<span>&gt;&gt;</span>
									<span>&gt;</span>
								<?php endif; ?>							
							<?php endif; ?>
						</td>
						
						<td>&nbsp;</td>
						
						<td style='text-align: right'>Registros: <?php echo $tab->getRowCount(); ?>
						<?php 
							if(is_array($tab->getTotales()))
							{
								foreach ($tab->getTotales() as $total)
								{
									echo '<br>' . $total['txt'] . ': ' . $prefs->toString($total['valor'], Tipo::MONEY);
								}
							}
						?>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<?php 
			
		
			echo "</table>\n"; 
			
	} else {		 //si había al menos 1 registros.
		 	
		echo '<h2>' . $tab->getMsgEmpty() . '</h2>';
	}
		
	