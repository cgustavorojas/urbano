<?php
/**
 * Desloguea al usuario actualmente logueado. 
 * Responde al link "Desconectarse" o "Salir del sistema". 
 * Elimina la session actual. 
 * 
 * @package default
 */

include('../includes_gral.php');

Utils::log ('gral_logout');

session_destroy();

header("location: " . Utils::makeAbsoluteUrl('index.php'));
