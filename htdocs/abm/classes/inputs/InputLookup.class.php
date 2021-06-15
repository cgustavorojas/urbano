<?php
/**
 * @package abm
 */

/**
 * Input derivado de InputComboSql, que permite ingresar un campo seleccionando valores
 * desde una tabla de lookup.
 * Ejemplo: queremos ingresar un valor para el campo "pais" y hay que sacarlo de la tabla
 * "paises", haciendo "SELECT pais, txt FROM tb_pais ORDER BY txt". 
 * 
 * @package abm
 */
class InputLookup extends InputComboSql
{
	private $campoTxt; 
	private $tabla; 
	
	public function __construct ($txt, $campo, $tabla, $tipo = Tipo::NUMBER, $campoTxt = "txt", $showKey = false)
	{	
		parent::__construct($txt, $campo, null, $tipo);
		$this->campoTxt = $campoTxt;
		$this->tabla = $tabla;  
		$this->setShowKey($showKey);
	}
	
	public function refrescar($filtros) 
	{
		$campo    = $this->getCampo(); 
		$campoTxt = $this->getCampoTxt(); 
		$showKey  = $this->isShowKey(); 
		$tabla    = $this->getTabla(); 
		
		if ($showKey) 
			$sql = "SELECT $campo, $campoTxt FROM $tabla ORDER BY $campo";
		else
			$sql = "SELECT $campo, $campoTxt FROM $tabla ORDER BY $campoTxt";
			
		$this->setSql ($sql);
			
		parent::refrescar($filtros); 
	}
	
	public function getCampoTxt() {
		return $this->campoTxt; 
 	}
 	public function setCampoTxt($campoTxt) {
 		$this->campoTxt = $campoTxt; 
 	}
 	public function getTabla() {
 		return $this->tabla; 
 	}
 	public function setTabla($tabla) {
 		$this->tabla = $tabla; 
 	}
}