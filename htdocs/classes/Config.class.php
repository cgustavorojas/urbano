<?php
/**
 * @package default
 */

/**
 * Permite acceder a los parámetros de configuración generales del sistema, que se guardan en la tabla gral_config. 
 *
 * La tabla guarda un registro por cada parámetro, que puede ser de distinto tipo, aunque siempre se guarda en una
 * columna de tipo varchar. 
 * 
 * La clase funciona somo singleton, generando un único objeto por session, que la primera vez que se crea se 
 * guarda en $_SESSION y luego se recupera de allí con getInstance().
 *  
 * @package default
 */
class Config
{
	/** array asociativo que guarda los parámetros y sus valores */
	private $valores = array();
	/** clave de $_SESSION donde guarda la configuración actual */
	const SESSION_KEY = 'config.class';
	
	
	/**
	 * El constructor es privado para que no se puedan generar nuevos objetos de esta clase si no es 
	 * con la llamada a getInstance(). 
	 */
	private function __construct() {
		
	}

	/**
	 * Devuelve una instancia de la configuración. La primera vez que se llama crea un nuevo objeto
	 * y lo carga con los valores de la base de datos, guardándolo en la session activa. En veces
	 * sucesivas, se trae el objeto guardado en la session. 
	 * @return Config
	 */
	public static function getInstance()
	{
		if (isset($_SESSION[Config::SESSION_KEY])) {
			return $_SESSION[Config::SESSION_KEY];
		}
		
		$cfg = new Config(); 
		$cfg->load();
		$_SESSION[Config::SESSION_KEY] = $cfg; 
		return $cfg; 
	}
	
	/**
	 * Carga la configuración de la base de datos y la guarda en un array privado. 
	 * Sólo puede ser llamado desde métodos internos a la clase (se llama en getInstance()).  
	 * @return void
	 */
	private function load()
	{
		$rs = Database::query('SELECT id_config, valor FROM gral_config');
		
		while ($row = $rs->getRow()) {
			$this->valores[$row['id_config']] = $row['valor'];		
		}
	}

	/**
	 * Carga nuevamente la configuración desde la base. 
	 * @return void
	 */
	public function reload()
	{
		$this->valores = array(); 
		$this->load();
	}
	
	/**
	 * Devuelve el valor de un parámetro de configuración. 
	 * @param string $id La clave del parámetro (columna id_config de la tabla gral_config)
	 * @return string El valor del parámetro (columna valor)
	 */
	public function get($id)
	{
		return $this->valores[$id];
	}
	
}
