<?php 
/**
 * 
 */

global $directorio_relativo; 
global $menu_gral; 
?>
 
<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">

    <!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	
	<title>Urbano</title>
	
	 
	
	<?php foreach ($t->getCss() as $css ) : ?>
		<link href="<?php echo ($css); ?>" rel="stylesheet" type="text/css">
	<?php endforeach; ?>
	
	<?php foreach ($t->getJs() as $js ) : ?>
		<script src="<?php echo ($js); ?>" type="text/javascript" ></script>
	<?php endforeach; ?>

	<?php   include (dirname(__FILE__) . '/../include/calendario/calendar.php');  ?>
	  
</head>

<body>

<!-- ex getNavegacionSolapas() -->
		<form name='navegacionSolapas' id='navegacionSolapas' method='post' action = ''>
			<input type='hidden' name='id_sistema_seleccionado' id='id_sistema_seleccionado' />
		</form>
<!-- fin getNavegacionSolapas() -->		

<script type="text/javascript">
function navegarSolapa(id_sistema, url)
{
	$('id_sistema_seleccionado').value = id_sistema; 
	$('navegacionSolapas').action = url;
	$('navegacionSolapas').submit();  
}
/*
 * Abre y cierra el menú y ademas hace una llamada ajax asincrónica al servidor
 * para avisarle que el menú se abrió o cerró así­ puede mantener el estado del mismo
 * para las próximas páginas. 
 */
function toggleMenu(id_permiso)
{
	$(id_permiso).toggle(); 
	$('p_' + id_permiso).toggleClassName('btn_submenu_abierto');

	var ajaxRequest = new Ajax.Request(
            '/template/ajax-submenu.php',
            {
                    method: 'post',
                    parameters: 'id_permiso=' + id_permiso,
                    asynchronous: true
            });
	 
	return false;
}

</script>

<table width="98%"  style="border:2px solid #99B3CA" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td>
    </td>
  </tr>
      <tr>
  <td>
    <table width="100%" height="45" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
<tr>
    
    <td><!--<p class="tit_sist"><?php echo Config::getInstance()->get('gral.screen.titulo');?></p>-->
    
    </td>
    <td align="left" valign="bottom">
        <table width="250" border="0" cellpadding="4" cellspacing="4">
          <tr>
            <td nowrap><?php 
            
            $nombre = Seguridad::getCurrentUser()->getTxt();
            
			if (trim($nombre)!='') {

	            echo "<p><img src='/imagenes/icono_usuario.gif'/>&nbsp;$nombre&nbsp&nbsp;<a href='/gral/logout.php' class='salida'>[ Salir ]</a></p>";
			
			}

            ?></td>
          </tr>
        </table>
    </td>
  </tr>
</table>
</td>
  </tr>
	
  <tr>
    <td>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#3A549F">
      <tr>
        <td height="2" colspan="2" bgcolor="#EEEEEE"></td>
      </tr>
      <tr>
        <td align="left" valign="bottom" height="27">
			<DIV ID='solapa'>
				<table cellspacing='0'>
					<tr>
						<?php foreach ($_SESSION['menu'] as $id_sistema => $sistema) : ?>
							<td width='9px' height='25px' valign='top' background='/imagenes/btn_solapas_parte1_off.jpg'>&nbsp;</td>
							<td background='/imagenes/btn_solapas_parte2_off.jpg' nowrap>
								<a href="#" onclick="navegarSolapa('<?php echo $id_sistema; ?>', '/<?php echo $sistema['link']; ?>'); return false;"><?php echo $sistema['descripcion']; ?></a></td>
							<td width='11px' height='25px' valign='top' background='/imagenes/btn_solapas_parte3_off.jpg'>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
			        	<?php endforeach; ?>
        			</tr>
        		</table>
			</DIV>						                        
        </td>
        <td>&nbsp;</td>
      </tr>
     <tr>
        <td height="2" colspan="2" bgcolor="#EEEEEE"></td>
      </tr>
    </table>
    </td>
  </tr>
 
  <tr>
    <td>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top" height="27" width="235" bgcolor="#DCDCDC" style="border-right: 1px solid #FFFFFF">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="20" align="center" class="tit_menu"><?php echo $_SESSION['menu'][$_SESSION['id_sistema_seleccionado']]['descripcion']?></td>
      </tr>
      <tr>
        <td>
		 <!-- =========== COMIENZA MENU ================== -->
		 <?php 
		 
		 	foreach ($_SESSION['menu'][$_SESSION['id_sistema_seleccionado']]['menu'] as $id_permiso => $permiso)
		 	{
	 			$link 		= $permiso['link']; 	
	 			$desc  		= $permiso['descripcion'];
	 			$id_permiso = $permiso['id_permiso'];
	 			
		 		if ($permiso['tipo'] == 'M')
		 		{
		 		
					 echo ("<tr>");
					 echo ("   <td class='cell_menu' onmouseover=\"this.className='cell_menu_over';\" onmouseout=\"this.className='cell_menu';\">");
					 echo ("	<p class='btn_menu'><a href='/$link'>$desc</a></p>");
					 echo ("	</td>");
					 echo ("</tr>");
					 
				} else {
					
					 if ($permiso['abierto'] == 'S') {
					 	$classAbierto = "btn_submenu_abierto";
					 	$style = "";
					 } else {
					 	$classAbierto = "";
					 	$style = "display: none"; 
					 }
					
					 echo ("<tr>");
					 echo ("	<td class='cell_menu'>");
					 echo ("	<p id='p_$id_permiso' class='btn_menu btn_submenu $classAbierto'><a href='#' onclick=\"toggleMenu('$id_permiso');\">$desc</a></p>");
					 echo ("	</td>");
					 echo ("</tr>");
					 
					 echo ("<tbody class='submenu' style='$style' id='$id_permiso'>");
					 
					 
					 
					 foreach ($permiso['submenu'] as $id_subpermiso => $subpermiso) 
					 {
					 	 $sublink = $subpermiso['link'];
					 	 $subdesc = $subpermiso['descripcion'];
					 	 
						 echo ("<tr>");
						 echo ("	<td class='cell_menu' onmouseover=\"this.className='cell_menu_over';\" onmouseout=\"this.className='cell_menu';\">");
						 echo ("		<p class='btn_menu'><a href='/$sublink'>$subdesc</a></p>");
						 echo ("	</td>");
						 echo ("</tr>");
					 }
					 echo ("</tbody>");
					 
				}
		
		 	}
		 
		 ?>
		 <!-- =========== FIN MENU ================== -->
		 
		</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>

    </table>
    </td>
    <td align="left" valign="top" bgcolor="#FFFFFF" style="BORDER-LEFT:1PX SOLID #A3B9D1;padding-left: 10px;padding-right: 10px;height:500px;">

