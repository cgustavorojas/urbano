<?php
/**
 * @package abm
 */

/**
 * Clase especializada de FiltroInput para manejar números.
 * 
 * @package abm 
 */
class FiltroNumber extends FiltroTextBox
{
	
	public function __construct($txt, $campo = NULL, $size = 5, $maxlength = NULL)
	{
		parent::__construct($txt, $campo, $size, $maxlength);
	}
		
	/**
	 * Valida el dato ingresado 
	 */
	public function validar()
	{
		$ok = parent::validar(); 
		
		if ($ok)
		{
			$value = $this->getValue(); 
			
			if (! is_null($value) && strlen(trim($value)) > 0 && ! is_numeric($this->getValue())) {
				$this->setError("debe ser numérico");
				$ok = false; 
			} 					
		}
		
		return $ok;  
	}
		
}