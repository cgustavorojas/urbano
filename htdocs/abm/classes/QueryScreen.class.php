<?php
/**
 * @package abm
 */

/**
 * Clase que encapsula la lógica y los datos asociados a una pantalla de consulta
 * de registros, formada por un sector de filtros y una grilla de resultados. 
 * 
 * @property string			$sql		Sentencia SQL para generar la consulta. Debe tener en algún lugar el keyword #FILTROS# que será reemplazado por los filtros en caliente.
 * @property ListaFiltros	$filtros	Objeto ListaFiltros que mantiene la lista de filtros que tendrá la pantalla.
 * @property ListaColumns	$cols		Objeto ListaColumns que mantiene la lista de columnas para la grilla.
 * @property array		$accionesItem	Lista de acciones que se ejecutarán sobre los ítems de la grilla (íconos a nivel de registro).
 * @property array			$acciones	Lista de acciones que se ejecutarán a nivel de la pantalla completa.
 * @property int			$pageSize	Cantidad de líneas por pantalla (parámetro preferencia.q_page_size).
 * @property string		$printPageSize	Tamaño de la página para impresión en PDF (formato: 999x999 (ancho por alto, en milímitros)).
 * @property string		$printFont		Font para impresión de registros (debe ser monospace). Default: courier. 
 * @property int		$printFontSize	Tamaño el font para impresión de registros en pdf. Default: 8. 
 * @property array		$orders			Lista de posibles ORDER BY para el query. Array asociativo, la clave es la descripción, el valor la lista de campos.
 * @property string		$orderBy		Cláusula orderBy seleccionada (es un ítem de $orders)
 * @property string		$msgEmpty		El mensaje a mostrar cuando no hay ningún registro (default: "No hay datos para mostrar")
 * @property string		$msgInfo		Mensaje a mostrar arriba de los FILTROS a título informativo 
 * @property int		$rowCount		Cantidad de registros (para el total del query, no para la página actual).
 * @property bool		$btnRefrescar	Si mostrar o no el botón Refrescar
 * @property bool		$btnRefrescar	Si mostrar o no el botón Exportar
 * @property bool		$btnRefrescar	Si mostrar o no el botón Imprimir
 * @property bool		$btnRefrescar	Si mostrar o no el botón Configurar 
 * 
 * 
 * @package abm 
 */
class QueryScreen extends Screen
{
	//-----------
	// Propiedades
	//
	private $sql;
	private $filtros;
	private $cols;
	private $accionesItem = array();
	private $acciones = array();
	private $pageSize;
	private $printPageSize;
	private $printFont = 'courier';
	private $printFontSize = 8;   
	private $orders;
	private $orderBy; 
	private $msgEmpty = "No hay datos para mostrar";
	private $msgInfo; 	
	private $rowCount = 0; 
	private $sqlDinamico;
	private $btnRefrescar  = true;
	private $btnExportar   = true;
	private $btnImprimir   = true;
	private $btnConfigurar = true;
	private $btnPageSize   = true; 
	private $totales; 
	private $campoMultiSelect; 		// nombre del campo para selección múltiple
	private $autoFilter    = true;	// si es FALSE, toda la parte de filtros automáticos se deshabilita
	//
	//-----------
	
	
	/** Página actual (1-based) */
	private $currentPage = 1; 

	/** Lista de registros seleccionados */ 
	private $selected = array(); 
	
	public function isMultiSelect() {
		return ! is_null($this->campoMultiSelect);
	}
	public function getCampoMultiSelect() {
		return $this->campoMultiSelect; 
	}
	public function setCampoMultiSelect($campoMultiSelect) {
		$this->campoMultiSelect = $campoMultiSelect; 
	}
	public function select($ids) {
		$arr = explode(',',$ids);
		foreach ($arr as $id) {
			if (($id != '') && ! in_array($id, $this->selected))
				$this->selected[] = $id;
		} 
	}
	public function unselect($ids) {
		$arr = explode(',',$ids);
		foreach ($arr as $id) {
			if (in_array($id, $this->selected))
				unset ($this->selected[array_search($id, $this->selected)]);
		}
	}
	public function isSelected($id) {
		return in_array($id, $this->selected); 
	}
	public function unselectAll() {
		$this->selected = array();
	} 
	public function getSelected() {
		return $this->selected;
	}
	
	function __construct() {
		parent::__construct(); 
		$this->filtros = new ListaFiltros();
		$this->cols = new ListaColumns();
		$this->orders = array();
		$this->totales = array();
		$id = $this->getId(); 
		$this->setReturnUrl("abm/query.php?cmd=return&screenid=$id");
	}
	
	public function init() 
	{
		if (is_null($this->getTitulo()) && isset($_REQUEST['_titulo'])) {
			$this->setTitulo ($_REQUEST['_titulo']);
		}
		
		if (is_null($this->pageSize)) {
			$this->pageSize = $this->getUserPref('q_page_size', $this->pageSize);
		}
		if (is_null($this->printPageSize)) {
			$this->printPageSize = $this->getUserPref('q_print_page_size', $this->printPageSize);
		}

		$this->filtros->init();
		$this->filtros->setFocusToFirstOne();
		
		if (count($this->orders) > 0) {
			$aux = array_values($this->orders);
			$this->orderBy = $aux[0]; // obtengo el 1er. valor de la lista 
		}
		parent::init();
	}
	

	public function getPageSizes()
	{
		$curr = $this->getPageSize(); 
		$a = array();

		$a[10] = 10;
		$a[30] = 30;
		$a[50] = 50;
		$a[100] = 100;
		$a[200] = 200;
		$a[1000] = 1000;
		
		if (! in_array($curr, $a)) { 
			$a[$curr] = $curr; 
		}
		
		asort($a, SORT_NUMERIC);	// los ordeno 
		
		return $a; 
	}
	
	public function refrescar()
	{
		$this->filtros->refrescar(); 
	}
	
	public function validar()
	{
		return $this->filtros->validar();
	}
	
	public function parseRequest() 
	{
		$this->filtros->parseRequest();
		
		if (isset($_POST['orderBy'])) {
			$this->orderBy = $_POST['orderBy'];
		}
		if (isset($_POST['pageSize'])) {
			$this->pageSize = $_POST['pageSize'];
		}
		$this->validar();
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
		
		if (! $this->validar()) {
			$this->sqlDinamico = null;
			return null;
		}

		if ($this->isAutoFilter())
		{
			$clause = $this->getFiltros()->getWhereClause();
			
			if (strpos ($sql, '#FILTROS#') === false) {
				$sql = $sql . ' WHERE ' . $clause; 
			} else {
				$sql = str_replace ("#FILTROS#", $clause, $sql);
			}
	
			if (! is_null($this->orderBy))
			{
				if (strpos ($sql, '#ORDERBY#') === false) {
					//$sql = $sql . ' ORDER BY ' . $this->orderBy; 
				} else {
					$sql = str_replace ("#ORDERBY#", '', $sql);
				}
			}
		}

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
	 * Adicionalmente, saca los totales pedidos por el usuario con addTotal().    
	 */
	public function countRows()
	{
		$sqld = $this->sqlDinamico; 
		
		if (is_null($sqld)) {	// si no hay SQL, no tengo cómo contar.
			$this->rowCount = 0;   
			return; 
		}
		
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
			
		if ($paginar) {
			$limit = $this->getPageSize(); 
			$offset = $this->getPageSize() * ($this->getCurrentPage() - 1);
			
			if (!$limit){
				$limit = 20;
			}
	
			$orderBy = '';
			
			if ($this->orderBy != ''){
				$orderBy = ' ORDER BY '.$this->orderBy; 
			}
			//var_dump($orderBy);
			// evito mandar un "OFFSET 0" que puede no ser válido y es lo mismo que no especificar offset
			$offset = $offset > 0  ? "OFFSET $offset" : ''; 
			$sql = 'SELECT * 
					FROM (' . $this->sqlDinamico . ") a 
					$orderBy
					LIMIT $limit $offset";
		} else {
			$sql = $this->sqlDinamico; 
		}
		//var_dump($sql);
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
	
	/**
	 * Agrega una opción más de ordenamiento de registros. 
	 * 
	 * @param string $txt Leyenda a mostrar al usuario (ej: "Código")
	 * @param string $campos Lista de campos con sintaxis ORDER BY (ej: apellido, nombre, fecha desc)
	 * @return void
	 */
	public function addOrder ($txt, $campos)
	{
		$this->orders[$txt] = $campos;
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
	
	/**
	 * Atajo para agregar una columna, pero poniéndola por default inactiva.
	 * @param Column $col Objeto columna a agregar
	 */
	public function addColInactive(Column $col) {
		$col->setActive(false);
		$this->cols->add($col);
	}
	
	
	/**
	 * Toma como base una consulta ya ejecutada y arma columnas por defecto para cada columna
	 * de la consulta.  
	 * 
	 * @param DatabaseResult $rs
	 */
	public function addDefaultColumns($rs)
	{
		if (is_null($rs)) {
			return; 
		}
		$nf = $rs->getNumFields();
		for ($i = 0 ; $i < $nf ; $i++)
		{
			$ft = Tipo::fromSqlType($rs->getFieldType($i));
			$fn = $rs->getFieldName($i);

			switch ($ft)
			{
				case Tipo::STRING:		$this->addCol (new ColString($fn, $fn)); 
										break; 	
				case Tipo::NUMBER: 		$this->addCol (new ColNumber($fn, $fn));
										break; 	
				case Tipo::DATE: 		$this->addCol (new ColDate($fn, $fn));
										break; 	
				case Tipo::MONEY: 		$this->addCol (new ColMoney($fn, $fn));
										break; 	
				case Tipo::BOOLEAN: 	$this->addCol (new ColBoolean($fn, $fn));
										break; 	
				case Tipo::TIMESTAMP: 	$this->addCol (new ColTimestamp($fn, $fn));
										break; 	
			}
		}
	}
	
	//--- getters && setters ---//
	public function getOrderBy() {
		return $this->orderBy; 
	}
	public function setOrderBy($orderBy) {
		$this->orderBy = $orderBy; 
	}
	public function getOrders() {
		return $this->orders; 
	}
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
	public function addFiltro(Filtro $filtro, $posicion = null) {
		if (! is_null($posicion))
			$filtro->setPosicion($posicion);
		$this->filtros->add($filtro);
	}
	public function addCol(Column $col) {
		$this->cols->add($col);
	}
	public function addCols($arrayOfColumn) {
		foreach ($arrayOfColumn as $col)
			$this->cols->add($col);
	}
	public function getPageSize() {
		return $this->pageSize; 
	}
	public function setPageSize($pageSize) {
		$this->pageSize = $pageSize;
	}

	public function getCurrentPage() {
		return $this->currentPage;
	}
	public function setCurrentPage($currentPage) {
		$this->currentPage = $currentPage; 
	}
	/**
	 * 
	 * @return ListaColumns
	 */
	public function getCols() {
		return $this->cols; 
	}
	public function setCols(ListaColumns $cols)
	{
		$this->cols = $cols; 
	}
	public function getPrintPageSize() {
		return $this->printPageSize;
	}
	public function setPrintPageSize($printPageSize) {
		$this->printPageSize = $printPageSize;
	}
	public function getAcciones() {
		return $this->acciones; 
	}
	public function getAccionesItem() {
		return $this->accionesItem;
	}
	public function getPrintFont() {
		return $this->printFont; 
	}	
	public function setPrintFont($printFont) {
		$this->printFont = $printFont; 
	}
	public function getPrintFontSize() {
		return $this->printFontSize; 
	}
	public function setPrintFontSize($printFontSize) {
		$this->printFontSize = $printFontSize; 
	}
	public function getMsgEmpty() {
		return $this->msgEmpty; 
	}
	public function setMsgEmpty($msgEmpty) {
		$this->msgEmpty = $msgEmpty; 
	}
	public function getMsgInfo() {
		return $this->msgInfo; 
	}
	public function setMsgInfo($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	public function getRowCount() {
		return $this->rowCount; 
	}
	public function getPageCount() {
		
		if ($this->pageSize == 0){
			$this->pageSize = 20;
		}
		return ceil($this->rowCount / $this->pageSize); 
	}
	public function setRowCount($rowCount) {
		$this->rowCount = $rowCount; 
	}
	public function getBtnRefrescar() {
		return $this->btnRefrescar; 
	}
	public function setBtnRefrescar ($mostrar = true) {
		$this->btnRefrescar = $mostrar; 
	}
	public function getBtnExportar() {
		return $this->btnExportar; 
	}
	public function setBtnExportar ($mostrar = true) {
		$this->btnExportar = $mostrar; 
	}
	public function getBtnImprimir() {
		return $this->btnImprimir; 
	}
	public function setBtnImprimir ($mostrar = true) {
		$this->btnImprimir = $mostrar; 
	}
	public function getBtnConfigurar() {
		return $this->btnConfigurar; 
	}
	public function setBtnConfigurar ($mostrar = true) {
		$this->btnConfigurar = $mostrar; 
	}
	public function getBtnPageSize() {
		return $this->btnPageSize;
	}
	public function setBtnPageSize($mostrar = true) {
		$this->btnPageSize = $mostrar; 
	}
	public function getTotales() {
		return $this->totales;
	}
	public function isAutoFilter() {
		return $this->autoFilter; 
	}
	public function setAutoFilter($autoFilter = true) {
		$this->autoFilter = $autoFilter; 
	}
}
