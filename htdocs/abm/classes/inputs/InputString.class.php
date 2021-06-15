<?php
/**
 * @package abm
 */

/**
 * Clase especializada de InputTextBox para cadenas de texto.
 * Sobre la funcionalidad básica de un InputTextBox, permite configurar para que el valor
 * ingresado se pase siempre a mayúsculas o a minúsculas. 
 * 
 * @property boolean	$toUpper	Si es true, el valor ingresado por el usuario se pasa siempre a mayúsculas.
 * @property boolean	$toLower	Si es true, el valor ingresado por el usuario se pasa siempre a minúsculas.
 * 
 * @package abm
 */
class InputString extends InputTextBox
{
	private $toUpper = false; 
	private $toLower = false;

	
	/**
	 * Después de la lógica normal de asignar al control el valor que vino en el request, 
	 * si corresponde pasa el valor a mayúsculas o minúsculas. 
	 */
	public function parseRequest() 
	{
		parent::parseRequest(); 
		
		if (! is_null($this->getValue()) && $this->toUpper) { 
			$this->setValue(strtoupper($this->getValue()));
			return; 
		}
		if (! is_null($this->getValue()) && $this->toLower) { 
			$this->setValue(strtolower($this->getValue()));
			return; 
		}
	}
	
	// ---- getters && setters ---- // 
	
	public function isToUpper() {
		return $this->toUpper; 
	}
	public function setToUpper($toUpper = true) {
		$this->toUpper = $toUpper; 
	}
	public function isToLowerr() {
		return $this->toLower; 
	}
	public function setToLower($toLower = true) {
		$this->toLower = $toLower; 
	}
}