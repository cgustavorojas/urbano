<?php
/**
 * Action: query.php
 * 
 * Maneja la interacción con un objeto QueryScreen.
 * Tiene toda la lógica para el manejo de una pantalla de consulta de registros, 
 * en forma genérica. Recibe como parámetros principales, la clase específica que
 * define los atributos de la pantalla (consulta, resultado, etc.) y un parámetro
 * "cmd" que define el comando a ejecutar, lo que va bifurcando la lógica
 * según corresponda en las funciones do???()
 * 
 * Todo la parte de presentación está en las vistas. 
 * 
 * @package abm 
 * 
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');


/**
 * Primer punto de entrada. 
 * Crea el objeto QueryScreen que se mantendrá en la SESSION de aquí
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
	
	$screen->buildDinamicSql();
	$screen->countRows(); 
	$screen->setCurrentPage(1);
	
	if (isset($_REQUEST['clearStack'])) {
		$screenManager->clear();
	}
	
	$rs = $screen->executeQuery(true);
	
	if ($screen->getCols()->getCount() == 0)
		$screen->addDefaultColumns($rs);
	
	$screenManager->push($screen);	
}

/**
 * Llamado desde los links de movimiento de la grilla, permite cambiar de página.
 * 
 */
function doMove()
{
	$screenid = $_REQUEST['screenid'];
	
	global $screen; 
	global $screenManager;
	global $rs; 
	 
	$screen = $screenManager->pop($screenid);
		
	$screen->parseRequest(); 
	$screen->setCurrentPage($_REQUEST['pagina']);
	$rs = $screen->executeQuery(true);
	
}


/**
 * Llamado con el botón "Configurar", muestra la pantalla de configuración del query. 
 */
function doCustomize()
{
	$screenid = $_REQUEST['screenid'];
	
	global $screen; 
	
	global $screenManager; 
	$screen = $screenManager->pop($screenid);
		
	$screen->parseRequest(); 
	
}

/**
 * Llamando con el botón Aceptar de la pantalla de customización de la pantalla.
 */
function doCustomizeSave()
{
	$screenid = $_REQUEST['screenid'];
	
	global $screen; 
	global $screenManager;
	 
	$screen = $screenManager->pop($screenid);

	$screen->setTitulo($_POST['titulo']);

	/*
	 * Configuro filtros
	 */
	$filtros = $screen->getFiltros();
	$fCount = $filtros->getCount();
	
	$values = $_POST['fTxt'];
	for ($i = 0 ; $i < $fCount ; $i++) 
	{
		$filtros->get($i)->setTxt($values[$i]);
	}
	
	$values = $_POST['fActive'];
	for ($i = 0 ; $i < $fCount ; $i++) 
	{
		$filtros->get($i)->setActive($values[$i] == 'true');
	}
	
	$values = $_POST['fPrintable'];
	for ($i = 0 ; $i < $fCount ; $i++) 
	{
		$filtros->get($i)->setPrintable($values[$i] == 'true');
	}

	/*
	 * Configuro columnas
	 */
	$cols   = $screen->getCols();
	$cCount = $cols->getCount();
	
	$values = $_POST['cTxt'];
	for ($i = 0 ; $i < $cCount ; $i++) 
	{
		$cols->get($i)->setTxt($values[$i]);
	}

	$values = $_POST['cActive'];
	for ($i = 0 ; $i < $cCount ; $i++) 
	{
		$cols->get($i)->setActive($values[$i] == 'true');
	}
	
	$values = $_POST['cMaxLength'];
	for ($i = 0 ; $i < $cCount ; $i++) 
	{
		if (is_numeric($values[$i]))
			$cols->get($i)->setMaxLength($values[$i]);
	}
	
	$values = $_POST['cPrintWidth'];
	for ($i = 0 ; $i < $cCount ; $i++) 
	{
		if (is_numeric($values[$i]))
			$cols->get($i)->setPrintWidth($values[$i]);
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
			include 'views/query-grid.php';
		} catch (Exception $e) {
			include 'views/screen-init-error.php'; 
		}
		break; 
		
	case "move": 
		$view = doMove(); 
		include 'views/query-grid-ajax.php'; 
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
		$rs = $screen->executeQuery(true);
		include 'views/query-grid.php';
		break; 
				
	case "filtrar":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop ($screenid);
		$screen->parseRequest(); 
		$screen->buildDinamicSql();
		$screen->countRows(); 
		$screen->setCurrentPage(1);
		$rs = $screen->executeQuery(true);
		include 'views/query-grid-ajax.php';
		break; 
		
	case "exportar":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop($screenid);
		$screen->parseRequest(); 		
		include 'views/query-excel.php';	 
		break;

	case "print":
		$screenid = $_REQUEST['screenid'];
		$screen = $screenManager->pop($screenid);
		$report = new QueryReport(); 
		$report->ejecutar($screen); 
		$report->outputToBrowser();
		break; 

	case "customize":
		doCustomize();
		include 'views/query-customize.php';
		break; 
		
	case "customizeSave":
		doCustomizeSave();
		header ('Location: ' . $screenManager->getReturnUrl());
		break; 
}

$screenManager->serialize(); 

