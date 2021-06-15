<?php
/**
 * @package abm
 */

/**
 * Input de tipo combo donde la lista de valores sale de una consulta SQL.
 * 
 * @property string	$sql	Consulta SQL para obtener la lista de valores. Debe retornar exactamente 2 columnas: la primera
 * 							es la clave y la segunda el valor para mostrar.  
 * @package abm
 */
class InputComboSqlMultiple extends InputComboMultiple
{
	private $sql;
	private $params;
		
	public function __construct ($txt, $campo, $sql, $size = 7, $tipo = Tipo::NUMBER, $showKey = false)
	{
		parent::__construct($txt, $campo, array(), $size, $tipo, $showKey);
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