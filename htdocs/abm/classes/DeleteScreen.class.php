<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos asociados a una pantalla de eliminación de un registro. 
 * Permite eliminar un registro dada su tabla y su clave primaria, opcionalmente mostrando un 
 * mensaje de confirmación. 
 * 
 * Puede ejecutar sentencias SQL antes y después de ejecutar el delete, y que reciben como 
 * único parámetro la clave primaria del registro a eliminar. 
 * 
 * Se puede definir una lista de posibles mensajes de error a devolver por la base y sus
 * descripciones. De esta forma, se puede dejar que el DELETE falle, por ejemplo, por un 
 * foreign key constraint pero en lugar de devolver al usuario el mensaje crudo de la base, 
 * se devuelve un mensaje amigable. 
 * 
 * Ejemplo: la base devuelve: ERROR: update or delete on table "gral_doc" violates foreign key constraint "fk_cc_cabecera__dr".
 * se puede atrapar con: 
 * 		addErrorMsg ('fk_cc_cabecera__dr', 
 * 					 'No se puede eliminar el registro porque existen cabeceras asociadas');
 * 
 * El valor de la clave primaria que tiene que buscar viene en el request.
 * 
 * @property string 	$tabla		Nombre de la tabla donde buscar el registro
 * @property string		$campo		Nombre del campo que es clave primaria de la tabla
 * @property string		$tipo		Tipo de datos de la columna que es clave primaria de la tabla
 * @property string		$param		Nombre del parámetro en el request donde viene el valor de la primary key
 * @property string		$msgConfirmar	Mensaje de confirmación a mostrar al usuario antes de eliminar el registro.
 * @property array		$constraints	Lista de constraints que se deben cumplir para poder eliminar el registro
 * @property array		$actionsPre		Sentencias SQL a ejecutar antes de ejecutar el delete
 * @property array		$actionsPost 	Sentencias SQL a ejecutar después de ejecutar el delete
 * @property array		$errorMsgs		Descripciones para los posibles mensajes de error que tire la base
 * @property bool		$bajaLogica		Si es TRUE, en lugar de borrar el registro, se pasa su campo "activo" a false
 *  
 * 
 * @package abm 
 */
class DeleteScreen extends Screen
{
	//------------
	// Propiedades
	//
	private $tabla; 
	private $campo; 
	private $param = "id"; 
	private $tipo = Tipo::NUMBER;
	private $msgConfirmar;
	private $constraints = array();
	private $actionsPre = array();
	private $actionsPost = array();
	private $errorMsgs = array(); 
	private $bajaLogica = false; 
	//----------------
	 
	/** valor de la clave primaria a editar */
	private $pkValue; 

	
	public function __construct()
	{
		parent::__construct();
		$this->setReturnUrl("abm/delete.php?cmd=return&screenid=" . $this->getId());		
	}
	
	
	public function addConstraint($constraint)
	{
		$this->constraints[] = $constraint;
	}

	
	/**
	 * Clases derivadas crearán aquí un tipo de pantalla específico. 
	 */
	public function init() 
	{
	}

	public function refrescar()
	{
	}
	
	public function load()
	{
		if (is_null ($this->pkValue)) {
			$this->pkValue = $_REQUEST[$this->param];
		}	
	}
	
	/*
	 * Atajo para no tener que setear cada uno de los atributos que se usan siempre por separado.
	 */
	public function setDbData($tabla, $campo, $param = "id", $tipo = Tipo::NUMBER)
	{
		$this->setTabla ($tabla);
		$this->setCampo ($campo);
		$this->setTipo ($tipo);
		$this->setParam ($param);	
	}
	
	/**
	 * Le da la posibilidad a clases descendientes de implementar algo una vez que se ejecutó la rutina
	 * normal de guardar(). 
	 */
	public function afterGuardar()
	{
		
	}
	
	/**
	 * Elimina el registro.
	 * En caso de error, el mismo se puede consultar con getError()
	 * @return bool TRUE si pudo hacerlo, FALSE si hubo algún error
	 */
	public function guardar()
	{
		$tabla = $this->tabla; 
		$campo = $this->campo; 

		$sql = ""; 
		
		if ($this->bajaLogica) {
			$sql = "UPDATE $tabla SET activo = false WHERE $campo = ?";
		} else {
			$sql = "DELETE FROM $tabla WHERE $campo = ?";
		}
		
		$pk = $this->getPkValue();
		
		try {
			//FIXME: todas las ejecuciones deberían estar dentro de la misma transacción
			
			foreach ($this->getActionsPre() as $sql) {
				Database::execute($sql, $pk);
			}
			
			Database::query($sql, $pk);
			
			foreach ($this->getActionsPost() as $sql) {
				$params = strpos($sql, '?') !== FALSE ? $pk : null;
				Database::query($sql, $params);
			}
			
			if (! is_null($this->getLogEvento())) {
						// si la PK no es numérica, no tengo lugar en el registro de log para guardarla ... 
				Utils::log ($this->getLogEvento(), $tabla, $this->tipo == Tipo::NUMBER ? $pk : NULL);
			}
			
			$this->afterGuardar();
			
		} catch (Exception $e) {
			$msg = $e->getMessage(); 
			
			$this->setError ($e->getMessage()); 
			
			foreach ($this->errorMsgs as $key => $value) {
				if (strpos(strtolower($msg), strtolower($key)) !== false) {
					$this->setError('ERROR: ' . $value);
					break; 
				}
			}
	
			return false; 
		}
		
		return true; 
	}
	
	public function validar()
	{
		foreach ($this->constraints as $c)
		{
			if ( ! $c->validar ($this)) {
				$this->setError($c->getError());
				return false; 
			}	
		}
		return true;
	}
	
	public function isConfirmable()
	{
		return ! is_null($this->msgConfirmar);
	}
	
	
	/**
	 * Agregar una acción (sentencia SQL) que se ejecutará después de ejecutar
	 * el DELETE. La sentencia SQL puede o no incluir 
	 * un parámetro ? que recibirá la Pk del registro a eliminar.  
	 *  
	 * @param $sql Ejemplo: DELETE FROM tabla_asociada WHERE pk = ?
	 * @return void
	 */
	public function addActionPost($sql) {
		$this->actionsPost[] = $sql; 
	}

	/**
	 * Agregar una acción (sentencia SQL) que se ejecutará antes de ejecutar
	 * el DELETE. La sentencia SQL puede o no incluir 
	 * un parámetro ? que recibirá la Pk del registro a eliminar.  
	 *  
	 * @param $sql Ejemplo: DELETE FROM tabla_asociada WHERE pk = ?
	 * @return void
	 */	
	public function addActionPre($sql) {
		$this->actionsPost[] = $sql; 
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
	public function getMsgConfirmar() {
		return $this->msgConfirmar; 
	}
	public function setMsgConfirmar($msgConfirmar) {
		$this->msgConfirmar = $msgConfirmar; 
	}
	public function getConstraints() {
		return $this->constraints;
	}
	public function setConstraints($constraints) {
		$this->constraints = $constraints;
	}
	public function getActionsPre() {
		return $this->actionsPre;
	}
	public function setActionsPre($actionsPre) {
		$this->actionsPre = $actionsPre; 
	}
	public function getActionsPost() {
		return $this->actionsPost; 
	}
	public function setActionsPost($actionsPost) {
		$this->actionsPost = $actionsPost; 
	}
	public function getErrorMsgs() {
		return $this->errorMsgs;
	}
	public function setErrorMsgs($errorMsgs) {
		$this->errorMsgs = $errorMsgs;
	}
	public function getBajaLogica() {
		return $this->bajaLogica; 
	}
	public function setBajaLogica($bajaLogica = true) {
		$this->bajaLogica = $bajaLogica;
	}
}