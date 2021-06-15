<?php
/**
 * @package common
 */

/**
 * Representa un usuario del sistema (tabla gral_usuario). 
 * Creado en el momento de login y guardado en $_SESSION como parte del objeto Session.
 * 
 * Se crea con el método estátido crearUsuario() y no con el constructor. 
 *
 * @package common
 */
class Usuario
{  
	private  $id_usuario;
	private  $usuario;
	private  $nombre;
	private  $apellido;
	private  $telefono;
	private  $email;
	private  $txt; 
	
	private $permisos = array();
	private $areas    = array();


	/**
	 * El constructor es privado porque los objetos se crean llamando al método estático crearUsuario. 
	 * 
	 * @param array $row  El registro traído de la tabla gral_usuario
	 * @return Usuario El objeto nuevo
	 */
	private function __construct ($row) 
	{
		$this->id_usuario = $row['id_usuario'];
		$this->usuario = $row['usuario'];
		$this->nombre = $row['nombre'];
		$this->apellido = $row['apellido'];
		$this->telefono = $row['telefono'];
		$this->email = $row['email'];
		$this->txt = $row['txt'];
	}

	public function getId() {
		return $this->id_usuario; 
	}
	
	/**
	 * Devuelve el usuario por el cual se loguea una persona.
	 * @return string 
	 */
	public function getUsuario() {
		return $this->usuario; 
	}
	
	
	/**
	 * Devuelve el ID del usuario (PK de la tabla gral_usuario).
	 * @return integer 
	 */
	public function getUsuarioId() {
		return $this->id_usuario;
	}
	
	public function getDescripcion() {
		return $this->txt;
	}
	
	
	/**
	 * Devuelve la lista de IDs de ubicaciones a las que pertenece el usuario, 
	 * separadas por coma (listas para usarse en una claúsula "where id_unidad_ejecutora in (xxxx)".
	 * 
	 * @return string Lista de Ids separador por coma (ej: "143,567,85,18").
	 */
	function getUbicacionesSQL() 
	{
		$where_aux = '';
		
		foreach ($this->areas as $a) 
		{
			$delimiter = '';
			if (trim($where_aux)!='') {
				$delimiter = ',';
			}
			
			$where_aux = $where_aux .$delimiter . $a; 		
		}
		return $where_aux;
	}

	/**
	 * Devuelve la cantidad de unidades ejecutoras a las que pertenece el usuario
	 * @return integer
	 */
	function getCantUbicaciones() {
		return sizeof($this->areas);
	}
	
	
	/**
	 * Verifica si el usuario tiene habilitado un determinado permiso, dado por su ID alfanumérico. 
	 * @param string $id_permiso PK de la tabla gral_permiso.
	 * @return bool TRUE si tiene dicho permiso, FALSE si no. 
	 */
	function tieneHabilitado($id_permiso)
	{
		return in_array($id_permiso, $this->permisos);
	}
	
	/*
	 * Se eliminaron los métodos: 
	 * 	tienePermisoTipoGasto($tipo_gasto) 
	 *  getPermisoAccesoTipoGasto()
	 * que fueron reemplazados por métodos optimizadosde igual nombre en la clase PreUtils (presupuesto/classes/PreUtils.class.php)
	 */
	
	/**
	 * Carga en $this->permisos la lista completa de permisos (sin discriminar por sistema) que este usuario tiene habilitados. 
	 */
	private function loadPermisos()
	{
		$this->permisos = array();
		$rs = Database::query ('SELECT id_permiso FROM gral_v_permiso WHERE id_usuario = ?', $this->id_usuario);
		while ($row = $rs->getRow()) {
			
			
			$this->permisos[] = $row['id_permiso'];
		}
		
	}
	
	/**
	 * Functión estática usada para crear usuarios. 
	 * 
	 * @param integer $usuario ID del usuario en la tabla gral_usuario
	 * @return Usuario
	 */
	public static function crearUsuario($id_usuario) 
	{
			global $dbConn; 
			 
			if (! $row = Database::query ("SELECT u.* FROM gral_usuario u WHERE id_usuario = ?", $id_usuario)->getRow())
				return null;
					
			$usuario = new Usuario($row);
			
			$usuario->loadPermisos();
					
			return $usuario;
	}
	
	public function getTxt() {
		return $this->txt; 
	}
	public function setTxt($txt) {
		$this->txt = $txt; 
	}
	
	public function getEmail() {
		return $this->email;
	}
}
