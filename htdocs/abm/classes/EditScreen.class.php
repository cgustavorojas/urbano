<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos asociados a una pantalla de edición de un registro. 
 * Una pantalla de edición de un registro es una pantalla que edita uno y solo un registro 
 * sacado de una tabla, buscando por un único campo: su clave primaria. 
 * 
 * El valor de la clave primaria que tiene que buscar viene en el request.
 * 
 * @property string 	$tabla		Nombre de la tabla donde buscar el registro
 * @property string		$campo		Nombre del campo que es clave primaria de la tabla
 * @property string		$tipo		Tipo de datos de la columna que es clave primaria de la tabla
 * @property string		$param		Nombre del parámetro en el request donde viene el valor de la primary key
 * @property string		$sql		Sentencia SQL usada para traer los datos del registro (normalmente SELECT * FROM tabla WHERE campo = pkValue)
 * @property string		$msgInfo	Mensaje a mostrar arriba de los INPUTS a título informativo
 * @property string		$msgOk		Mensaje a mostrar si la edición fue exitosa
 * @property string		$btnAceptar  Leyenda que muestra el botón Aceptar (default: "Aceptar")
 * @property string		$btnCancelar Leyenda que muestra el botón Cancelar(default: "Cancelar") 
 * 
 * @package abm 
 */
class EditScreen extends Screen
{
	//------------
	// Propiedades
	//
	private $tabla; 
	private $campo; 
	private $param = "id"; 
	private $tipo = Tipo::NUMBER;
	private $sql;
	private $msgInfo; 
	private $msgOk; 	
	private $errorMsgs = array(); 	
	private $btnAceptar  = 'Aceptar';
	private $btnCancelar = 'Cancelar';	
	//----------------
	 
	/** valor de la clave primaria a editar */
	private $pkValue; 
	/** lista de controles para entrada de datos */
	private $inputs; 

	
	public function __construct()
	{
		parent::__construct();
		$this->inputs = new ListaInputs();
		$this->setReturnUrl("abm/edit.php?cmd=return&screenid=" . $this->getId());		
	}
	

	/**
	 * Clases derivadas crearán aquí un tipo de pantalla específico, agregando inputs y seteando propiedades. 
	 */
	public function init() 
	{
		if (is_null($this->campo) && ! is_null($this->tabla)) {
			$this->campo = 'id_' . substr($this->tabla, strpos($this->tabla, '.') + 1);	// asumo que la PK se llama "id_" + el nombre de la tabla, sin schema
		}
		$this->inputs->init();
		$this->inputs->setFocusToFirstOne();
		parent::init();
	}

	
	/**
	 * Llamado luego de init() y antes de refrescar() para cargar el registro a modificar 
	 */
	public function load() 
	{
		if (is_null ($this->pkValue)) {
			$this->pkValue = $_REQUEST[$this->param];
		}
	
		$tabla = $this->tabla; 
		$campo = $this->campo; 
		
		if (is_null($this->sql)) {
			$this->sql = "SELECT * FROM $tabla WHERE $campo = ?"; 
		}
					
		$row = Database::query($this->sql, array($this->pkValue))->getRow();

		
		$this->inputs->parseRow($row);
		$this->onRowLoad($row);
	}
	
	/**
	 * Se deja disponible este punto para implementar lógica que tenga en cuenta
	 * datos del registro una vez que éste se haya cargado. 
	 * 
	 */
	public function onRowLoad($row)
	{}
	
	/**
	 * Le da la posibiliad a todos los INPUTS que se inicialicen, tomando si fuera necesario datos del ambiente o la base. 
	 */
	public function refrescar() 
	{
		$this->inputs->refrescar();
	}

	/**
	 * Validación de datos. Se puede hacer tanto a nivel individual de controles como a nivel de la pantalla completa.
	 * En caso de error a nivel de ventana completa, se setea $this->error. Si el error es a nivel de 
	 * los controles, el mensaje de error lo guarda cada input. 
	 * @return bool TRUE si no hubo errores, FALSE si hay algún error.  
	 */
	public function validar()
	{
		$this->setError(null);
		return $this->inputs->validar();
	}
	
	
	/**
	 * Le da la posibilidad a todos los INPUTS que tomen del request el valor que les corresponda.
	 */
	public function parseRequest() 
	{
		$this->inputs->parseRequest();
		//$this->validar();
	}
	
	public function addInput (Input $input) {
		$this->inputs->add($input); 
	}
	
	/**
	 * Agrega el input pero antes le setea el flag para que sea obligatorio. 
	 * @param Input $input
	 * @return void
	 */
	public function addInputOb (Input $input) {
		$input->setObligatorio();
		$this->inputs->add($input);
	}
		
	/**
	 * Atajo para no tener que setear cada uno de los atributos que se usan siempre por separado.
	 */
	public function setDbData($tabla, $campo, $param = 'id', $tipo = Tipo::NUMBER)
	{
		$this->setTabla ($tabla);
		$this->setCampo ($campo);
		$this->setTipo ($tipo);
		$this->setParam ($param);		
	}
	
	/**
	 * Devuelve TRUE si todos los inputs son válidos y si la ventana en sí es no tiene error  
	 * @return bool
	 */
	public function isValid()
	{
		return parent::isValid() && $this->inputs->isValid();
	}
	
	/**
	 * Guarda el registro.
	 * En caso de error, el mismo se puede consultar con getError()
	 * @return bool TRUE si pudo hacerlo, FALSE si hubo algún error
	 */	
	public function guardar()
	{
		$this->setError(null); 
		
		if ( ! $this->inputs->isChanged()) {
			return true;		// el usuario dio OK pero no modificó ningún valor
		}
		
		try {
			$stmt = new UpdateStmt($this->getTabla(), $this->getCampo(), $this->getPkValue());
	
			$this->inputs->fillParameter($stmt); 
			
			$stmt->execute();	//si falla, se lanza una exception que es atrapada en el llamador (alta.php) 
	
			if (! is_null($this->getLogEvento())) { 
				Utils::log ($this->getLogEvento(), $this->getTabla(), 
						$this->getTipo() == Tipo::NUMBER ? $this->getPkValue() : NULL,
						$this->getLogDetallado() ? $this->inputs->toString(false, true) : NULL);
			}
		} catch (Exception $e) {
			
			$this->handleError($e); 
	
			return false; 					
		}
		
		return true; 
		
	}
	
	public function handleError($e)
	{
		$msg = $e->getMessage(); 
		
		$this->setError ($e->getMessage()); 
		
		foreach ($this->errorMsgs as $key => $value) {
			if (strpos(strtolower($msg), strtolower($key)) !== false) {
				$this->setError('ERROR: ' . $value);
				break; 
			}
		}
	}
		
	/**
	 * Función llamada luego de haber guardado (exitosamente) el registro. 
	 * La implementación default no hace nada, pero se puede implementar en clases derivadas para 
	 * hacer algo después del update (ej: mandar un mail, guardar un log, etc.). 
	 * @return void
	 */
	public function afterGuardar()
	{
		// abre la posibilidad a clases derivadas de ejecutar algo aquí	
	}
		
	/**
	 * Agrega una descripción a un posible mensaje de error que devuelva la base. 
	 * 
	 * @param $msg Mensaje de error (o parte de él). Ej: 'violates foreign key'
	 * @param $descripcion Texto descriptivo. Ej: 'No se puede eliminar el registro porque existen registros asociados'
	 */
	public function addErrorMsg($msg, $descripcion)
	{
		$this->errorMsgs[$msg] = $descripcion;
	}

	public function addSubtitulo($txt)
	{
		$this->addInput(new InputSubtitulo($txt));
	}
	
	
	//--- getters && setters ---//
	
	public function getPkValue() {
		return $this->pkValue; 
	}
	public function setPkValue($pkValue) {
		return $this->pkValue = $pkValue; 
	}
	public function getParam() {
		return $this->param; 
	}
	public function setParam($param) {
		$this->param = $param; 
	}
	public function getTabla() {
		return $this->tabla; 
	}
	public function setTabla($tabla) {
		$this->tabla = $tabla; 
	}
	public function getCampo() {
		return $this->campo; 
	}
	public function setCampo($campo) {
		$this->campo = $campo; 
	}
	public function getTipo() {
		return $this->tipo; 
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo; 
	}
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
	}
	/**
	 * @return ListaInputs
	 */
	public function getInputs() {
		return $this->inputs; 
	}
	public function getMsgOk() {
		return $this->msgOk; 
	}
	public function setMsgOk($msgOk) {
		$this->msgOk = $msgOk; 
	}
	public function getMsgInfo() {
		return $this->msgInfo; 
	}
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function getBtnAceptar() {
		return $this->btnAceptar; 
	}
	public function setBtnAceptar($btnAceptar) {
		$this->btnAceptar = $btnAceptar; 
	}
	public function getBtnCancelar() {
		return $this->btnCancelar; 
	}
	public function setBtnCancelar($btnCancelar) {
		$this->btnCancelar = $btnCancelar; 
	}
}