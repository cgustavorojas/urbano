<?php
/**
 * Lista los archivos y permite bajarlos. 
 * @package gral
 */
?>

<style>
.archivos th { padding: 0 1em; }
.archivos td { padding: 0 1em; }
.archivos .c3 { text-align: center; }
.archivos .c4 { text-align: center; } 
</style>

<?php getHeader($menu_gral,$directorio_relativo); ?>

<h2>Documentación y Manuales</h2>

<p>Los siguientes documentos están disponibles para ser bajados a su PC</p>

<table class='data_grid archivos'>
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>Módulo</th>
			<th>Descripción</th>
			<th>Versión</th>
			<th>Fecha Publicación</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($archivos as $a) : ?>
		<tr class='renglon_par'>
			<?php if (substr ($a[4],0,4) != 'http' ) {
						$a[4] = '../archivos/' . $a[4];
				  } 
			?>
			<td class='c0'><a target='_blank' href='<?php echo $a[4];?>'><img src='../imagenes/download.gif' width='16' height='16' alt='Bajar archivo'></a></td>
			<td class='c1'><?php echo $a[0];?></td>
			<td class='c2'><?php echo $a[1];?></td>
			<td class='c3'><?php echo $a[2];?></td>
			<td class='c4'><?php echo $a[3];?></td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>


<?php getFooter($menu_gral,$directorio_relativo); ?>

