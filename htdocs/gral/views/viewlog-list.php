<?php
/**
 * Muestra la lista de archivos de log disponibles, que recibe en un array $archivos. 
 * El array asociativo tiene como clave el nombre del archivo y como valor, el resultado de la función stat() sobre el mismo.   
 * 
 * @package gral
 */
?>

<?php getHeader($menu_gral,$directorio_relativo); ?>

<style>
.viewlog td {text-align: center;}
.viewlog td.c1 {text-align: left;}
.viewlog td.c2 {text-align: right;}
.c0 {padding: 0 0.5em;}
pre { font-size: 10pt; }
</style>
	
<h2>Archivos de log</h2>
	

<table class='data_grid viewlog'>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Archivo</th>
			<th>Tamaño (bytes)</th>
			<th>Fecha</th>
		</tr>
	</thead>
<tbody>
<?php foreach ($archivos as $f => $info) : ?>
<tr class='renglon_par'>
	<td class='c0'>
		<a title="Bajar archivo completo" href="viewlog.php?cmd=download&file=<?php echo $f;?>"><img width="16" height="16" src="../imagenes/download.gif"></a>
		<a title="Ver últimas líneas" target="_blank" href="viewlog.php?cmd=tail&file=<?php echo $f;?>"><img width="16" height="16" src="../imagenes/detalles.gif"></a>
	</td>
	<td class='c1'><?php echo $f;?></td>
	<td class='c2'><?php echo $info[7];?></td>
	<td class='c3'><?php echo date('Y-m-d H:i:s',$info[9]);?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>


<?php getFooter($menu_gral,$directorio_relativo); ?>


