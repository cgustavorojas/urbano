<?php
include ('../includes_gral.php');

$screenManager = ScreenManager::getInstance();

$screen = $screenManager->pop();

$ctrl = $_REQUEST['_ctrl'];

try { 
	$input = $screen->getInputs()->get($ctrl);
	$data = $input->getValueAjax($_REQUEST);
	echo $data;
} catch (Exception $e) 
{
	include 'views/screen-init-error.php';
} 