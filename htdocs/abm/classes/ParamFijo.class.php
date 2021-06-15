<?php
/**
 * @package abm
 */

/**
 * Clase burda de parámetro que siempre tiene un valor fijo. 
 * @package abm
 */
class ParamFijo extends Parametro
{
	private $value; 
	
	
	public function __construct ($txt, $value) 
	{
		parent::__construct($txt);
		$this->value = $value; 
	}

	public function getValue($input) 
	{
		return $this->value; 
	}
	
}