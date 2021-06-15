<?php
/**
 * @package abm
 */

/**
 * Tipo específico de ViewTab que muestra una consulta SQL asociada al registro principal. 
 * 
 * La asociación al registro principal se puede hacer de 2 formas: 
 *   Si se especifica "campo", se reemplaza en el query ? por el valor de dicho campo (ViewScreen::getRow() [campo])
 *   Si no se especifica "campo", se reemplaza en el query ? por el valor de la primary key (ViewScreen::getPkValue())
 * 
 * Como todas las clases de tipo ViewTab, tiene además de la definición de la clase, un archivo template con 
 * el código HTML a mostrar, dentro de la subcarpeta "views". En este caso, hay 2 archivo:
 * 
 * 	view-tabquery.php		: es llamado desde view-main.php para mostrar el TAB junto con el resto de la pantalla View
 *  view-tabquery-ajax.php	: refresca únicamente el pedacito del grid. Atiende llamadas Ajax a través de la acción view_tabquery.php
 * 
 * @package abm
 */
class TabQuery extends ViewTab
{
	//---------------------
	// Propiedades
	//
	private $sql; 
	private $cols;
	private $totales = array();
	private $filtros;  
	private $accionesItem = array(); 
	private $acciones = array();
	private $pageSize = 20;	
	private $msgEmpty = "No hay datos para mostrar";
	private $rowCount = 0; 	
	private $sqlDinamico;	
	private $msgInfo; 
	private $cssNotEmpty;
	private $cssEmpty; 
	
	private $campo;
	private $tipo; 
	private $orderBy;
	
	//
	//----------------------
	
	/** Página actual (1-based) */
	private $currentPage = 1; 

	function __construct($titulo) {
		parent::__construct($titulo); 
		$this->filtros = new ListaFiltros();
		$this->cols = new ListaColumns();
		$this->setViewToInclude('view-tabquery.php');
		$this->tipo = Tipo::NUMBER;
	}	
	
	public function init() 
	{
		parent::init(); 
		$this->filtros->init(); 
	}
	
	public function refrescar()
	{
		$this->filtros->refrescar();

		$this->buildDinamicSql(); 
		$this->countRows();
		
		global $rs; 
		$rs = $this->executeQuery(true);
	}

	
	public function getCssForThisRow($row)
	{
		if (is_null($this->cssEmpty) && is_null($this->cssNotEmpty)) {
			return ''; 
		}
		
		$this->filtros->refrescar(); 
		$this->buildDinamicSql(); 
		$this->countRows(); 
		
		if ($this->getRowCount() > 0 && ! is_null($this->cssNotEmpty)) {
			return $this->cssNotEmpty; 
		}
		
		if ($this->getRowCount() <= 0 && ! is_null($this->cssEmpty)) {
			return $this->cssEmpty; 
		}
		
		return ''; 
	}
	
	public function parseRequest() 
	{
		$this->filtros->parseRequest();
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
		$clause = $this->getFiltros()->getWhereClause();
		
		$screen = $this->getScreen(); 

		if (is_null($this->getCampo())) {
			$sql = str_replace ('?', Utils::sqlEscape($screen->getPkValue(), $screen->getTipo()), $this->getSql());	
		} else {
			$row = $screen->getRow(); 
			$sql = str_replace ('?', Utils::sqlEscape($row[$this->getCampo()], $this->getTipo()), $this->getSql());
		}
		
		$sql = str_replace ("#FILTROS#", $clause, $sql);
		
		$this->sqlDinamico = $sql; 
		return $sql; 
	}

	/**
	 * Cuenta la cantidad total de registros que devuelve el query, 
	 * aplicando los filtros que haya puesto el usuario. 
	 * No toma en cuenta el paginado. El resultado de esta función
	 * dividido por la cantidad de registros por página da la cantidad
	 * de páginas disponibles.
	 * El query dinámico tiene que haber sido previamente generado con una llamada a buildDinamicSql(). 
	 * Además de devolver el valor, lo deja guardado en el atributo rowCount.   
	 */
	public function countRows()
	{
		if (is_null($this->sqlDinamico))	// si no hay SQL, no tengo cómo contar.  
			return; 
		
		$sqld = $this->sqlDinamico; 
			
		$sql = 'SELECT COUNT(*)'; 
		
		foreach ($this->totales as $total)
		{
			$campo = $total['campo'];
			$sql = $sql . ", SUM($campo) ";
		}
			
		$sql = $sql . " FROM ($sqld) a";

		$rs = Database::query ($sql)->getRowWithIndexes();
		
		$this->rowCount = $rs[0];
		
		for ($i = 0 ; $i < count($this->totales) ; $i++) {
			$this->totales[$i]['valor'] = $rs[$i+1];
		}

		$this->rowCount = Database::simpleQuery($sql);
		
	}

	
	/**
	 * Ejecuta el query para devolver los registros de la página actual.
	 * Si el parámetro paginar es false, en cambio se devuelve el query con 
	 * todos los registros. 
	 * 
	 * @param boolean $paginar True para devolver 1 página de registros, false para devolver todos. 
	 * @return DbResult Los registros de la página actual (o de todo el query si $paginar == false)
	 */
	public function executeQuery($paginar = false)
	{
		if (is_null($this->sqlDinamico))
			return null;

		$order_by = "";
		if ($this->getOrderBy()!=''){
			
			$order_by = $this->getOrderBy();
		}
			
			
		if ($paginar) {
			$limit = $this->getPageSize(); 
			$offset = $this->getPageSize() * ($this->getCurrentPage() - 1);
	
			// evito mandar un "OFFSET 0" que puede no ser válido y es lo mismo que no especificar offset
			$offset = $offset > 0  ? "OFFSET $offset" : ''; 
			$sql = 'SELECT * FROM (' . $this->sqlDinamico . ") a $order_by LIMIT $limit $offset";
		} else {
			$sql = $this->sqlDinamico.' '.$order_by; 
		}
		
		return Database::query($sql);
	}


	/**
	 * Agrega una acción a nivel de pantalla (no opera sobre un registro en particular). 
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
	
	/**
	 * Agrega una acción a nivel de registro. 
	 * Si se especifica $permiso, la acción sólo está disponible si el usuario logueado
	 * tiene el permiso referido. 
	 * 
	 * @param Action $action El objeto de tipo Action a agregar
	 * @param string $permiso (Opcional) Permiso que debe tener el usuario para acceder a esta acción
	 * @return void
	 */
	public function addActionItem(Action $action, $permiso = NULL) {
		if (! is_null($permiso)) {
			if (! Seguridad::tieneHabilitado($permiso)) 
				return;   
		} 
		$this->accionesItem[] = $action; 
	} 
	
	//--- getters && setters ---//
	
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql;
	}
	public function getFiltros() {
		return $this->filtros;
	}
	public function setFiltros(ListaFiltros $filtros) {
		$this->filtros = $filtros;
	}
	public function addFiltro(Filtro $filtro) {
		$this->filtros->add($filtro);
	}
	public function addCol(Column $col) {
		$this->cols->add($col);
	}
	public function addCols($arrayOfColumn) {
		foreach ($arrayOfColumn as $col)
			$this->cols->add($col);
	}
	
	/**
	 * Agrega un total a calcular sobre la totalidad de los registros abarcados por los filtros (no sólo
	 * los que están visibles en la página actual). 
	 * Se calculan al presionar Refrescar, en el mismo momento en que se cuenta la cantidad de registros. 
	 * 
	 * @param string $txt Leyenda a mostrar al usuario (ej: "Total de las facturas")
	 * @param string $campo Campo a totalizar (ej: "importe")
	 */
	public function addTotal($txt, $campo)
	{
		$this->totales[] = array ('campo' => $campo, 'txt' => $txt, 'valor' => null);			
	}
	
	public function getTotales() {
		return $this->totales;
	}
	
	public function getCurrentPage() {
		return $this->currentPage;
	}
	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage; 
	}
	public function getCols() {
		return $this->cols; 
	}
	public function getAcciones() {
		return $this->acciones; 
	}
	public function setAcciones($acciones) {
		$this->acciones = $acciones; 
	}
	public function getAccionesItem() {
		return $this->accionesItem; 
	}
	public function setAccionesItem($accionesItem) {
		$this->accionesItem = $accionesItem; 
	}
	public function getPageSize() {
		return $this->pageSize; 
	}
	public function setPageSize($pageSize) {
		$this->pageSize = $pageSize;
	}
	public function getMsgEmpty() {
		return $this->msgEmpty; 
	}
	public function setMsgEmpty($msgEmpty) {
		$this->msgEmpty = $msgEmpty; 
	}
	public function getRowCount() {
		return $this->rowCount; 
	}
	public function getPageCount() {
		return ceil($this->rowCount / $this->pageSize); 
	}
	public function setRowCount($rowCount) {
		$this->rowCount = $rowCount; 
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
	public function getMsgInfo() {
		return $this->msgInfo; 
	}
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function setCssEmpty($cssEmpty) {
		$this->cssEmpty = $cssEmpty; 
	}	
	public function getCssEmpty() {
		return $this->cssEmpty; 
	}
	public function setCssNotEmpty($cssNotEmpty) {
		$this->cssNotEmpty = $cssNotEmpty; 
	}
	public function getCssNotEmpty() {
		return $this->cssNotEmpty; 
	}
	public function setOrderBy($order){
		$this->orderBy = $order;
	}
	public function getOrderBy(){
		return $this->orderBy;
	}
	
	
}