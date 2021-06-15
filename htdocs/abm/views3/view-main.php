<?php
/**
 * @package views
 * @author MA AMD
 */
/** */ 
sendHeader($screen->getTitulo()); 

$row = $screen->getRow();
$prefs = $screen->getUserPrefs(); 
?>

<h2><?php echo $screen->getTitulo(); ?></h2>


<script type="text/javascript">
function doAction(href, msgConfirm, noMarkDouble)
{
	if (msgConfirm != '') {
 		if (! confirm(msgConfirm)) {
 			return false;
 		} 
 	}

	<?php if ($screen->getPreventDoubleSubmit()): ?>
	if (!checkDoubleSubmit(noMarkDouble)){
		var auxM = '<?php echo $screen->getPreventDoubleSubmitMsg();?>';
		if (auxM != '') alert(auxM);
		return false;
	}
	<?php endif; ?>

	f1 = document.createElement('form'); 
 	f1.method = 'POST';
 	f1.action = href;  
 	document.body.appendChild(f1); 
 	//setTimeout(function(){ f1.submit(); }, 10000);
 	f1.submit(); 	
}
 <?php if ($screen->getPreventDoubleSubmit()): ?>
 /**
  * Script para evitar que el form se envíe 2 veces.
  */
  var lastSubmitted = null;
  function checkDoubleSubmit(noMark) {
  	var nowTS = new Date()+0;
	if (lastSubmitted
		<?php if ($auxT = $screen->getPreventDoubleSubmitTime()): ?>
		&& (lastSubmitted + <?php echo $auxT;?> > nowTS)
		<?php endif; ?>
		) 
  	{
  		console.log('Stopping');
  		return false;	
  	}	

  	if ( (typeof noMark === "undefined") || !noMark){
  		lastSubmitted = nowTS;
  	}
  	
  	return true;
  };
 <?php endif; ?>
</script>

<form name='f' id='f' method='POST' action='edit.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<br>

<?php if ($screenManager->getCount() > 1):?>
	<input class='btn btn-secondary btn-xs h btn-cancelar' type='submit' value='Volver' onclick="$('f').action='query.php?cmd=close';">
<?php endif; ?>

<?php 
	foreach ($screen->getAcciones() as $a)
	{
		if ($a->isEnabledForThisRow($row))
		{
			$href = $a->buildHref($row); 
			$txt  = $a->getTxt(); 
			$msgConfirm = Utils::coalesce($a->getMsgConfirm(), '');
			$auxCss = $a->getCssClass();
			$noMarkDouble = (is_null($a->getMarkDoubleSubmit()) || $a->getMarkDoubleSubmit())  ? 'false' : 'true';
			
			echo "<input class='$auxCss btn btn-primary btn-xs h btn-aceptar' type='button' value='$txt' onclick=\"doAction('$href', '$msgConfirm', $noMarkDouble);\">&nbsp;";
		}
	}	
?>

<?php if ($screen->isHelpCargado() || Seguridad::tieneHabilitado('GRAL_MENU_HELP')):?>
	<button class='btn btn-secondary btn-circle btn-xl h' type='button' value='Ayuda' onclick="<?php echo HelpUtils::getJsOpen($screen->getHelpId()); ?>"><img src="../icon/ayuda.png" alt="Ayuda" /></button>
<?php endif; ?>

	
</form>

<!--  este form es auxiliar para ejecutar las acciones globales (no las de a nivel de registro)  -->
<form name="fQueryActions" id="fQueryActions" method="post">
</form>

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="class="alert alert-info"">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<div id="abm-view-grupos">

<br><br>
<div class="row">

<?php 
    switch ( $screen->getPosiciones()) {
        case 1:
            $c="col-lg-12";
            break;
        case 2:
            $c="col-lg-6";
            break;
        case 3:
            $c="col-lg-4";
            break;
        case 4:
            $c="col-lg-3";
            break;
}
    
    ?>
<?php for ($i = 1 ; $i <= $screen->getPosiciones() ; $i++) : ?>

	<div class="<?php echo $c;?>">
		<?php foreach ($screen->getGrupos() as $g) : ?>
			<?php if ($g->getPosicion() == $i && $g->isEnabledForThisRow($row)) : ?>		
                      <div class="card">
                         <?php if (! is_null($g->getTitulo())) : ?>
						  <h5 class="h5 card-header noticias-header"><?php echo $g->getTitulo(); ?></h5>		
					<?php endif; ?>
					  <div class="card-body" id='contenido'>
					  <table class="table table-sm ">
					<?php foreach ($g->getAll() as $col) : ?>
					   <tr>
					   <td><b><?php echo $col->getTxt().": "; ?></b></td>
						<td>
						<?php echo $col->getValueHtml($row, $prefs);?>
						</td>
				
					</tr>
					<?php endforeach; ?>	
					</table>
					</div>
				</div>
			<?php endif; ?>
		
		<?php endforeach; ?>
	</div>
<?php endfor; ?>

</div>


</div>
<br>
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
           //ajax jquery para actualizar  mapa  
			var $j = jQuery.noConflict();
			  var  d=$j("#pru").data("direccion")
			   //console.log(d);
               $j("#pru").load("../../mapa2.php",{dir:d});
			  
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
	
		<ul > 
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


