<?php
/**
 * Ejecuta un  método del objeto de la pantalla actualmente arriba de todo. 
 * @package abm
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');

$screenManager = ScreenManager::getInstance();

$screen = $screenManager->pop();

$metodo = $_REQUEST['_fx'];

try { 
	eval ("\$ret = \$screen->$metodo();");

	switch ($ret)
	{
		case Screen::CERRAR: 
								$screenManager->remove($screen->getId()); 
								$screenManager->serialize();
								Utils::redirect($screenManager->getReturnUrl());
								break; 
								
		case Screen::VOLVER: 
								Utils::redirect($screenManager->getReturnUrl());
								break; 
	
		default:
								// no hago nada porque supongo que la función llamada ya se encargó de todo, 
								// sea haciendo un redirect o mandando una página con contenido
								break;
	}


} catch (Exception $e) {
	include 'views/screen-init-error.php';
} 

