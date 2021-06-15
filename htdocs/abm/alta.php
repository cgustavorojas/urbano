<?php
/**
 * Maneja la interacción con un objeto AltaScreen.
 * Tiene toda la lógica para el manejo de una pantalla de alta de un registro 
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
	
	$screen->parseRequest();
	$screen->validar();
	
	if (! $screen->isValid()) 
		return 'views/alta-view.php';

	$ret = false; 

	try {
		$ret = $screen->guardar(); 
	} catch (Exception $e) {
		$screen->setError($e->getMessage());
	}
			
	if ($ret === true) {  // la grabación fue exitosa
		
		$screen->afterGuardar();
		
		// 
		// Si la pantalla tiene definido un mensaje (atributo msgOk), mando al usuario
		// a la pantalla con el mensaje. Si no, cierro la ventana actual y termino
		//
		if (! is_null($screen->getMsgOk())) 
		{
			return 'views/alta-ok.php';	
		} else {
			$screenManager->remove($screenid); 
			
			//
			// Terminé con esta pantalla y ya la saqué del medio con remove($screenid), ahora
			// tengo que ir a algún lado. Si esta pantalla tenía definida alguna url a la cual 
			// avanzar, hacia allá voy. Si no, voy a la url de la ventana que estaba abajo. 
			//
			$url = $screen->getForwardUrl();
			if (is_null($url))
				$url = $screenManager->getReturnUrl();
			
			Utils::redirect ($url);
		}
	} else if ($ret === Screen::HECHO) {
			// el código de retorno especial Screen::HECHO indica que la función guardar() ya hizo internamente todo lo que tenía que hacer, 
			// envió al browser lo que había que enviar, dejó en memoria lo que había que dejar, etc. 
	} else {
		return 'views/alta-view.php';
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
			include 'views/alta-view.php';
		} catch (Exception $e) {
			include 'views/screen-init-error.php';
		} 
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
		include 'views/alta-view.php';
		break; 

	case "parse":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->parseRequest();
		//$screen->validar(); 
		break; 
		
	case "save":
		$view = doSave();
		
		if ($view)
			include $view; 
		break;
		
	case "eventHook":
		$screenid = $_REQUEST['screenid'];
		$method = $_REQUEST['method'];
		$screen = $screenManager->pop ($screenid);
		$screen->parseRequest(); 
		eval ('$screen->' . $method . '();');
		include 'views/alta-view.php';
}

$screenManager->serialize(); 


