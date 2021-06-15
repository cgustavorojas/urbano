<?php
/**
 * @package abm
 */

/**
 * Filtro de tipo combo donde la lista de valores sale de una consulta SQL.
 * 
 * @property string	$sql	Consulta SQL para obtener la lista de valores. Debe retornar exactamente 2 columnas: la primera
 * 							es la clave y la segunda el valor para mostrar.  
 * @package abm
 */
class FiltroComboSql extends FiltroCombo
{
	private $sql;
	private $params;  
	
	/**
	 * @param $txt Leyenda a mostrar al usuario
	 * @param $campo Nombre del campo (o expresión) a filtrar
	 * @param $sql Sentencia SQL que debe devolver 2 columnas: la 1era. la clave, la 2da. la descripción
	 * @param $tipo Tipo de datos de la clave (default: Tipo::NUMBER)
	 * @return FiltroComboSql
	 */
	public function __construct ($txt, $campo, $sql, $tipo = Tipo::NUMBER)
	{
		parent::__construct($txt, $campo, array(), $tipo);
		$this->sql = $sql; 	
	}
	
	public function refrescar($filtros)
	{
		$rs = Database::query($this->sql, $this->params);

		$lista = array(); 
		 
		while ($row = $rs->getRowWithIndexes())
		{
			$lista[$row[0]] = $row[1];
		}
		
		$this->setlista($lista);
	}
	
	//---- getters && setters ----// 
	
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
	}
	public function getParams() {
		return $this->params; 
	}
	public function setParams($params) {
		$this->params = $params; 
	}
	
}