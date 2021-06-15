<?php
/**
 * Action: mselect.php
 * 
 * Maneja la interacción con un objeto MultiSelectScreen.
 *  
 * Recibe como parámetros principales, la clase específica que
 * define los atributos de la pantalla y un parámetro
 * "cmd" que define el comando a ejecutar, lo que va bifurcando la lógica
 * según corresponda en las funciones doXXXX().
 * 
 * 
 * Todo la parte de presentación está en las vistas. 
 *
 * @package abm
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');


if (isset ($_REQUEST['cmd'])) {
	$cmd = $_REQUEST['cmd'];
} else {
	$cmd = 'init';
}

/**
 * Primer punto de entrada. 
 * Crea el objeto MultiSelectScreen que se mantendrá en la SESSION de aquí
 * en adelante mientras siga vigente esta pantalla.
 * 
 */
function doInit()
{
	global $screen; 
	global $screenManager; 
	global $rs;
	
	$screenName = $_REQUEST['screen']; // nombre de la clase que tengo que instanciar

	eval ('$screen = new ' . $screenName . "();");
	
	$screen->init(); 
	$screen->refrescar(); 
	
	if (isset($_REQUEST['clearStack'])) {
		$screenManager->clear();
	}
	
	$screen->buildDinamicSql();
	$rs = $screen->executeQuery();
	
	$screenManager->push($screen);	
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
	global $rs;	
	
	$screenid = $_REQUEST['screenid'];
	$screen = $screenManager->pop($screenid);
	
	$screen->parseRequest();
	$screen->validar();
	
	if (! $screen->isValid()) 
		return 'views/mselect-view.php';

	$ok = false; 
	
	try {
		$ok = $screen->guardar(); 
	} catch (Exception $e) {
		$screen->setError ($e->getMessage());
	}
		
	if ($ok) {  
		$screen->afterGuardar();
		$screenManager->remove($screenid); 
		//Utils::redirect($screenManager->getReturnUrl());
		$url = $screen->getForwardUrl();
		if (is_null($url))
			$url = $screenManager->getReturnUrl();
			
		Utils::redirect ($url);		
	} else {
		$rs = $screen->executeQuery(true);
		
		return 'views/mselect-view.php';
	}
	
}


$screenManager = ScreenManager::getInstance();

switch ($cmd)
{
	case "init": 
		try {
			doinit();
			include 'views/mselect-view.php';
		} catch (Exception $e) {
			include 'views/screen-init-error.php';
		} 
		break;

	case "filtrar":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->parseRequest(); 
		$screen->buildDinamicSql();
//		$screen->countRows(); 
//		$screen->setCurrentPage(1);
		$rs = $screen->executeQuery();
		include 'views/mselect-view.php';
		break; 
				
	case "close":
		$screenid = $_REQUEST['screenid'];
		$screenManager->remove($screenid);
		Utils::redirect ($screenManager->getReturnUrl()); 
		break;

	case "return":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->refrescar();
		include 'views/mselect-view.php';
		break; 
		
	case "save":
		$view = doSave();
		
		if ($view)
			include $view; 
		break;
		
}

$screenManager->serialize(); 


