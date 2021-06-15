<?php
/**
 * Vista de un TAB de tipo TabText dentro de una pantalla ViewScreen. 
 * 
 * La pantalla ViewScreen permite definir tabs. Cada tipo de tab muestra un
 * contenido diferente del resto y cada uno tiene una vista asociada. En particular,
 * el tab de tipo TabText muestra una columna de texto libre multi-línea del query principal. 
 * 
 * @package views
 * @author Beto
 */
?>
<?php

	$tab = $screen->getTab(); 
	
	
	 echo $tab->getHtml();
  
	
?>


		