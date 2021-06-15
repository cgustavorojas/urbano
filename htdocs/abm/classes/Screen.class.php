<?php
/**
 * @package abm
 */

/**
 * Clase muy sencilla que sirve de base para las ventanas específicas. 
 * 
 * @property string 	$id			Identificador único de la ventana
 * @property string 	$titulo		Título de la ventana
 * @property string		$returnUrl	URL a la que hay que apuntar para volver a mostrar esta ventana
 * 									luego que se "saltó" a otra ventana posterior
 * @property string		$forwardUrl URL a la que hay que ir una vez terminada la edición de esta 
 * 									ventana. Ej: después del alta de un registro, saltar a la pantalla de consulta.
 * @property string		$error		Si la pantalla tiene algún error de validación o de ejecución, 
 * 									el mensaje de error. NULL si al ventana es válida y está ok.
 * @property string		$logEvento	El código de evento a loguear cuando el usuario ejecuta la acción principal
 * 									de la pantalla (ej: elimina, inserta o borra un registro)  
 * @property string		$permiso	Permiso que requiere el usuario para poder acceder a esta pantalla. Si se especifica y el usuario
 * 									no posee este permiso, se devuelve un error de acceso denegado. Si no se especifica, cualquier 
 * 									usuario está habilitado a ejecutar esta pantalla (si la tiene habilitada o no en el menú es otra
 * 									historia, pero si conoce la URL, el framework no le va a impedir el acceso). 	
 * 
 * Lo que tienen en comunes todas las pantallas es que son stackeables por ScreenManager.
 * @package abm  
 */
class Screen
{
	const CERRAR = 1;		// usadas como código de retorno especiales para significar qué hacer frente a una situación  
	const VOLVER = 2; 		// usadas como código de retorno especiales para significar qué hacer frente a una situación
	const HECHO  = 3; 		// usadas como código de retorno especiales para significar qué hacer frente a una situación
	
	//------------
	// Propiedades
	//
	private $id;
	private $titulo;  
	private $returnUrl;
	private $forwardUrl; 
	private $error;
	private $logEvento; 
	private $logDetallado = false; 
	private $permiso;
	private $helpId;
	private $helpCargado = false; 
	
	//
	//------------ 
	 
	/** preferencias del usuario */
	private $userPrefs;   
	
	public function __construct() 
	{
		$this->id = "s" . rand(); 
		$this->userPrefs = UserPref::load();
	}
	
	/**
	 * Inicialización de la pantalla. 
	 * A nivel genérico, sólo se controla que el usuario tenga permiso (si lo requiere) para ejecutar la pantalla. 
	 * Clases más esepcíficas harán las inicializaciones necesarias. 
	 *
	 * @throws Exception Si el usuario no está habilitado.
	 */
	public function init()
	{
		if (! is_null($this->permiso) && ! Seguridad::tieneHabilitado($this->permiso)) {
			throw new Exception ('Ud. no está habilitado a ejecutar esta pantalla. Solicite la habilitación del permiso ' . $this->permiso . '.'); 
		}		
		
		// Si no se especificó nada en contrario, el código de la página de ayuda es el nombre de la clase
		if (is_null($this->getHelpId())) {
			$this->setHelpId (get_class($this));
		}
		
		// Me fijo si está cargado el help online para esta página (así las vistas pueden mostrar el botón de ayuda contextual)
		$this->helpCargado = Database::simpleQuery('SELECT count(*) FROM gral_help WHERE id_help = ?', $this->getHelpId()) > 0;
	}
	
	/**
	 * Verifica si la pantalla no tiene ningún error seteado con setError(). 
	 * @return boolean TRUE si getError() devuelve NULL.
	 */
	public function isValid() {
		return is_null($this->error);
	}
	
	/**
	 * Devuelve código javascript que se agregará a la pantalla HTMl al final de la definición 
	 * de todos los controles. 
	 * 
	 * @return string
	 */
	public function getJavaScript() {
		return ''; 
	}
	
	//---- getters && setters ----//
	
	public function getId() {
		return $this->id; 
	}
	public function setId($id) {
		$this->id = $id; 
	}
	public function getTitulo() {
		return $this->titulo; 
	} 
	public function setTitulo($titulo) {
		$this->titulo = $titulo; 
	}
	public function getReturnUrl() {
		return $this->returnUrl; 
	}
	public function setReturnUrl($returnUrl) {
		$this->returnUrl = $returnUrl; 
	}
	public function getUserPrefs() {
		return $this->userPrefs; 
	}
	public function getUserPref($key) {
		return $this->userPrefs->get($key);
	}
	public function getError() {
		return $this->error; 
	}
	public function setError($error) {
		$this->error = $error; 
	}
	public function getForwardUrl() {
		return $this->forwardUrl; 
	}
	public function setForwardUrl($forwardUrl) {
		$this->forwardUrl = $forwardUrl; 
	}
	public function getLogEvento() {
		return $this->logEvento;
	}
	public function setLogEvento($logEvento) {
		$this->logEvento = $logEvento;
	}
	public function getLogDetallado() {
		return $this->logDetallado;
	}
	public function setLogDetallado($logDetallado = true) {
		$this->logDetallado = $logDetallado;
	}
	public function getPermiso() {
		return $this->permiso; 
	}
	public function setPermiso($permiso) {
		$this->permiso = $permiso; 
	}
	public function setHelpCargado($helpCargado = true) {
		$this->helpCargado = $helpCargado; 
	}
	public function isHelpCargado() {
		return $this->helpCargado;
	}
	public function getHelpId() {
		return $this->helpId;
	}
	public function setHelpId($helpId) {
		$this->helpId = $helpId; 
	}
	
}