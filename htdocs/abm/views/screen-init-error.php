<?php
/**
 * Vista incluída cuando alguna pantalla (funciona con todas) tira una excepción al momento
 * de inicializarse. Es un error particular porque al haber fallado la inicialización, no puedo
 * confiar en tener un objeto $screen válido, así que muestro el mensaje que viene en la 
 * exception ($e), con un título genérico, y doy únicamente la opción de volver. 
 * 
 * @package views
 * @author   
 */
 
sendHeader('ERROR'); ?>

<style>
</style>


<h2>Error</h2>

<div class="abm-error-screen">
	<?php echo $e->getMessage(); ?>
</div>

<br>

<form name='abm-form' id='abm-form' method='POST' action='volver.php'>
	<input class="abm-boton abm-btn-return" type="submit" value="Volver">
</form>

<?php sendFooter(); ?>


