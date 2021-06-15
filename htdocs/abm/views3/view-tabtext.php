<?php
/**
 * Vista de un TAB de tipo TabText dentro de una pantalla ViewScreen. 
 * 
 * La pantalla ViewScreen permite definir tabs. Cada tipo de tab muestra un
 * contenido diferente del resto y cada uno tiene una vista asociada. En particular,
 * el tab de tipo TabText muestra una columna de texto libre multi-línea del query principal. 
 * 
 * @package views
 * @author 
 */
?>

<style>
pre.tabtext { font-family: courier new ; font-size: 10pt; color: #333; }
</style>

<?php

	$tab = $screen->getTab(); 
	
	if ($tab->isHtml()) { 
		echo "<div>";
	} else {
		echo "<pre class='tabtext'>";
	}

	$row = $screen->getRow(); 
	$txt = $row[$tab->getCampo()];
	if ($tab->isReplaceTabs()) {
		$txt = str_replace("\t", str_repeat(' ', $tab->getTabWidth()), $txt); 
	}
	echo $txt;

	if ($tab->isHtml()) { 
		echo "</div>";
	} else {
		echo "</pre>";
	}
	
?>


		