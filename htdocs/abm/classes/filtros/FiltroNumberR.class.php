<?php
/**
 * @package abm
 */

/**
 * Clase especializada de FiltroInput para manejar números, pero con la particularidad que le permite
 * al usuario seleccionar rangos. Admite, por ejemplo: 
 *     "534"	=> el número debe ser igual a 534
 *     "<534"	=> el número debe ser menor a 534
 *     ">534"	=> el número debe ser mayor a 534
 *     "!534"	=> el número debe ser distinto de 534
 *     "534/600" =>  el número debe estar entre 534 y 600  <== NO IMPLEMENTADO TODAVIA
 * 
 * @package abm 
 */
class FiltroNumberR extends FiltroTextBox
{

	public function extrae_op($valor)
	{
		if (substr($valor,0,2) == '<=') {
			return '<=';	
		} else if (substr($valor,0,1) == '<') {
			return '<';
		} else if (substr($valor,0,2) == '>=') {
			return '>=';
		} else if (substr($valor,0,1) == '>') {
			return '>';
		} else if (substr($valor,0,1) == '!') {
			return '<>';
		} else if (substr($valor,0,2) == '<>') {
			return '<>';
		} else {
			return '=';
		}
	}
	
	public function extrae_num($valor)
	{
		$v = null;
		
		if (substr($valor,0,2) == '<=') {
			$v = substr($valor,2);	
		} else if (substr($valor,0,1) == '<') {
			$v = substr($valor,1);
		} else if (substr($valor,0,2) == '>=') {
			$v = substr($valor,2);
		} else if (substr($valor,0,1) == '>') {
			$v = substr($valor,1);
		} else if (substr($valor,0,1) == '!') {
			$v = substr($valor,1);
		} else {
			$v = $valor; 
		}
		return ($v == '') ? null : $v; 
	}
	
	/**
	 * Valida el dato ingresado 
	 */
	public function validar()
	{
		if (!parent::validar()) {
			return false;
		}
		
		$value = $this->getValue(); 

		$v = $this->extrae_num($value);
		
		if (is_null($v) && $this->isObligatorio()) {
			$this->setError ('debe especificar un valor');
			return false; 
		}
		
		if (! is_null($v) && strlen(trim($v)) > 0 && ! is_numeric($v)) {
			$this->setError("debe ser numérico");
			return false;  
		} 					
		
		return true; 
	}
		
	public function getWhereClause()
	{
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! $this->isAutoFilter()) {
			return ""; 
		}
		
		if (! is_null($this->getValue())) 
		{
			$value    = $this->extrae_num($this->getValue());
			
			if (! is_null($value)) 
			{
				$operador = $this->extrae_op($this->getValue()); 
				
				$campo = $this->getCampo(); 
		
				return " AND $campo $operador $value ";
			} 				
		}
		
		return "";
		
	}	
}