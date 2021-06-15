<?php
if (!isset($directorio_relativo)) {
	$directorio_relativo = '';
}
include_once (dirname(__FILE__) . '/include/autoload.php');
include_once (dirname(__FILE__) . '/include/objetos_globales.php');
session_start();
	
if (isset($_POST['id_sistema_seleccionado'])) {
	$_SESSION['id_sistema_seleccionado'] = $_POST['id_sistema_seleccionado'];
}

	// si no est� logueado, lo mando a la p�gina de login 
	$usuario = Seguridad::getCurrentUser(); 
	
	
	if (is_null ($usuario)) {
		Utils::redirect ('gral/login.php');
		exit; 
	}

//------- funciones nuevas -----------------//

function sendHeader($title)
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

function getHeader($ignored1 = null, $ignored2 = null, $title = 'Urbano') {
	$t = Template::getTplDefault();
	$t->setTitle($title);
	$t->sendHeader();
} 

function getFooter($ignored1=null, $ignored2=null) {
	Template::getTplDefault()->sendFooter();
}
	