<?php
/**
 * Vista de un TAB de tipo básico ViewTab dentro de una pantalla ViewScreen.
 * Lo que hace es mostrar la salida de la getHtml() del mismo tab.  
 * 
 */
?>

<style>
pre.tabtext { font-family: courier new ; font-size: 10pt; color: #333; }
</style>

<?php

	$tab = $screen->getTab(); 	
	$row = $screen->getRow(); 
	echo $tab->getHtml($row);
	
?>


		