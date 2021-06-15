<?php
/**
 * Vista copia de la vista "screen-init-error". la unica diferencia con la anterior es
 * que no tiene el boton volver.
 * se implementa al efecto de mostrar errores con las pantallas que no pertenecen a la 
 * clase Screen, por lo cual no tienen definidos url "adonde volver"
 * 
 * @package views
 * @author cpj
 */
 
sendHeader('ERROR'); ?>

<style>
</style>


<h2>Error</h2>

<div class="abm-error-screen">
	<?php echo $e->getMessage(); ?>
</div>

<br>

<?php sendFooter(); ?>


