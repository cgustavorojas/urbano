<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos de una pantalla de selección múltiple de ítems. 
 * Es una pantalla que, como resultado de un query que devuelve registros, muestra los registros
 * por pantalla (sin paginación) y permite mediante checkboxes seleccionar uno o varios. 
 *
 * @package abm
 */
class MultiSelectScreen extends Screen
{
	private $msgInfo; 
	private $msgOk; 
	private $msgEmpty = "No hay datos para mostrar";
	private $sql; 
	private $sqlDinamico;	
	private $params; 
	private $campo; 
	private $tipo = Tipo::NUMBER; 
	private $errorMsgs = array(); 		
	private $selected = array();
	private $filtros;	
	private $inputs;
	
	
	
	public function __construct () 
	{
		parent::__construct();
		$this->filtros = new ListaFiltros();
		$this->inputs = new ListaInputs();
		
		$id = $this->getId(); 
		
		$this->setReturnUrl("abm/mselect.php?cmd=return&screenid=$id");
	}
	
	public function init()
	{
		$this->filtros->init();
		$this->inputs->init();
		parent::init();	
	}
	
	public function refrescar()
	{
		$this->filtros->refrescar(); 
		$this->inputs->refrescar(); 
	}
	
	public function validar()
	{
		$this->setError(null);
		$ok = false;
		if($this->filtros->validar() && $this->inputs->validar())
			$ok= true;
			
	    return $ok;
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
	 * Parsea el request y saca del mismo los IDs de todos los registros seleccionados. 
	 */
	public function parseRequest()
	{
		$this->filtros->parseRequest();
		$this->inputs->parseRequest();
		
		if (isset ($_REQUEST['mselect-inputs'])) {	// si no seleccionó ninguno, directamente no viene el atributo ...
			$this->setSelected($_REQUEST['mselect-inputs']);
		} else {
			$this->setSelected(array());	// array vacío = ninguno seleccionado
		}
	}


	public function addInput (Input $input) {
			$this->inputs->add($input); 
		}
		
	public function addInputOb (Input $input) {
			$input->setObligatorio();
			$this->inputs->add($input);
		}
		
		
	public function getInputs() {
			return $this->inputs; 
	}	
	/**
	 * Esta ventana no tiene una lógica propia a ejecutar. Esta función debe ser sobreescrita en cada clase heredada.
	 * En caso de error debe lanzar una exception.  
	 * @return void
	 */
	public function guardar()
	{
		
	}
	
	/**
	 * Punto de llamada luego de ser guardados los registros. En otras pantallas que tienen alguna implementación default de guardar()
	 * tiene más sentido (permite ejecutar algo custom luego del comportamiento default de guardar()), pero se implementa por compatibilidad
	 * y guardar coherencia con el resto de las pantallas. 
	 * 
	 */
	public function afterGuardar()
	{
		
	}
	
	public function setSelected($ids)
	{
		$this->selected = $ids; 
	}
	
	/**
	 * Arma una sentencia SQL que tiene ya los filtros embebidos como cláusulas WHERE. 
	 * Reemplaza en el SQL original, la aparición de la etiqueta mágica #FILTROS#
	 * por una cláusula dinámica armada en función a los valores de los filtros.
	 *  
	 * @return string SQL listo para ejecutar
	 */
	public function buildDinamicSql()
	{
		$sql = $this->getSql(); 
		
		if (is_null($sql)) {	// si no me especificaron SQL, no tengo nada que hacer. 
			$this->sqlDinamico = null;
			return null;
		}
		
		$clause = $this->getFiltros()->getWhereClause();
		
		if (strpos ($sql, '#FILTROS#') === false) {
			$sql = $sql . ' WHERE ' . $clause; 
		} else {
			$sql = str_replace ("#FILTROS#", $clause, $sql);
		}

//		if (! is_null($this->orderBy))
//		{
//			if (strpos ($sql, '#ORDERBY#') === false) {
//				$sql = $sql . ' ORDER BY ' . $this->orderBy; 
//			} else {
//				$sql = str_replace ("#ORDERBY#", $this->orderBy, $sql);
//			}
//		}

		$this->sqlDinamico = $sql;
		
		return $sql; 
	}	
	
	/**
	 * Ejecuta el query y devuelve un objeto con los registros resultantes. 
	 * @return DatabaseResult
	 */
	public function executeQuery()
	{
		if (is_null($this->sqlDinamico))
			return null;
			
		$sql = $this->sqlDinamico; 

		return Database::query($sql);		
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
	
	

	//---------------------------------- getters && setters -----------------------------------// 
	
	public function getFiltros() {
		return $this->filtros;
	}
	public function setFiltros(ListaFiltros $filtros) {
		$this->filtros = $filtros;
	}
	public function addFiltro(Filtro $filtro, $posicion = null) {
		if (! is_null($posicion))
			$filtro->setPosicion($posicion);
		$this->filtros->add($filtro);
	}
	
	public function getMsgInfo() {
		return $this->msgInfo; 
	}
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function getMsgOk() {
		return $this->msgOk; 
	}
	public function setMsgOk($msgOk) {
		$this->msgOk = $msgOk; 
	}
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
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
	public function getErrorMsgs() {
		return $this->errorMsgs;
	}
	public function setErrorMsgs($errorMsgs) {
		$this->errorMsgs = $errorMsgs;
	}
	public function getSelected() {
		return $this->selected; 
	}
	public function getSelectedCount() {
		return count($this->selected); 
	}
	public function getParams() {
		return $this->params; 
	}
	public function setParams($params) {
		$this->params = $params; 
	}
	public function getMsgEmpty() {
		return $this->msgEmpty; 
	}
	public function setMsgEmpty($msgEmpty) {
		$this->msgEmpty = $msgEmpty; 
	}	
}