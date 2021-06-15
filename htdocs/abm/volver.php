<?php
/**
 * Manda un redirect al browser que lo lleva nuevamente a la última
 * pantalla disponible (la de más arriba). 
 * 
 * @package abm
 */

/** */
$directorio_relativo = '../';

include ('../includes_gral.php');


$screenManager = ScreenManager::getInstance();

Utils::redirect($screenManager->getReturnUrl());




