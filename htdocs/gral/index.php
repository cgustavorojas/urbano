<?php
/**
 * @package common
 * 
 * Es la página índice que presenta el browser cuando se no se pone un archivo en particular en la barra de direcciones. 
 * Se asegura que el usuario esté logueado (de eso se encarga includes_gral.php) y luego redirecciona al usuario
 * a la página home del primer módulo que tenga habilitado (normalmente, el módulo General). 
 * 
 */
	include('includes_gral.php');
	
	$menu = $_SESSION['menu'];
	
	Utils::redirect($menu[key($menu)]['link']);
	
