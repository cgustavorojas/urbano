<?php
/**
 * Action: view_tabquery.php
 * 
 * Responde a los comandos dentro de un tab de tipo TabQuery, dentro de una pantalla ViewScreen. 
 * 
 * @package abm 
 * 
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');

if (isset ($_REQUEST['cmd'])) {
	$cmd = $_REQUEST['cmd'];
} else {
	$cmd = 'init';
}

$screenManager = ScreenManager::getInstance(); 

switch ($cmd)
{
	case "move": 
			$screenid = $_REQUEST['screenid'];
			$screen = $screenManager->pop($screenid);
			$tab = $screen->getTab();
			$tab->parseRequest(); 
			$tab->setCurrentPage($_REQUEST['pagina']);
			
			$rs = $tab->executeQuery(true);
			
			include 'views/view-tabquery-ajax.php';
		break; 		
}

$screenManager->serialize(); 

