<?php
/**
 * @package abm
 */

/**
 * Clase particular de InputTextBox para ingreso de números enteros. 
 * Se puede definir el rango (mínimo/máximo) además de los propios del
 * InputTextBox (maxlength, size, etc.). 
 * 
 * @package abm
 * @subpackage inputs
 */
class InputNumber extends InputTextBox
{
	private $minimo; 
	private $maximo; 
	
	
	public function validar()
	{
		if (! parent::validar()) 
			return false;
			
		$value = $this->getValue(); 
		if (! is_null($value) ) {
			if ( ! is_numeric($value) ) {
				$this->setError('debe ser numérico');
				return false; 
			}
			if ($value <> round($value)) {
				$this->setError('debe ser un número entero');
				return false; 
			}
			if (! is_null($this->minimo) && $value < $this->minimo) { 
				$this->setError('debe ser mayor o igual a ' . $this->minimo);
				return false; 
			}
			if (! is_null ($this->maximo) && $value > $this->maximo) {
				$this->setError ('debe ser menor o igual a ' . $this->maximo);
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
}