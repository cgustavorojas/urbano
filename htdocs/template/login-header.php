<?php
/**
 * Parte del template de nombre "login", que se usa para las pantallas iniciales de login, cambio de contraseña, etc.
 *  
 * @see classes/TemplateLogin */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <meta charset="utf-8">
 
     <link href="../css/login.css" rel="stylesheet" id="bootstrap-css">
	  <link rel="stylesheet" href="../lib/bootstrap-4.3.1/css/bootstrap.min.css">
      <script src="../lib/bootstrap-4.3.1/js/jquery-3.4.1.min.js"></script>
      <script src="../lib/bootstrap-4.3.1/js/bootstrap.min.js"></script>

<head>
		<title><?php echo Config::getInstance()->get('gral.screen.titulo');?></title>
		
		      <style>
            @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');
</style> 	
		

</head>

<body>

	<div id="centrado3" class="container">
		

			<div id="banner" class="card card-container cuadro">
		    			<div class="tit">
				<?php echo Config::getInstance()->get('gral.screen.loginmsg'); ?>
		</div>

             	<p id="profile-name" class="profile-name-card"></p>
			

		
		
		
		
		

