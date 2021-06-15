<?php
/**
 * @package common
 * @author 
 */

class Seguridad
{
	/**
	 * Al tener un constructor privado, evito que se creen objetos de esta clase. 
	 * @return Seguridad
	 */
	private function __construct() {	}
	
	/**
	 * Devuelve el ID del usuario actualmente logueado.
	 * @return int ID del usuario logueado
	 */
	public static function getCurrentUserId()
	{
		if (! isset($_SESSION['usuario']))
			return null; 
			
		return $_SESSION['usuario']->getId();
	}
	
	/**
	 * Devuelve un objeto Usuario que representa al usuario actualmente logueado
	 * (o null is no hay ninguno). 
	 * 
	 * @return Usuario
	 */
	public static function getCurrentUser()
	{
		if (! isset($_SESSION['usuario']))
			return null; 
		
		return $_SESSION['usuario'];
	}
	
	/**
	 * Verifica si el usuario logueado tiene habilitado el permiso en cuestión. 
	 * 
	 * @param string $constante El permiso a consultar (su constante, no su ID numérico)
	 * @return bool TRUE si el usuario está habilitado o FALSE si no. 
	 */
	public static function tieneHabilitado($permiso)
	{
		$usr = Seguridad::getCurrentUser();
		return $usr->tieneHabilitado($permiso);
	}

	public static function validarUsuario ($usuario, $passwd)
	{
		$usr = Database::query('SELECT id_usuario, estado, password FROM gral_usuario WHERE usuario = ?', $usuario)->getRow();
			
		if ($usr) {	// el usuario existía
			
			if ($usr->estado <> 'A') {
				Utils::log('gral_login.notactive', 'gral_usuario', $usr['id_usuario']);
				return null;		// el usuario existe, pero está bloqueado o eliminado (no está A=Activo)
			}

			$id_usuario = $usr['id_usuario'];
	
			
					// chequeo la password local en la base de datos
				$ok = $usr['password'] == md5($passwd);
				if (! $ok) { 
					Utils::log ('gral_login.db.badpasswd', 'gral_usuario', $id_usuario, "usuario=$usuario");
				}	
				return $ok ? $id_usuario : null; 
				
				
				
			//}
			
		} else {	// el usuario no existía.

					
					// doy de alta el usuario
							
					// no fue pedido
					
					
					return null;
		}
						
	}
	
	public static function doLogin($id_usuario)
	{
	
		$usuario = Usuario::crearUsuario($id_usuario);
		$_SESSION['usuario'] = $usuario; 
		
		$menu = Seguridad::buildMenu();
		$firstKey = key($menu);			
		
		$_SESSION['menu'] = $menu;
		$_SESSION['id_sistema_seleccionado'] = $firstKey;
		
		
		return $menu[$firstKey]['link'];
	}
	
	
	/**
	 * Verifica si hay que forzar al usuario a que cambie su contraseña. 
	 * 
	 * @param int $id_usuario ID del usuario (campo gral_usuario.id_usuario)
	 * @return bool
	 */
	public static function expiroPassword($id_usuario)
	{
		return (Database::simpleQuery('SELECT forzar_cambio FROM gral_usuario WHERE id_usuario = ?', $id_usuario) == 't');
	} 
	

	/**
	 * Arma un gran array con toda la estructura de sistemas y menúes. 
	 * 
	 * ['GRAL']['descripcion']  = 'General';
	 * ['GRAL']['link'] = 'gral/home.php'; 
	 * ['GRAL']['menu']['GRAL_CHGE_PASSWD']['descripcion'] = 'Cambiar contraseña';
	 * ['GRAL']['menu']['GRAL_CHGE_PASSWD']['tipo'] = 'M';
	 * ['GRAL']['menu']['GRAL_CHGE_PASSWD']['link'] = 'abm/alta.php?screen=GralChgePassword';
	 * ['GRAL']['menu']['GRAL_SUBMENU1']['descripcion'] = 'Un submenú';
	 * ['GRAL']['menu']['GRAL_SUBMENU1']['tipo'] = 'S';
	 * ['GRAL']['menu']['GRAL_SUBMENU1']['abierto'] = 'S';  (ó 'N')
	 * ['GRAL']['menu']['GRAL_SUBMENU1']['submenu']['descripcion'] = 'Item 1 del submenú';
	 * ['GRAL']['menu']['GRAL_SUBMENU1']['submenu']['link'] = 'abm/alta.php?=screen=Item1Submenu';     
	 * 
	 * @return array
	 */
	public static function buildMenu()
	{
		$id_usuario = Seguridad::getCurrentUserId();
		//var_dump($id_usuario);die;
		$sistemas = Database::query ('SELECT id_sistema, descripcion, link,activo FROM gral_sistema WHERE activo ORDER BY orden_menu');
		$lenguetas = array(); 
		
		while ($s = $sistemas->getRow())
		{
			$id_sistema = $s['id_sistema'];
			
			$menu = array(); 
			$permisos = Database::query ('SELECT id_permiso, tipo, descripcion, link FROM gral_v_permiso 
												WHERE tipo in (\'M\',\'S\') and id_usuario = ? and id_sistema = ? and padre is null ORDER BY orden_menu',
											array($id_usuario, $id_sistema));
											
			while ($p = $permisos->getRow())
			{
				
				if ($p['tipo'] == 'M') {			// es una opción de menú 
					
					
					$aux = array();
					$aux['id_permiso'] = $p['id_permiso'] ;
					$aux['tipo'] = $p['tipo'] ;
					$aux['descripcion'] = $p['descripcion'] ;
					$aux['link'] = $p['link'];
					
					$menu[$p['id_permiso']] = $aux;
					unset($aux);
					
					
				} else {							// es un submenú
					
					$hijos = Database::query ('SELECT id_permiso, tipo, descripcion, link FROM gral_v_permiso 
												WHERE tipo = \'M\' and id_usuario = ? and id_sistema = ? and padre = ? ORDER BY orden_menu',
											array($id_usuario, $id_sistema, $p['id_permiso']));
					
					$submenu = array(); 

					while ($h = $hijos->getRow()) 
					{
						
						$aux_hijo = array();
						$aux_hijo['id_permiso'] = $h['id_permiso'] ;
						$aux_hijo['tipo'] = $h['tipo'] ;
						$aux_hijo['descripcion'] = $h['descripcion'] ;
						$aux_hijo['link'] = $h['link'];
						
						$submenu[$h['id_permiso']] = $aux_hijo;
						unset($aux_hijo);
					}
					
					$aux = array();
					$aux['id_permiso'] = $p['id_permiso'] ;
					$aux['tipo'] = $p['tipo'] ;
					$aux['descripcion'] = $p['descripcion'] ;
					$aux['link'] = $p['link'];
					
					$aux['abierto'] = 'N';
					$aux['submenu'] = $submenu; 
				
					
					
					$menu[$p['id_permiso']] = $aux;
					unset($aux);					
				} 	
			}
			
			if (count($menu) > 0) { 
				$lenguetas[$id_sistema] = array ( 'descripcion'  => $s['descripcion'],
												   'link' => $s['link'],
												   'menu' => $menu,
												 'activo' => $s['activo']);
			}
					
		}
		
		return $lenguetas;
	}
	
	
	
}