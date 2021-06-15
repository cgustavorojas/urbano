<?php
	ini_set('default_charset','UTF-8');
	mb_internal_encoding('UTF-8');

	if (!isset($directorio_relativo)) {
		$directorio_relativo = '';
	}

	include (dirname(__FILE__) . '/include/autoload.php');
	include (dirname(__FILE__) . '/include/objetos_globales.php');

	// estos 3 con el tiempo deberían desaparecer de acá
	include (dirname(__FILE__) . '/include/constantes_permiso.php');
	include (dirname(__FILE__) . '/include/funciones_basicas.php');
	include (dirname(__FILE__) . '/include/funciones_presupuesto_pyv.php');
	
	session_start();
	
	if (isset($_POST['id_sistema_seleccionado'])) {
		$_SESSION['id_sistema_seleccionado'] = $_POST['id_sistema_seleccionado'];
	}

	// si no está logueado, lo mando a la página de login 
	$usuario = Seguridad::getCurrentUser(); 
	
	if (is_null ($usuario)) {
		Utils::redirect ('gral/login.php');
		exit; 
	}

//------- funciones nuevas -----------------//

function sendHeader($title = '')
{
	$t = Template::getTplDefault();
	$t->setTitle($title);
	$t->sendHeader();
}	

function sendFooter() 
{
	Template::getTplDefault()->sendFooter();
}
	

function getHeaderWithoutMenu($ignored1 = null, $ignored2 = null) {
	Template::getTplSinMenu()->sendHeader(); 
}

function getFooterWithoutMenu() {
	Template::getTplSinMenu()->sendFooter();
}

function getHeader($ignored1 = null, $ignored2 = null, $title = 'SISTEMA DE GESTION') {
	$t = Template::getTplDefault();
	$t->setTitle($title);
	$t->sendHeader();
} 

function getFooter($ignored1=null, $ignored2=null) {
	Template::getTplDefault()->sendFooter();
}
	