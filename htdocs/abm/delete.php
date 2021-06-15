<?php
/**
 * Maneja la interacción con un objeto DeleteScreen.
 * Tiene toda la lógica para el manejo de una pantalla de eliminación de un registro 
 * en forma genérica. Recibe como parámetros principales, la clase específica que
 * define los atributos de la pantalla y un parámetro
 * "cmd" que define el comando a ejecutar, lo que va bifurcando la lógica
 * según corresponda en las funciones doXXXX()
 * 
 * Todo la parte de presentación está en las vistas. 
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
 * Si la pantalla no tiene ningún mensaje de confirmación, directamente llama a doSave(); 
 * 
 * @return string La vista a incluir 
 */
function doInit()
{
	global $screen;
	global $screenManager;  
	
	$screenName = $_REQUEST['screen']; // nombre de la clase que tengo que instanciar
	
	eval ('$screen = new ' . $screenName . "();");
	
	$screen->init(); 
	$screen->load();
	$screen->refrescar();
	
	if (isset($_REQUEST['clearStack'])) {
		$screenManager->clear();
	}
	
	$screenManager->push ($screen);	
	
	if ($screen->validar())
		return "views/delete-view.php";
	else
		return "views/delete-error.php";
}


/**
 * LLamado cuando se presiona el botón de Aceptar. 
 * Actualiza el estado de los inputs, hace validaciones y trata de guardar. 
 *
 * @return string La vista a incluir o NULL si no hay que incluir nada (se devolvió un redirect via header())
 */
function doSave()
{
	global $screen; 
	global $screenManager; 
	
	$screenid = $_REQUEST['screenid'];
	$screen = $screenManager->pop($screenid);
	
	if ($screen->guardar()) {  
		$screenManager->remove($screenid); 
		
		$url = $screen->getForwardUrl();
		if (is_null($url))
			$url = $screenManager->getReturnUrl();
			
		Utils::redirect($url);

	} else { 
		return 'views/delete-error.php';
	}
	
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
		$view = doInit();
		include $view;
		break;

	case "close":
		$screenid = $_REQUEST['screenid'];
		$screenManager->remove($screenid); 
		Utils::redirect($screenManager->getReturnUrl());
		break;

	case "return":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->refrescar();
		include 'views/delete-view.php';
		break; 
		
	case "save":
		$view = doSave();
		
		if ($view)
			include $view; 
		break;
}

$screenManager->serialize(); 


