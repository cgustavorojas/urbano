<?php
/**
 * @package gral
 */
?>
<?php getHeader(); ?>

<h2>Agregar Archivo</h2>


<form enctype="multipart/form-data" id="f" method="post" action="archivo.php?cmd=save">
<input type="hidden" name="tabla" value="<?php echo $tabla; ?>">
<input type="hidden" name="pk" value="<?php echo $pk; ?>">

<table>
	<tr>
		<td>Archivo</td>
		<td><input name="uploadedfile" size="50" type="file"></td>
	</tr>
	<tr>
		<td>Descripción</td>
		<td><input name="txt" type="text" size="50" maxlength="100"></td>
	</tr>

</table>

	<br/>
	<input type="submit" value="Aceptar" class="abm-boton abm-btn-ok"/>
	<input type="submit" value="Cancelar" onclick="$('f').action='../abm/volver.php'" class="abm-boton"/>

</form>

<?php getFooter ($menu_gral, $directorio_relativo); ?>
 