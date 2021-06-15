<?php
/**
 * Muestra el perfil del usuario actualmente logueado. 
 *  
 * @package gral
 */
?>

<?php getHeader($menu_gral,$directorio_relativo); ?>

<style>
table.data_grid th { padding: 0 1em 0 1em; }
table.data_grid td { padding: 0 1em 0 1em; }
</style>

<h2>Mi Perfil</h2>

<p>Esta pantalla muestra la lista de perfiles y permisos que Ud. tiene habilitados en el sistema.</p>

<h3>Perfiles habilitados</h3>

<table class='data_grid'>
	<tr><th>Código</th><th>Descripción</th></tr>
	<?php while ($p = $perfiles->getRow()) : ?>
		<tr>
			<td><?php echo $p['id_perfil']?></td>
			<td><?php echo $p['descripcion']?></td>
		</tr>
	<?php endwhile; ?>
</table>

<h3>Opciones de menú</h3>

<table class='data_grid'>
	<tr><th>Módulo</th><th>Opción</th></tr>
	<?php while ($p = $permisos_m->getRow()) : ?>
		<tr>
			<td><?php echo $p['sistema']?></td>
			<td><?php echo $p['permiso']?></td>
		</tr>
	<?php endwhile; ?>
</table>

<h3>Permisos adicionales</h3>

<table class='data_grid'>
	<tr><th>Módulo</th><th>Permiso</th><th>Descripción</th></tr>
	<?php while ($p = $permisos_p->getRow()) : ?>
		<tr>
			<td><?php echo $p['sistema']?></td>
			<td><?php echo $p['id_permiso']?></td>
			<td><?php echo $p['permiso']?></td>
		</tr>
	<?php endwhile; ?>
</table>

<?php getFooter($menu_gral,$directorio_relativo); ?>


