<?php
/**
 * Action: view.php
 * 
 * Maneja la interacción con un objeto ViewScreen.
 * Tiene toda la lógica para el manejo de una pantalla de visualización de un registro 
 * en forma genérica. Recibe como parámetros principales, la clase específica que
 * define los atributos de la pantalla (consulta, resultado, etc.) y un parámetro
 * "cmd" que define el comando a ejecutar, lo que va bifurcando la lógica
 * según corresponda en las funciones doXXXX()
 * 
 * Todo la parte de presentación está en las vistas. 
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
	case "init": 
		try {
			$screenName = $_REQUEST['screen']; // nombre de la clase que tengo que instanciar
		
			eval ('$screen = new ' . $screenName . "();");
			
			$screen->init(); 
			$screen->load();
		
			if (isset($_REQUEST['clearStack'])) {
				$screenManager->clear();
			}
			$screenManager->push($screen);
			
			include 'views/view-main.php';
			
		} catch (Exception $e) {
			include 'views/screen-init-error.php';
		}
		break; 
		
	case "close":
		$screenid = $_REQUEST['screenid'];
		$screenManager->remove($screenid); 
		Utils::redirect($screenManager->getReturnUrl());
		break;

	case "selectTab":
		$screenid = $_REQUEST['screenid'];
		$tab = $_REQUEST['tab'];
		$screen = $screenManager->pop ($screenid);
		$screen->setCurrentTab($tab);
		$screen->getTab()->refrescar(); 
		include 'views/' . $screen->getTab()->getViewToInclude(); 
		break; 
		
	case "return": 
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->refrescar();
		
		include 'views/view-main.php';
		break; 
				
}

$screenManager->serialize(); 

