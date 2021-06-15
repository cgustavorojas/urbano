<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos asociados a una pantalla de visualización de un registro. 
 * La pantalla de visualización de un registro es una pantalla que recibe como dato la PK de un registro
 * y con ese dato trae los datos asociados a ese registro y, opcionalmente, puede mostrar información
 * de consultas relacionadas.
 * 
 * @property string	$sql		Consulta SQL a ejecutar. Debe contener un parámetro (?) en el WHERE para el registro a buscar. Ej: SELECT * FROM tabla WHERE PK = ?
 * @property string	$param		Nombre del parámetro del request donde vendrá el valor de la PK. Default: "id".
 * @property string	$tipo		Tipo de datos de la columna PK. default: Tipo::NUMBER
 * @property array	$grupos		Array de objetos ListaColumns. Cada ListaCampo es un conjunto de campos a mostrar con un título.
 * @property array	$acciones	Lista de acciones que se ejecutarán a nivel de la pantalla completa.
 * @property array	$tabs		Array de objetos ViewTab.
 * @property int	$posiciones	Cantidad de columnas para distribuir los campos.
 * @property string	$msgInfo	Mensaje a mostrar arriba de los campos a título informativo   
 * 
 * @package abm
 */
class ViewScreen extends Screen
{
	//--------------
	// Propiedades
	//
	private $sql; 
	private $param = "id";
	private $tipo = Tipo::NUMBER; 
	private $grupos = array();
	private $acciones = array();	
	private $tabs = array();
	private $posiciones = 1; 
	private $msgInfo;
	private $campoTitulo; 
	//
	//--------------
	
	private $pkValue; 
	private $row; 
	private $currentTab = 0; 
	
	public function __construct()
	{
		parent::__construct(); 
		$this->setReturnUrl('abm/view.php?cmd=return&screenid=' . $this->getId());
	}
	
	public function init() 
	{
		// Me fijo cuántas posiciones (columnas) hay definidas en los grupos
		foreach ($this->grupos as $g) {
			if ($g->getPosicion() > $this->posiciones) {
				$this->posiciones = $g->getPosicion();
			}
		}
		foreach ($this->tabs as $t) {
			$t->init();
		}
		parent::init();
	}
	
	public function parseRequest()
	{	
	}
	
	/**
	 * Vuelve a cargar el registro desde la base de datos. 
	 * 
	 * El registro queda cargado en $this->row y a partir de ahí se muestra sus valores por pantalla. 
	 */
	public function refrescar()
	{
		$this->row = Database::query($this->sql, array($this->pkValue))->getRow();
		$this->onRowLoad($this->row);
		
		$tab = $this->getTab(); 
		if (! is_null($tab)) { 
			$tab->refrescar(); 
		}
		
	}
	
	/**
	 * Carga inicial del registro (la primera vez). 
	 * 
	 * @return unknown_type
	 */
	public function load()
	{
		if (is_null ($this->pkValue)) {
			$this->pkValue = $_REQUEST[$this->param];
		}
		
		$this->row = Database::query($this->sql, array($this->pkValue))->getRow();	
		$this->onRowLoad($this->row);

		if (! is_null($this->getCampoTitulo())) { 
			$this->setTitulo ($this->row[$this->getCampoTitulo()]);
		}
		
		$tab = $this->getTab(); 
		if (! is_null($tab)) { 
			$tab->refrescar(); 
		}
	}
	
	/**
	 * Se deja disponible este punto para implementar lógica que tenga en cuenta
	 * datos del registro una vez que éste se haya cargado. 
	 * 
	 */
	public function onRowLoad($row)
	{}
	
	
	public function addCol(Column $col)
	{
		if (count($this->grupos) == 0) {
			$this->addGrupo(new ListaColumns());
		}
		$this->grupos[count($this->grupos)-1]->add($col); 
	}
	
	public function addSubtitulo($titulo, $posicion = null)
	{
		$this->addGrupo(new ListaColumns($titulo), $posicion);
	}
	
	/**
	 * Agrega un grupo de campos a mostrar.
	 * @param GrupoCammpos $grupo Grupo de campos a agregar.  
	 */
	public function addGrupo (ListaColumns $grupo, $posicion = NULL) {
		if (! is_null($posicion))
			$grupo->setPosicion($posicion);
		$this->grupos[] = $grupo; 
	}

	public function addTab($tab, $permiso = null) {
		if (! is_null($permiso)) {
			if (! Seguridad::tieneHabilitado($permiso)) 
				return;   
		} 
		$tab->setScreen($this);
		$this->tabs[] = $tab; 
	}
	
	public function getTab() {
		if (count($this->tabs) == 0)
			return NULL;
		
		return $this->tabs[$this->currentTab];
	}
	
	public function getTabCount() {
		return count($this->tabs);
	}
	
	/**
	 * Agrega una acción a nivel de pantalla (opera sobre el registro que se está visualizando). 
	 * Si se especifica $permiso, la acción sólo está disponible si el usuario logueado
	 * tiene el permiso referido. 
	 * 
	 * @param Action $action El objeto de tipo Action a agregar
	 * @param string $permiso (Opcional) Permiso que debe tener el usuario para acceder a esta acción
	 * @return void
	 */
	public function addAction(Action $action, $permiso = NULL) {
		if (! is_null($permiso)) {
			if (! Seguridad::tieneHabilitado($permiso)) 
				return;   
		} 
		$this->acciones[] = $action; 
	}
	
	public function clearActions() {
		$this->acciones = array();
	}
	public function clearGrupos() {
		$this->grupos = array(); 
	}
	public function clearTabs() {
		$this->tabs = array();
	}
	
	//---- getters && setters ----//
	
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
	}
	public function getParam() {
		return $this->param; 
	}
	public function setParam($param) {
		$this->param = $param; 
	}
	public function getTipo() {
		return $this->tipo; 
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo; 
	}
	public function getGrupos() {
		return $this->grupos; 
	}
	public function setGrupos($grupos) {
		$this->grupos = $grupos; 
	}
	public function getPkValue() {
		return $this->pkValue; 
	}
	public function setPkValue($pkValue) {
		$this->pkValue = $pkValue;
	}
	public function getRow() {
		return $this->row; 
	}
	public function setRow($row) {
		$this->row = $row; 
	}
	public function getTabs() {
		return $this->tabs; 
	}
	public function setTabs($tabs) {
		$this->tabs = $tabs; 
	}
	/**
	 * @return int El nro. de tab seleccionado (0-based). 
	 */
	public function getCurrentTab() {
		return $this->currentTab; 
	}
	/**
	 * Setea el nro. de tab activo (0-based).
	 */
	public function setCurrentTab($currentTab) {
		$this->currentTab = $currentTab; 
	}
	public function getPosiciones() {
		return $this->posiciones; 
	}
	public function setPosiciones($posiciones) {
		$this->posiciones = $posiciones; 
	}
	public function getAcciones() {
		return $this->acciones; 
	}
	public function getMsgInfo() {
		return $this->msgInfo; 
	}		
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function setCampoTitulo($campoTitulo) {
		$this->campoTitulo = $campoTitulo; 
	}
	public function getCampoTitulo() {
		return $this->campoTitulo; 
	}
}