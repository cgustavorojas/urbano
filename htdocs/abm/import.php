<?php
/**
 * Maneja la interacción con un objeto ImportsScreen.
 * Recibe como parámetros principales, la clase específica que
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
 * 
 */
function doInit()
{
	global $screen;
	global $screenManager;  
	
	$screenName = $_REQUEST['screen']; // nombre de la clase que tengo que instanciar
	
	eval ('$screen = new ' . $screenName . "();");
	
	$screen->init(); 
	$screen->refrescar();
	
	if (isset($_REQUEST['clearStack'])) {
		$screenManager->clear();
	}
	
	$screenManager->push ($screen);
}


/**
 * LLamado cuando se presiona el botón de Aceptar. 
 *
 * @return string La vista a incluir o NULL si no hay que incluir nada (se devolvió un redirect via header())
 */
function doSave()
{
	global $screen; 
	global $screenManager; 
	
	$screenid = $_REQUEST['screenid'];
	$screen = $screenManager->pop($screenid);
	
	$screen->parseRequest();
	$screen->validar();
	
	if (! $screen->isValid()) 
		return 'views/import-view.php';
			
	$ok = false; 
	try {

		$screen->recibirArchivo();
		
	} catch (Exception $e) {
		$screen->setError($e->getMessage());		// si tuve un error recibiendo el archivo
		return 'views/import-view.php';				// vuelvo a la pantalla donde pido el archivo y muestro el error
	}

	try {
		
		$screen->beforeProcesar(); 

		try {
			$screen->procesar();
			$screen->afterProcesar();
			
			
		} catch (Exception $e) {
			$screen->finalizar();		// me aseguro que el finalizar() se llame siempre,
										// aunque haya habido algún error.
			throw $e;
		} 
		
		$screen->finalizar();
		$screen->registrar();
		
		return 'views/import-ok.php';
			
	} catch (Exception $e) {
		$screen->setError($e->getMessage());
		$screen->registrar(); 
		return 'views/import-error.php';
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
		try { 
			doInit();
			include 'views/import-view.php';
		} catch (Exception $e) {
			include 'views/screen-init-error.php';
		} 
		break;

	case "close":
		$screenid = $_REQUEST['screenid'];
		$screenManager->remove($screenid);
		$screenManager->get(0)->refrescar();
		 
		Utils::redirect($screenManager->getReturnUrl());
		break;

	case "parse":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->parseRequest();
		break; 
				
	case "return":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->refrescar();
		include 'views/import-view.php';
		break; 
	
	case "save":
		$view = doSave();
		
		if ($view)
			include $view; 
		break;
		
}

$screenManager->serialize(); 


