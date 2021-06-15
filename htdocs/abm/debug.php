<?php

/**
 * Muestra alguna información de debug útil a la hora de reportar un problema a desarrollo
 */

require '../includes_gral.php';

$sm = ScreenManager::getInstance();

echo "<html><head></head><body><pre>";

$sm->printStack();

echo "</pre></body></html>";

