<?php
/**
 * @package abm
 */

/**
 * Control de ingreso de datos de tipo decimal. 
 * 
 * @package abm 
 */

class InputDecimal extends InputTextBox
{
	private $minimo; 
	private $maximo;
	private $allowZero = true;  
	
	public function validar()
	{
		if (! parent::validar()) 
			return false;
			
		$value = $this->getValue();
		$minimo = $this->getMinimo(); 
		$maximo = $this->getMaximo(); 
		
		if (! is_null($value) ) {
			if ( ! is_numeric($value) ) {
				$this->setError('debe ser numérico');
				return false; 
			}
			if (! $this->isAllowZero() && $value == 0) {
				$this->setError('no puede ser cero');
				return false; 
			}
			if (! is_null($minimo) && $value < $minimo) { 
				$this->setError('debe ser mayor o igual a ' . $minimo);
				return false; 
			}
			if (! is_null ($maximo) && $value > $maximo) {
				$this->setError ('debe ser menor o igual a ' . $maximo);
				return false; 
			}			
		}
		return true;	
	}
	
	//---- getters && setters ----//
	
	public function getMinimo() {
		return $this->minimo; 
	}
	public function setMinimo($minimo) {
		$this->minimo = $minimo; 
	}
	public function getMaximo() {
		return $this->maximo; 
	}
	public function setMaximo($maximo) {
		$this->maximo = $maximo; 
	}
	public function setAllowZero($v = true) {
		$this->allowZero = $v; 
	}
	public function isAllowZero() {
		return $this->allowZero; 
	}
}