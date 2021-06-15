<?php
/**
 * Maneja la interacción con un objeto OptionScreen.
 * Tiene toda la lógica para el manejo de una pantalla que muestra un como de opciones al usuario 
 * en forma genérica. Recibe como parámetros principales, la clase específica que
 * define los atributos de la pantalla.
 * 
 * @package abm
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');

/**
 * Primer punto de entrada. 
 * Crea el objeto Screen que se mantendrá en la SESSION de aquí
 * en adelante mientras siga vigente esta pantalla.
 * 
 */
function doInit()
{
	global $screen;
	global $screenManager;  
	
	$screenName = $_REQUEST['screen']; // nombre de la clase que tengo que instanciar
	
	eval ('$screen = new ' . $screenName . "();");
	
	$screen->init(); 
	
	if (isset($_REQUEST['clearStack'])) {
		$screenManager->clear();
	}
	
	$screenManager->push ($screen);
}


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
			doInit();
			include 'views/option-view.php';
		} catch (Exception $e) {
			include 'views/screen-init-error.php';
		} 
		break;

	case "close":
		$screenid = $_REQUEST['screenid'];
		$screenManager->remove($screenid);
		 
		Utils::redirect($screenManager->getReturnUrl());
		break;

//	case "return":
//		$screenid = $_REQUEST['screenid'];
//		$screen = $screenManager->pop ($screenid);
//		$screen->refrescar();
//		include 'views/option-view.php';
//		break; 

		
	case "select":
		$screenid = $_REQUEST['screenid'];
		$option = $_REQUEST['option'];
		$screen = $screenManager->pop ($screenid);
		$screenManager->remove($screenid);

		Utils::redirect ($screen->getUrl($option));
		break;
}

$screenManager->serialize(); 


