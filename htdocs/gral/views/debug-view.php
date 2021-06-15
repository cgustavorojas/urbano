<?php
/**
 * Muestra información de debug. LLamado desde ../debug.php
 * 
 * @package gral
 */
?>

<?php getHeader($menu_gral,$directorio_relativo); ?>
	
<h2>Información de debug</h2>
	
<h3>Configuración de php</h3>	
	
<p><a target="_blank" href="debug.php?cmd=phpinfo">Abrir phpinfo() en ventana nueva</a></p>

<h3>Contenido de $SERVER</h3>

<table>
	<?php foreach ($_SERVER as $key => $value) : ?>
		<tr>
			<td class="key"><?php echo $key?></td>
			<td class="value"><?php echo $value?></td>
		</tr>	
	<?php endforeach;?>
</table>
	
<h3>Contenido de $SESSION</h3>

<table>
	<?php foreach ($_SESSION as $key => $value) : ?>
		<tr>
			<td class="key"><?php echo $key?></td>
			<td class="value"><?php  echo (is_object($value) ? '___objeto___' : $value); ?></td>
		</tr>	
	<?php endforeach;?>
</table>
	
<h3>Contenido de $_COOKIE</h3> 

<table>
	<?php foreach ($_COOKIE as $key => $value) : ?>
		<tr>
			<td class="key"><?php echo $key?></td>
			<td class="value"><?php echo $value?></td>
		</tr>	
	<?php endforeach;?>
</table>

<h3>Base de datos</h3>

<table>
	<tr>
		<td class="key">Connection String</td>
		<td class="value"><?php echo _CONEXION_BASE; ?></td>
	</tr>	
</table>

<?php getFooter($menu_gral,$directorio_relativo); ?>

