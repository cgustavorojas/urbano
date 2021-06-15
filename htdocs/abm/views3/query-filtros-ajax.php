<?php
/**
 * Muestra la sección de filtros en una pantalla QueryScreen. Puede ser llamado via ajax en los onChange() de los filtros
 * 
 * @package abm
 */
?>

<?php //print_r($screen->getFiltros()->getAll()); ?>

  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" id="filtro" type="button" >
          Filtros
         <i class="fas fa-plus-circle"></i>
        </button>
      </h5>
    </div>

      <div class="card-body ccc" style="display: none;">
            <table class='table table-responsive table-borderless'>
            <tr>
            <?php for ($i = 1 ; $i <= $screen->getFiltros()->getPosiciones() ; $i++) : ?>
            			<td class='abm-query-fposicion'>
            				<table class='table table-responsive table-borderless'>
            					<?php echo $screen->getFiltros()->getHtml($i); ?>
            				</table>
            			</td>
            		<?php endfor; ?>
            		</tr>
            		</table>
                </div>
      </div> 
 </div>
             
<p> 
</p>
	<div class="row">	
		<div class="form-group  mt-12">	
		<?php if ($screen->getBtnRefrescar()) : ?>
		<button class='btn btn-primary btn-xs h myButton' type='submit' value='actualizar' onclick="doAjaxFiltrar(); return false;"><img src="../icon/actualizar.png" alt="Actualizar" /></button>
		<?php endif; ?>
		
		<?php if ($screen->getBtnResetForm()) : ?>
		<button class='btn btn-primary btn-xs h myButton' type='reset' value='reset' onclick="document.getElementById('grid1').reset(); doAjaxFiltrar(); return false;"><img src="../icon/limpiarfiltros.png" alt="Limpiar" /></button>
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
					$noMarkDouble = is_null($a->getMarkDoubleSubmit()) || $a->getMarkDoubleSubmit()  ? 'false' : 'true';
					$onclick = "doActionGlobal('$href', $sendFiltros, '$msgConfirm', $noMarkDouble);";
					$img = is_null($a->getImg()) ? '../imagenes/Agregar.png' : $a->getImg();
					//print_r($a);
					echo "<button type='button' class='btn btn-primary btn-xs h myButton' value='$txt' onclick=\"$onclick\"><img src=\"$img\" alt=\"Actualizar\" /></button>&nbsp;";
				}
			}
		?>

		<?php if ($screen->getBtnExportar()) : ?>
		<button class='btn  btn-primary btn-xs h myButton' type='submit' value='exportar' onclick="$('grid1').action='query.php?cmd=exportar';"><img src="../icon/descargar.png" alt="Exportar" /></button>
		<?php endif; ?>
		<?php if ($screen->getBtnImprimir()) : ?>
		<button class='btn  btn-primary btn-xs h myButton' type='submit' value='imprimir' onclick="$('grid1').action='query.php?cmd=print';"><img src="../icon/Imprimir.png" alt="imprimir" /></button>
		<?php endif; ?>
		<?php if ($screen->getBtnConfigurar()) : ?>
		<button class='btn  btn-primary btn-xs h myButton' type='submit' value='Configurar' onclick="$('grid1').action='query.php?cmd=customize';"><img src="../icon/herramientas.png" alt="Configurar" /></button>
		<?php endif; ?>
		<?php if ($screenManager->getCount() > 1):?>
			<button class='btn btn-primary btn-xs h myButton' type='submit' value='Volver' onclick="$('grid1').action='query.php?cmd=close';"><img src="../icon/volver.png" alt="volver" /></button>
		<?php endif; ?>
		
		<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
			<button class='btn  btn-primary btn-xs h myButton' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Ayuda" /></button>
		<?php endif; ?>

</div>

</div>
<div class="row">		
		<div class="form-group ml-3 mt-3">	
			
		<?php if (count($screen->getOrders()) > 0) :?>
		
					<label >Ordenar por: </label>
			
								<select name="orderBy" id="orderBySelect"   onchange="doAjaxFiltrar()">
									<?php echo Utils::htmlOptions ($screen->getOrders(), $screen->getOrderBy()); ?>
								</select>
					
  	
			<?php endif; ?>
		</div>
		<?php if ($screen->getBtnPageSize()) : ?>
    		<div class="form-group ml-3 mt-3">	
    			<label >Líneas por pág.: </label>
    			
    						
    							<select name="pageSize" id="pageSizeSelect"  onchange="doAjaxFiltrar()">
    								<?php echo Utils::htmlOptions ($screen->getPageSizes(), $screen->getPageSize()); ?>
    							</select>
    			
  	  				
		<?php endif; ?>
			</div>
	
</div>	 
