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
class InputComboSql extends InputCombo
{
	private $sql; 
	private $params; 
	
	
	public function __construct ($txt, $campo, $sql, $tipo = Tipo::NUMBER, $showKey = false,$multiple='')
	{
		parent::__construct($txt, $campo, array(), $tipo, $showKey,$multiple);
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