<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>TITLE</title>
<style>
#banner {
	height: 70px;
	background-image: url('/urbano/gral/imagenes/banner_back.png');
	background-repeat: repeat-x;
	overflow: hidden;
}
.banner-sprite {
	background-image: url('/urbano/gral/imagenes/banner.png');
	height: 70px;
	overflow: hidden;
}
#banner-left  { width: 209px; }
#banner-right { width: 185px; float: right; background-position: -209px; }
#barra {
	padding: 5px 0px;
	border-top: 1px solid #888888;
}
#barra-right {
	float: right; 
	margin-right: 2em;
}
#barra-tit {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 12pt;
	color: #333333;
	font-weight: bold;
	margin-left: 15px;
}

</style>


        <link rel="stylesheet" type="text/css" href="../lib/yui/build/reset/reset.css">
        <link rel="stylesheet" type="text/css" href="../lib/yui/build/fonts/fonts.css">
        <link rel="stylesheet" type="text/css" href="../lib/yui/build/container/assets/skins/sam/container.css">
        <link rel="stylesheet" type="text/css" href="../lib/yui/build/menu/assets/skins/sam/menu.css"> 

        <script type="text/javascript" src="../lib/yui/build/utilities/utilities.js"></script>
        <script type="text/javascript" src="../lib/yui/build/container/container.js"></script>
        <script type="text/javascript" src="../lib/yui/build/menu/menu.js"></script>

</head>
<body>
<div id="banner">
	<div class="banner-sprite" id="banner-right"></div>
	<div class="banner-sprite" id="banner-left"></div>
</div>

<div id="barra">
	<div id="barra-right">
		<b class="">Apellido, Nombre</b>
	</div>
	<b id="barra-tit">SISTEMA DE GESTIÓN</b>
</div>
<div id="menu" class="yui-skin-sam"></div>
