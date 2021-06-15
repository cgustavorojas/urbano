<?php
/**
 * @package abm
 */

/**
 * Tipo particular de Filtro del estilo combo donde cada opción, en lugar de ese un valor
 * posible de una columna, es una cláusula where completa. 
 * Ejemplos: 
 *    "saldo > 0" => "Saldo positivo", 
 *    "ejecutado > credito" => "Excedidos en crédito"
 *
 * @package abm
 */
class FiltroComboCond extends FiltroCombo
{
	private $clausulas = array();
	private $leyendas = array();  
	
	public function __construct ($txt, $lista)
	{
		foreach ($lista as $key => $value) {
			$this->leyendas[] = $key; 
			$this->clausulas[] = $value; 
		}		
		parent::__construct($txt, Utils::toLowerNoBlanks($txt), $this->leyendas, Tipo::NUMBER); 
	}
	
	
	public function getWhereClause()
	{
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! is_null($this->getValue())) 
		{
			$where = $this->clausulas[$this->getValue()];		
			return " AND $where"; 				
		}
		
		return "";
	}
	
	
	
}