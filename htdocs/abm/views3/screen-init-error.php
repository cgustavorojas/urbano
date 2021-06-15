<?php
/**
 * Vista incluída cuando alguna pantalla (funciona con todas) tira una excepción al momento
 * de inicializarse. Es un error particular porque al haber fallado la inicialización, no puedo
 * confiar en tener un objeto $screen válido, así que muestro el mensaje que viene en la 
 * exception ($e), con un título genérico, y doy únicamente la opción de volver. 
 * 
 * @package views
 * @author MA AMD
 */
 
sendHeader('SGC ERROR'); ?>

<style>
</style>


<h2 class="text-danger">Error <img src='../icon/atencion.png'></img></h2>

<div class="alert alert-danger col-lg-8" role="alert"">
	<?php echo $e->getMessage(); ?>
</div>

<br>

<form name='abm-form' id='abm-form' method='POST' action='volver.php'>
	<input class="btn btn-secondary btn-xs h btn-cancelar" type="submit" value="Volver">
</form>

<?php sendFooter(); ?>


