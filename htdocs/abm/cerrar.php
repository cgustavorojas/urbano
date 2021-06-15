<?php
/**
 * Cierra la pantalla que está arriba de todo y envia al browser un redirect
 * a una dirección específica que recibió en el parámetro url
 * 
 * @package abm 
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');

$screenManager = ScreenManager::getInstance();

$screenManager->remove($screenManager->pop()->getId()); 
$screenManager->serialize();

$relative = isset($_REQUEST['relative']) && $_REQUEST['relative'] == 'true';

Utils::redirect($_REQUEST['url'], $relative);

