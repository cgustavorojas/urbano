<?php
/**
 * Pantalla principal (home) cuando se ingresa al módulo general. 
 * 
 * Llamado cuando se hace click en la lengüeta del sistema General 
 * sin haber seleccionado ninguna opción del menú en particular. 
 * 
 * @package gral
 */

/** */
$directorio_relativo = '../';
include ('../includes_gral.php');
date_default_timezone_set("America/Argentina/Buenos_Aires");
if (! isset ($_SESSION['ej'])) { 
	$_SESSION['ej'] = date('Y');
}

//------------- Traigo el nombre del módulo para poner en el título -----------// 

if (isset ($_REQUEST['id_sistema'])) {
	$id_sistema = $_REQUEST['id_sistema'];
	$title = 'M&oacute;dulo de ' . Database::simpleQuery('SELECT descripcion FROM gral_sistema WHERE id_sistema = ?', $id_sistema);
} else {
	$title = Config::getInstance()->get('gral.screen.titulo');;
}

//------------- Traigo la lista de perfiles que tiene asignados -----------// 

$rs = Database::query ('SELECT descripcion FROM gral_usuario_perfil INNER JOIN gral_perfil USING (id_perfil) WHERE id_usuario = ?',
		Seguridad::getCurrentUserId());
		
$perfiles = array(); 
while ($row = $rs->getRow()) {
	$perfiles[] = $row['descripcion'];
}		
if (count($perfiles) == 0) {
	$perfiles[] = 'No tiene perfiles asignados';
}

//------- Todo el código HTML queda en la vista home-ok.php -----------//

include ('views/home-ok.php');
