<?php
/**
 * @package views
 * @author   
 */
/** */
?>
<?php sendHeader($screen->getTitulo()); ?>

<script type="text/javascript">

/**
 * Ante el click del botón "Refrescar", envía via AJAX el form al server
 * y actualiza la grilla de resultados. 
 */
function doAjaxFiltrar()
{
	$('abm-div-ajax').innerHTML = "<img src='imagenes/cambiando.gif' alt='Cambiando...'>";
	
	$('grid1').action = 'query.php?cmd=filtrar'; 
	var request = $('grid1').request({
		method: 'post',
		onSuccess: function (transport) {
			$('abm-div-ajax').innerHTML = transport.responseText; 
		}
	});
}

function eventHook(method)
{
	$('grid1').action = 'query.php?cmd=eventHook&method=' + method;
	var request = $('grid1').request({
		method: 'post',
		onSuccess: function (transport) {
			$('abm-query-filtros').innerHTML = transport.responseText;
			doAjaxFiltrar(); 
		}
	});
	$('abm-query-filtros').innerHTML = "<img src='imagenes/cambiando.gif' alt='Cambiando...'>";
}


/**
 * Ante el click sobre una flecha de navegación, envía via AJAX el request 
 * y actualiza la grilla de resultados. 
 */
function doAjaxMove(pagina)
{
	$('grid1').action = 'query.php?cmd=move&pagina=' + pagina; 
	var request = $('grid1').request({
		method: 'post',
		onSuccess: function (transport) {
			$('abm-div-ajax').innerHTML = transport.responseText; 
		}
	});	
}

/**
 * Ejecuta una acción global (a nivel pantalla y no a nivel registro. 
 * En función de si hay que enviar el valor de los filtros o no, hace un submit de un formulario distinto
 * (el mismo formulario de los filtros o un formulario vacío). Si se especifica un mensaje de confirmación,
 * pide una confirmación simple mediante javascript. 
 */
function doActionGlobal(href, sendFiltros, msgConfirm)
{
 
	if (msgConfirm != '') {
		if (! confirm(msgConfirm)) {
			return false;
		} 
	}

	if (sendFiltros) {
		$('grid1').action = href; 
		$('grid1').submit();
	} else {
		$('fQueryActions').action = href;
		$('fQueryActions').submit();
	} 
	 	
}
 /**
  * Ejecuta una acción a nivel de un registro.  
  * Si se especifica un mensaje de confirmación, pide una confirmación simple mediante javascript. 
  */
 function doActionItem(href, msgConfirm, target, targetOpts)
 {
  
 	if (msgConfirm != '') {
 		if (! confirm(msgConfirm)) {
 			return false;
 		} 
 	}

	if (target != '')
	{
		window.open (href, target, targetOpts); 
		
	} else {
	 	$('fQueryActions').action = href;
	 	$('fQueryActions').submit();
	}
 	
 }

 function doAjaxSelUnSel(checkbox)
 {
	 screenid = $('screenid').value; 
	 
	 if (checkbox.checked) {
		 new Ajax.Request('/abm/query.php?screenid=' + screenid + '&cmd=select&sid=' + checkbox.value,
				  {
				    method:'get'
				  });
	 } else {
		 new Ajax.Request('/abm/query.php?screenid=' + screenid + '&cmd=unselect&sid=' + checkbox.value,
				  {
				    method:'get'
				  });
	 }	 
 }

 function doAjaxSelUnSelAll()
 {
	 valor = $('multicheck').checked;
	 valores = ''; 
	 $$('.mselect-checkbox').each(function(c,i) {c.checked = valor; valores = valores + c.value + ','; });

	 screenid = $('screenid').value;

	 if (valor) {
		 new Ajax.Request('/abm/query.php?screenid=' + screenid + '&cmd=select&sid=' + valores,
				  {
				    method:'get'
				  });
	 } else {
		 new Ajax.Request('/abm/query.php?screenid=' + screenid + '&cmd=unselect&sid=' + valores,
				  {
				    method:'get'
				  });
	 }	 
 }
 
</script>

<h2><?php echo $screen->getTitulo(); ?></h2>

<?php if (! is_null($screen->getMsgInfo())) : ?>

	<div class="abm-msg-info">
		<?php echo $screen->getMsgInfo(); ?>
	</div>

<?php endif; ?>

<form name='grid1' id='grid1' method='POST'>
	<input id="screenid" type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">
	
	<div id='abm-query-filtros'>
		<?php include ('query-filtros-ajax.php'); ?>				
	</div>
	
	<!-- El contenido de abm-div-ajax se actualiza via ajax cuando se filtra o se avanza por las páginas -->
	<div id="abm-div-ajax">
		<?php include ('query-grid-ajax.php'); ?>
	</div>
		
</form>

<!--  este form es auxiliar para ejecutar las acciones globales (no las de a nivel de registro)  -->
<form name="fQueryActions" id="fQueryActions" method="post">
</form>

<script type="text/javascript">
<?php 
/*
 * Pongo en foco el filtro que tenga el flag correspondiente.  
 */
 foreach ($screen->getFiltros()->getAll() as $i) 
 {
		if ($i->hasFocus()) {
			$id = $i->getSelectableId();
			echo "\$('$id').select();\n";
			echo "if (\$('$id').focus)\n \$('$id').focus();";	
		}
 }
 ?>
</script>
	
<?php sendFooter(); ?>


