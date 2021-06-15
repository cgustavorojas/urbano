<?php
/**
 * @package views
 */

sendHeader($screen->getTitulo()); ?>

<h2><?php echo $screen->getTitulo(); ?></h2>


<form name='abm-form' id='abm-form' method='POST' action='import.php?cmd=close'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">

<div class="abm-error-screen">
Se produjo un error que impidó el correcto procesamiento del archivo. 
El archivo fue recibido en el servidor pero se encontró un error al procesar su contenido. 
A continuación puede encontrar un detalle del error encontrado. Una vez solucionado el problema,
puede volver a procesar el mismo archivo (lo encontrará en la lista de archivos con estado E=Error)
o subir un nuevo archivo. 
</div>

<table class="abm-info">
	<tr>
		<td class="abm-key">Error</td>
		<td><?php echo $screen->getError(); ?></td>
	</tr>
	<tr>
		<td class="abm-key">Nro. de línea</td>
		<td><?php echo $screen->getNroLineaActual(); ?></td>
	</tr>
	<tr>
		<td class="abm-key">Contenido de la línea</td>
		<td><pre><?php echo $screen->getLineaActual(); ?></pre></td>
	</tr>
	
</table>

<input type="submit" class="abm-boton abm-btn-return" value="Volver"/>

</form>

<?php sendFooter(); ?>

