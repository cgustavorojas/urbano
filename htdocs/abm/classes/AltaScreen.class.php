<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos asociados a una pantalla de alta de un registro. 
 * Una pantalla de alta de un registro es una pantalla que edita uno y solo un registro 
 * que no existe previamente y al finalizar la edición, lo guarda con un INSERT, o llama a un SP o alguna
 * otra lógica particular.
 * Las propiedades $tabla y $sp son mutuamente excluyentes. O bien se agrega el registro haciendo
 * un insert sobre $tabla o bin se llama al stored procedure sp. En este último caso, se pasan como 
 * parámetros los inputs, en el mismo orden en que son definidos en la pantalla. Dado que los parámetros
 * del SP no tienen nombre, al definir cada input el parámetro campo pasa a ser opcional. 
 * 
 * @property string			$tabla		Nombre de la tabla donde guardar el registro
 * @property string			$sp			Nombre del stored procedure a llamar en lugar de hacer un INSERT sobre $tabla
 * @property ListaInputs	$inputs		Lista de controles para entrada de datos
 * @property string			$msgInfo	Mensaje a mostrar arriba de los INPUTS a título informativo
 * @property string			$msgOk		Mensaje a mostrar si el alta fue exitosa
 * @property string			$serialCol	Nombre de una columna de tipo serial cuyo ID hay que recuperar luego de insert
 * @property integer		$serialId	Si se especificó $serialCol, después del INSERT queda guardado el ID generado
 * @property string			$forwardView Nombre de la pantalla tipo ViewScreen a la que ir luego del insert. Se le pasa como parámetro el ID de $serialCol.
 * @property string			$btnAceptar  Leyenda que muestra el botón Aceptar (default: "Aceptar")
 * @property string			$btnCancelar Leyenda que muestra el botón Cancelar(default: "Cancelar") 
 *   
 * @package abm 
 */
class AltaScreen extends Screen
{
	//-----------------
	// Propiedades
	//
	private $tabla;
	private $sp; 
	private $inputs; 
	private $msgInfo; 
	private $msgOk; 
	private $serialCol;
	private $serialId; 
	private $forwardView;
	private $errorMsgs = array(); 	
	private $btnAceptar  = 'Aceptar';
	private $btnCancelar = 'Cancelar';
	//-----------------
	 
	
	public function __construct()
	{
		parent::__construct();
		$this->inputs = new ListaInputs();
		$id = $this->getId();
		$this->setReturnUrl("abm/alta.php?cmd=return&screenid=$id");
	}
	

	/**
	 * Clases derivadas crearán aquí un tipo de pantalla específico, agregando inputs y seteando propiedades. 
	 */
	public function init() 
	{
		$this->inputs->init();
		$this->inputs->initDefault();
		$this->inputs->setFocusToFirstOne();
		parent::init();
	}

		
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
	 * Devuelve TRUE si todos los inputs son válidos y si la ventana en sí es no tiene error  
	 * @return bool
	 */
	public function isValid()
	{
		return parent::isValid() && $this->inputs->isValid();
	}
	
	/**
	 * Guarda el registro.
	 * En caso de error, el mismo se puede consultar con getErrorMessage()
	 * @return bool TRUE si pudo hacerlo. Si no pudo, lanza una exception.
	 */
	public function guardar()
	{
		$this->setError(null); 
		
		try { 
			if (! is_null ($this->sp)) {
				
				$params = array(); 
				foreach ($this->inputs->getAll() as $input) {
					$params[] = $input->getValue();
				}
				$ret = Database::executeSp($this->sp, $params); //si falla, se lanza una exception que es atrapada en el llamador (alta.php)
				
				$this->serialId = $ret; 
				
				if (! is_null($this->getLogEvento())) {
					Utils::log ($this->getLogEvento(),	 // evento a loguear 
							$this->tabla, 				 // nombre de la tabla, pueden no haberla informado en cuyo caso queda en NULL
							is_null($this->tabla) ? null : $ret, // si informaron tabla, asumo que executeSp me va devolver el ID de la columna serial (es una convención)
							$this->getLogDetallado() ? $this->inputs->toString(true) : NULL); // si piden log detallado, agrego los valores ingresados por el usuario
				}
	
				if (! is_null($this->forwardView)) {
					$this->setForwardUrl ('abm/view.php?screen=' . $this->forwardView . '&id=' . $ret);
				}
				
			} else if (! is_null ($this->tabla)) {
				
				
				$stmt = new InsertStmt ($this->tabla);
				$stmt->setSerialCol($this->serialCol);			
				$this->inputs->fillParameter($stmt);
				$stmt->execute();	//si falla, se lanza una exception que es atrapada en el llamador (alta.php)
				
				if (! is_null($this->getLogEvento())) { 
					Utils::log ($this->getLogEvento(), $this->tabla, $stmt->getSerialId(), 
						$this->getLogDetallado() ? $this->inputs->toString(true) : NULL);
				}

				$this->serialId = $stmt->getSerialId();
				
				if (! is_null($this->forwardView)) {
					$this->setForwardUrl ('abm/view.php?screen=' . $this->forwardView . '&id=' . $this->serialId);
				}
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
	 * hacer algo después del insert (ej: mandar un mail, guardar un log, etc.). 
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
	
	public function getTabla() {
		return $this->tabla; 
	}
	public function setTabla($tabla) {
		$this->tabla = $tabla; 
	}
	/**
	 * @return ListaInputs
	 */
	public function getInputs() {
		return $this->inputs; 
	}
	public function getMsgInfo() {
		return $this->msgInfo; 
	}
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function getSp() {
		return $this->sp; 
	}
	public function setSp($sp) {
		$this->sp = $sp;
	}
	public function getMsgOk() {
		return $this->msgOk; 
	}
	public function setMsgOk($msgOk) {
		$this->msgOk = $msgOk; 
	}
	public function getSerialCol() {
		return $this->serialCol; 
	}
	public function setSerialCol($serialCol) {
		$this->serialCol = $serialCol; 
	}
	public function getForwardView() {
		return $this->forwardView; 
	}
	public function setForwardView($forwardView) {
		$this->forwardView = $forwardView; 
	}
	public function getErrorMsgs() {
		return $this->errorMsgs;
	}
	public function setErrorMsgs($errorMsgs) {
		$this->errorMsgs = $errorMsgs;
	}
	public function getSerialId() {
		return $this->serialId; 
	}	
	public function setSerialId($serialId) {
		$this->serialId = $serialId; 
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