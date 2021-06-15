<?php
/**
 * Muestra la pantalla del comando "system.php"
 * @package gral
 */
?>

<?php getHeader($menu_gral,$directorio_relativo); ?>

<style>
#resultado 
{
	font-size: 10pt; 
}
</style>

<h2>Ejecutar comando en el servidor</h2>

<form id="f" method="post">
	Comando a ejecutar: <input type="text" name="cmd" id="cmd" size="60">
	<input type="submit" onclick="ejecutar(); return false;" value="Ejecutar">
</form>

<br>

<pre id='resultado'>
</pre>

<script type="text/javascript">
$('cmd').focus();

function ejecutar()
{
	var request = $('f').request({
		method: 'post',
		onSuccess: function (transport) {
			$('resultado').innerHTML = transport.responseText;
		}
	});
	$('cmd').focus();
	$('cmd').select();
}
</script>

<?php getFooter($menu_gral,$directorio_relativo); ?>

