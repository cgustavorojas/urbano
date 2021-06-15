<?php
/**
 * Llamada en forma asincrónica via AJAX cuando se clickea en un submenú (ya sea para abrirlo o para cerrarlo). 
 * Lo que hace es tomar el objeto menu de la sesión y actualizar el estado de cerrado o abierto del submenú. 
 * De esta forma, cuando se llama a otra página, se mantiene el estado de los submenúes abiertos o cerrados. 
 * 
 * Recibe como parámetro el id_permiso del submenú a abrir/cerrar. 
 */


include ('../include/autoload.php');

session_start();

$menu = $_SESSION['menu'];

$id_sistema = $_SESSION['id_sistema_seleccionado'];
$id_permiso = $_REQUEST['id_permiso'];		// por parámetro me viene el ID del submenú que se abrió o cerró

$abierto = $menu[$id_sistema]['menu'][$id_permiso]['abierto'];	// obtengo el estado actual

$menu[$id_sistema]['menu'][$id_permiso]['abierto'] = ($abierto == 'S') ? 'N' : 'S';	// doy vuelta el estado

$_SESSION['menu'] = $menu;		// vuelvo a meter al menú en la session 


