<?php
/**
 * Dada la siguiente secuencia: 
 * 
 *  1. Hay una ventana de ingreso de datos (tipo AltaScreen o EditScreen)
 *  2. El usuario hace click en el botón "seleccionar de una lista" de un INPUT complejo
 *  3. Se muestra una ventana nueva con la lista de valores posibles a elegir
 *  4. El usuario seleccionar un registro
 * 
 * Entonces, este programa es llamado, pasando el nombre del campo y el valor que se 
 * seleccionó. Hace 3 cosas: 
 * 
 * 1. Cierra la ventana de consulta (la que está más arriba)
 * 2. Vuelve a la superficie a la ventana que estaba abajo (el AltaScreen o EditScreen)
 * 3. Setea en la ventana de edición, el valor para el INPUT seleccionado
 * 
 * @package abm
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');


$screenManager = ScreenManager::getInstance();

//
// Cierro la ventana de selección de registros
//
$scr = $screenManager->pop();
$screenManager->remove ($scr->getId());

//
// Traigo la ventana con los INPUTs
//
$scr = $screenManager->pop();
$url = $scr->getReturnUrl();

//
// Modifico el valor de INPUT en cuestión
//
$inputId = $_REQUEST['inputId'];
$inputValue = $_REQUEST['inputValue'];
$input = $scr->getInputs()->get($inputId); 
$input->setValue($inputValue); 
$input->validar();		// si estaba en situación de error, por ej., porque era obligatorio y no tenía valor, reseteo situación de error

//
// Vuelvo a poner la pantalla visible
//
Utils::redirect($url);

$screenManager->serialize(); 


