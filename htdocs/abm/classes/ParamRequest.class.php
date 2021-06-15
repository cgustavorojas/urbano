<?php
/**
 * @package abm
 */

/**
 * Tipo particular de Parametro que toma su valor de una variable del entorno.
 * El entorno puede ser: _POST, _GET o _SESSION 
 * 
 * @package abm 
 */
class ParamRequest extends Parametro
{
	private $variable; 
	private $scope;

	public function __construct($txt, $variable, $scope = Scope::ALL) 
	{
		parent::__construct($txt); 
		$this->variable = $variable; 
		$this->scope = $scope; 
	}
	
	/**
	 * @return string El valor del parámetro o un string vacío si no tiene ningún valor. 
	 */
	public function getValue($input) 
	{
		$value = Scope::get($this->variable, $this->scope); 
				
		return is_null($value) ? '' : $value; 
	}
		
	//---- getters && setters ----/ 
	
	public function getVariable() {
		return $this->variable; 
	}
	public function setVariable($variable) {
		$this->variable = $variable; 
	}
	public function getScope() {
		return $this->scope; 
	}
	public function setScope($scope) {
		$this->scope = $scope; 
	}
}