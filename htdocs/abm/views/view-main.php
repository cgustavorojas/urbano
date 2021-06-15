<?php
/**
 * @package views
 * @author   
 */
/** */ 
sendHeader($screen->getTitulo()); 

$row = $screen->getRow();
$prefs = $screen->getUserPrefs(); 
?>

<h2><?php echo $screen->getTitulo(); ?></h2>


<script type="text/javascript">
function doAction(href, msgConfirm)
{
 	if (msgConfirm != '') {
 		if (! confirm(msgConfirm)) {
 			return false;
 		} 
 	} 
 	f1 = document.createElement('form'); 
 	f1.method = 'POST';
 	f1.action = href;  
 	document.body.appendChild(f1); 
 	f1.submit();
}
</script>

<form name='f' id='f' method='POST' action='edit.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<br>

<?php if ($screenManager->getCount() > 1):?>
	<input class='abm-boton abm-btn-return' type='submit' value='Volver' onclick="$('f').action='query.php?cmd=close';">
<?php endif; ?>

<?php 
	foreach ($screen->getAcciones() as $a)
	{
		if ($a->isEnabledForThisRow($row))
		{
			$href = $a->buildHref($row); 
			$txt  = $a->getTxt(); 
			$msgConfirm = Utils::coalesce($a->getMsgConfirm(), '');
			
			echo "<input class='abm-boton abm-btn-action' type='button' value='$txt' onclick=\"doAction('$href', '$msgConfirm');\">&nbsp;";
		}
	}	
?>

<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_ABM_HELP')):?>
	<input class='abm-boton abm-btn-help' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>">
<?php endif; ?>

	
</form>

<!--  este form es auxiliar para ejecutar las acciones globales (no las de a nivel de registro)  -->
<form name="fQueryActions" id="fQueryActions" method="post">
</form>

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<div id="abm-view-grupos">

<table>
<tr>

<?php for ($i = 1 ; $i <= $screen->getPosiciones() ; $i++) : ?>

	<td class="abm-view-grp-col">
		<?php foreach ($screen->getGrupos() as $g) : ?>
			<?php if ($g->getPosicion() == $i && $g->isEnabledForThisRow($row)) : ?>		
				<table cellpadding="0" cellspacing="0" class="abm-view-grupo">
				
					<?php if (! is_null($g->getTitulo())) : ?>
						<tr><td class="abm-view-grp-head" colspan="2"><?php echo $g->getTitulo(); ?></td></tr>
					<?php endif; ?>
					
					<?php foreach ($g->getAll() as $col) : ?>
					<tr>
						<td class="abm-view-grp-key"><?php echo $col->getTxt(); ?></td>
						<td class="abm-view-grp-value"><?php echo $col->getValueHtml($row, $prefs);?></td>
					</tr>
					
					<?php endforeach; ?>	
					
				</table>
			<?php endif; ?>
		
		<?php endforeach; ?>
	</td>
<?php endfor; ?>

</tr>
</table>

</div>

<script type="text/javascript">
var currentTab = <?php echo $screen->getCurrentTab(); ?>;

function doAjaxSelectTab(nro)
{
	$('tab').value = nro; 
	$('tabContent').innerHTML = "<img src='imagenes/cambiando.gif' alt='Cambiando...'>";
	var request = $('formTabs').request({
		method: 'post',
		onSuccess: function (transport) {
			$('tabContent').innerHTML = transport.responseText;
			$('tab' + nro).addClassName ('selected'); 
			$('tab' + currentTab).removeClassName ('selected');
			currentTab = nro; 
		}
	});
}

// para tabs de tipo tabquery
function doAjaxMove(pagina)
{
	$('grid1').action = 'view_tabquery.php?cmd=move&pagina=' + pagina; 
	var request = $('grid1').request({
		method: 'post',
		onSuccess: function (transport) {
			$('abm-div-ajax').innerHTML = transport.responseText; 
		}
	});	
}
</script>

<form id='formTabs' action='view.php?cmd=selectTab'>
	<input type='hidden' name='screenid' value='<?php echo $screen->getId(); ?>'/>
	<input type='hidden' name='tab' id='tab'/>
</form>

<?php if ($screen->getTabCount() > 0) :?>
	<div class="abm-view-tabs">
	
		<ul> 
		<?php
			$tabs = $screen->getTabs();
			for ($i = 0 ; $i < count ($tabs) ; $i++ )
			{
				if ($tabs[$i]->isEnabledForThisRow($row))
				{
					$css = $tabs[$i]->getCssForThisRow($row);
					$txt = $tabs[$i]->getTitulo();
					if ($i == $screen->getCurrentTab())
						echo "<li id='tab$i' class='abm-view-tabs $css selected'><a href='#' onclick='doAjaxSelectTab($i); return false;'><span class='$css'>$txt</span></a></li>";	
					else
						echo "<li id='tab$i' class='abm-view-tabs $css'><a href='#' onclick='doAjaxSelectTab($i); return false;'><span class='$css'>$txt</span></a></li>";
				}	
			} 
		?>
		</ul>
	
		<div id="tabContent">
			<?php include ($screen->getTab()->getViewToInclude()); ?>
		</div>
	
	</div>
<?php endif; ?>

	
<?php sendFooter(); ?>


