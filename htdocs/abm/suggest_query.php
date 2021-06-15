<?php
include ('../includes_gral.php');

$screenManager = ScreenManager::getInstance();

$screen = $screenManager->pop();

$ctrl = $_REQUEST['_ctrl'];
$search = $_REQUEST['query'] ;

try { 
	$input = $screen->getInputs()->get($ctrl);
	$data = $input->filtrar($search);
	echo json_encode($data);
} catch (Exception $e) {

	include 'views/screen-init-error.php';
} 