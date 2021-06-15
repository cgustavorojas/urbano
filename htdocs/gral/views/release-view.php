<?php
/**
 * Muestra información del checkout del repositorio 
 * @package gral
 */
?>

<style>
td.b { font-weight: bold; text-align: right; }
</style>


<?php getHeader($menu_gral,$directorio_relativo); ?>

<h2>Información del Release</h2>

<p>La siguiente información es parte del working copy de SVN:</p>

<table>
	<tr>
		<td class='b'>Repositorio :</td><td><?php echo $url;?></td>
	</tr>
	<tr>
		<td class='b'>Revisión :</td><td><?php echo $revision;?></td>
	</tr>
	<tr>
		<td class='b'>Fecha :</td><td><?php echo $fecha;?></td>
	</tr>
</table>

<?php getFooter($menu_gral,$directorio_relativo); ?>

