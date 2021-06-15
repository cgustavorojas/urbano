<?php
/**
 * 
 */

global $directorio_relativo; 
?>
<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"> 
	
	
	<title>Urbano</title>
	<link rel="shortcut icon" href="../imagenes/favicon2.ico" type="image/x-icon"/>

	<?php foreach ($t->getCss() as $css ) : ?>
		<link href="<?php echo Utils::makeAbsoluteUrl($css); ?>" rel="stylesheet" type="text/css">
	<?php endforeach; ?>
	 
	<?php foreach ($t->getJs() as $js ) : ?>
		<script src="<?php echo Utils::makeAbsoluteUrl($js); ?>" type="text/javascript" ></script>
	<?php endforeach; ?>
	
	<?php   include (dirname(__FILE__) . '/../include/calendario/calendar.php');  ?>

	  
</head>

<body>