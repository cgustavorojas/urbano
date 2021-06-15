<?php
/**
 * @package common
 * @author 
 */

/**
 * Mantiene las preferencias del usuario actualmente logueado (o las preferencias por default
 * si no hay ningún usuario logueado y no se pasa ningún ID de usuario a la función load().
 *  
 * Se inicializa llamando al método estático load($userid), que devuelve un objeto de 
 * esta clase con sus valores ya cargados a partir de lo guardado en la base de datos. 
 * 
 * En la base, la tabla preferencia guarda las preferencias de cada usuario individual y, 
 * además, guarda un registro con las preferencias por default para todos los usuarios. 
 * Cada vez que se cargan las preferencias de un usuario, si para un campo no tiene
 * una preferencia seteada, se toma el valor por defecto. 
 * 
 * @package common
 */
class UserPref
{
	private $prefs = array(); 
	
	/**
	 * El constructor es privado para forzar a que siempre se creen objetos de esta
	 * clase llamando al método estático load()
	 * 
	 * @param $prefs Array con las preferencias cargadas de la base
	 */
	private function __construct($prefs) 
	{
		$this->prefs = $prefs;
	}
	
	/**
	 * Inicializa un objeto nuevo con las preferencias del usuario pasado como parámetro. 
	 * Si no hay ningún usuario logueado y no se pasa ninguno, se cargan las preferencias default.  
	 * 
	 * La primera vez lo carga de la base de datos y lo guarda en $_SESSION. Veces sucesivas
	 * directamente lo trae de la sesión guardada.
	 * 
	 * Se hacen 2 consultas a la base de datos: la primera trae los valores defaulta para 
	 * todos los usuarios. La segunda trae los valores particulares de este usuario. Cuando
	 * hay un valor específico de usuario, se toma ése. Cuando el valor del usuario es NULL, 
	 * se toma el valor global por defecto.  
	 * 
	 * @param $usuarioid ID del usuario. Si no se especifica, lo saca de la variable global. Si no hay usuario logueado, trae preferencias por default. 
	 * @return UserPref
	 */
	public static function load($usuarioid = NULL)
	{
		if (is_null($usuarioid)) {
			$usuarioid = Seguridad::getCurrentUserId();  
		}

		if (is_null($usuarioid)) {
			return UserPref::loadDefault(); 	// devuelve las preferencias default
		}
		
		if (isset($_SESSION['UserPref' . $usuarioid])) { 
			return $_SESSION['UserPref' . $usuarioid];
		}
		
		$rs = Database::query("SELECT * FROM gral_preferencia WHERE id_usuario IS NULL");
		$prefs = $rs->getRow();
		
		$aux = array();
		$aux['id_preferencia'] = $prefs['id_preferencia'];
		$aux['id_usuario'] = $prefs['id_usuario'];
		$aux['q_page_size'] = $prefs['q_page_size'];
		$aux['q_nav_pos'] = $prefs['q_nav_pos'];
		$aux['q_show_filters'] = $prefs['q_show_filters'];
		$aux['excel_num_formats'] = $prefs['excel_num_formats'];
		$aux['q_action_pos'] = $prefs['q_action_pos'];
		$aux['q_print_page_size'] = $prefs['q_print_page_size'];
		$aux['num_format'] = $prefs['num_format'];
		$aux['date_format'] = $prefs['date_format'];
		$aux['excel_date_format'] = $prefs['excel_date_format'];
		$aux['ej'] = $prefs['ej'];
		$aux['excel_list_sep'] = $prefs['excel_list_sep'];
		
		
		
		$rs = Database::query ("SELECT * FROM gral_preferencia WHERE id_usuario = ?", $usuarioid);
		$row = $rs->getRow();
		
		if ($row) {
			foreach ($row as $key => $value) {
				if (!is_null($value)) { 
					$aux[$key] = $value; 
				}
			}	
		}

		$userPref = new UserPref($aux);
		
		$_SESSION['UserPref' . $usuarioid] = $userPref;

		return $userPref; 
	}
	
	/**
	 * Devuelve las preferencias por default (las que aplican si el usuario no 
	 * especifica una particular). Util para funciones donde no hay ningún usuario
	 * logueado. 
	 * 
	 * @return UserPref
	 */
	public static function loadDefault()
	{
		$rs = Database::query("SELECT * FROM gral_preferencia WHERE id_usuario IS NULL");
		$prefs = $rs->getRow();
		return new UserPref($prefs);
	}
	
	/**
	 * Fuerza que se carguen nuevamente las preferencias del usuario en la $_SESSION. 
	 * 
	 * @param $usuarioid ID del usuario. Si no se especifica, lo saca de la variable global.  
	 * @return void
	 */
	public static function reload($usuarioid = NULL)
	{
		if (is_null($usuarioid)) {
			$usuarioid = Seguridad::getCurrentUserId();  
		}
		
		if (isset($_SESSION['UserPref' . $usuarioid])) {
			unset($_SESSION['UserPref' . $usuarioid]);
		}
	}	
	
	/**
	 * Devuelve la preferencia indicada por $key. 
	 * 
	 * @param $key Nombre de la preferencia (coincide con el nombre del campo en la tabla preferencia)
	 * @param $def Default a devolver si el parámetro $key no tiene ningún valor
	 */
	public function get($key, $def = NULL) 
	{
		$k = NULL; 
		if (isset($this->prefs[$key]))
			$k = $this->prefs[$key]; 
		return is_null($k) ? $def : $k;
	}
	
	/**
	 * Dada una variable y su tipo, la convierte a string según las preferencias personales del usuario.  
	 * 
	 * @param $valor El valor a representar
	 * @param $tipo El tipo de datos
	 * @return string El texto listo para mostrar al usuario
	 */
	public function toString($valor, $tipo)
	{
		if (is_null($valor)) 
			return "";
			
		switch ($tipo)
		{
			case Tipo::STRING:
				return $valor;
				break;
				
			case Tipo::NUMBER:
				return $valor; 
				break;
				
			case Tipo::MONEY:
				if ($this->get('num_format', 'english') == 'english') { 
					return number_format ($valor, 2, '.', ',');
				} else {
					return  number_format ($valor, 2, ',', '.');
				}
				break; 
			
			case Tipo::DATE:
				//------
				// Esta línea, que es más prolija, sólo funciona con PHP 5.2 en adelante:
				//return date_format (date_create($valor), $this->get('date_format', 'Y-m-d'));
				//------
				date_default_timezone_set("America/Argentina/Buenos_Aires");
				return date ($this->get('date_format', 'Y-m-d'), strtotime($valor));
				
				break;
				
			case Tipo::BOOLEAN:
				return $valor == 't' ? 'Sí' : 'No';
				break;
				
			case Tipo::TIMESTAMP:
				//------
				// Esta línea, que es más prolija, sólo funciona con PHP 5.2 en adelante:
				//return date_format (date_create($valor), $this->get('date_format', 'Y-m-d'));
				//------
				return date ($this->get('date_format', 'Y-m-d') . ' G:i:s', strtotime($valor));
				
				break;				
		}
	}
}