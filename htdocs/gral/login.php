<?php
/**
 * Nueva pantalla de login. Es independiente de las otras pantallas del sistema. 
 * No hace uso de los templates getHeader() y getFooter() y no llama a includes_general.php
 * 
 * 
 * @package gral
 */

include ('../include/autoload.php');

ini_set('default_charset','UTF-8');

session_start(); 



$cmd = 'input'; 

if (isset($_REQUEST['cmd'])) {
	$cmd = $_REQUEST['cmd'];
}




switch ($cmd)
{
	case 'input':   include 'views/login-input.php';
					break; 
					
	case 'login':	$usuario = strtolower($_POST['usuario']);
					$passwd  = $_POST['passwd'];	
		
					$id = Seguridad::validarUsuario($usuario, $passwd);
					
					if (is_null($id)) {
						$error = 'Usuario o contraseña incompletos o incorrectos';
						include 'views/login-input.php';

					} else {
							if (Seguridad::expiroPassword($id)) {
									// debe cambiar su contraseña
									
								$_SESSION['login_id_chgepasswd'] = $id;
								$_SESSION['login_old_passwd'] = $passwd; 
								include 'views/login-chge.php';
								
							} else {
						
								$home = Seguridad::doLogin($id);
								Utils::redirect($home);
							}
					}
					break;
					
	case 'chge':	
					$password1 = $_REQUEST['password1'];
					$password2 = $_REQUEST['password2'];
					$id = $_SESSION['login_id_chgepasswd'];
					
					if (is_null($id)) {
						Utils::redirect ('/login.php');
					}
					
					$minlength = Config::getInstance()->get('gral_password.minlength');
					$maxlength = Config::getInstance()->get('gral_password.maxlength');
					
					if ($password1 != $password2) {
						$error = 'La contraseña no coincide con su repetición'; 
						include 'views/login-chge.php';
						return; 
					} 
					
					if (strlen($password1) < $minlength || strlen($password1) > $maxlength) { 
						$error = "La contraseña debe tener entre $minlength y $maxlength caracteres";  
						include 'views/login-chge.php';
						return; 
					}
					
					if ($password1 == $_SESSION['login_old_passwd']) { 
						$error = "La contraseña no puede ser igual a la anterior";  
						include 'views/login-chge.php';
						return; 
					}
					
					unset($_SESSION['login_id_chgepasswd']);
					unset($_SESSION['login_old_passwd']);
					
					Seguridad::chgePassword($id, $password1);
					$home = Seguridad::doLogin($id);
					Utils::redirect($home);
					
					break; 					
}

