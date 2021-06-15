<?php
/**
 * Muestra información de debug. 
 * 
 * El resultado de phpinfo() lo fuerza a abrir en una ventana nueva porque no es queda bien metido
 * dentro del header y del footer por temas de CSS. 
 * 
 * @package gral
 * @author 
 */

/** */
$directorio_relativo = '../';
include ('../includes_gral.php');

if (isset($_GET['cmd']) && $_GET['cmd'] = 'phpinfo') {
	phpinfo();
} else {
	include 'views/debug-view.php';
} 


