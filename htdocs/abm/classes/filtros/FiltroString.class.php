<?php
/**
 * @package abm
 */

/**
 * Clase especializada de FiltroInput para cadenas de texto. 
 * Permite pasar a mayúsculas o minúsculas o agregar porcientos
 * para usar operador LIKE.
 * 
 * @property bool	$toUpper	Si es TRUE, el valor ingresado por el usuario se pasa a mayúsculas antes de filtrar.
 * @property bool	$toLower	Si es TRUE, el valor ingresado por el usuario se pasa a minúsculas antes de filtrar.
 * @property string	$append		Texto arbitrario a agregar al final del ingresado por el usuario 
 * 								(útil para agregar "%" con operador LIKE
 * @property string	$prepend	Texto arbitrario a agregar al principio del ingresado por 
 * 								el usuario (útil para agregar "%" con operador LIKE  
 * 
 * @package abm 
 */
class FiltroString extends FiltroTextBox
{
	private $toUpper = false; 
	private $toLower = false; 
	private $append  = "";
	private $prepend = "";
	
	public function parseRequest() 
	{
		parent::parseRequest(); 
		
		$value = $this->getValue(); 
		
		if (! is_null($value))
		{	
			if ($this->toUpper) { 
				$this->setValue(strtoupper($value));
			}
			if ($this->toLower) { 
				$this->setValue(strtolower($value));
			}
		}
	}
	
	public function getWhereClause()
	{
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! is_null($this->getValue())) {
			
			$where = $this->getWhere(); 
			$value = Utils::sqlEscape($this->getPrepend() . $this->getValue() .  $this->getAppend(), $this->getTipo());
						
			if (is_null($where)) {
				$campo = $this->getCampo(); 
				$operador = $this->getOperador();
	
				return " AND $campo $operador $value "; 				
			} else {
				$where = str_replace('?', $value, $where);
				return "AND $where";
			}
		}
		
		return "";
		
	}

	
	// ---- getters && setters ---- // 
	
	public function isToUpper() {
		return $this->toUpper; 
	}
	public function setToUpper($toUpper = true) {
		$this->toUpper = $toUpper; 
		if ($toUpper)
			$this->toLower = false; 
	}
	public function isToLowerr() {
		return $this->toLower; 
	}
	public function setToLower($toLower = true) {
		$this->toLower = $toLower; 
		if ($toLower) 
			$this->toUpper = false; 
	}
	public function getAppend() {
		return $this->append; 
	}
	public function setAppend($append) {
		$this->append = $append; 
	}
	public function getPrepend() {
		return $this->prepend; 
	}
	public function setPrepend($prepend) {
		$this->prepend = $prepend; 
	}
}