<?php
/**
 * Muestra al usuario un mensaje de error cuando ocurrió un problema con el 
 * upload o eliminación de archivos. Llamado desde archivo.php. 
 * 
 * Recibe los parámetros "error" (string) y "info" (array) con detalles a 
 * mostrar al usuario. 
 * 
 * @package gral
 */
?>
<?php getHeader(); ?>

<h2>Falló la Operación</h2>

<h3><?php echo $error; ?></h3>

<h4>Información adicional</h4>

<table>

<?php foreach ($info as $k => $v) :?>
<tr>
	<td><?php echo $k?></td>
	<td><?php echo $v?></td>	
</tr>
<?php endforeach;?>

</table>

<br>
<form action="../abm/volver.php">
<input class="boton" type="submit" value="Volver">
</form>

<?php getFooter ($menu_gral, $directorio_relativo); ?>
 