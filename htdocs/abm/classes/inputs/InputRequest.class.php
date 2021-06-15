<?php
/**
 * @package abm
 */

/**
 * Un Input que no toma un valor del usuario sino que toma un valor de un parámetro del Request. 
 * Es inherentemente ReadOnly, pero no setea el parámetro ReadOnly porque eso implicaría que
 * no participa del UPDATE. Sí participa, solo que no le pide datos al usuario sino que es un valor fijo
 * que recibió como parámetro de otro lado. 
 * 
 * @package abm
 */
class InputRequest extends Input
{
	private $variable;
	private $scope = Scope::ALL;
	private $default;  
	private $sql; 
	

	public function __construct ($campo, $variable = null, $tipo = Tipo::NUMBER, $scope = Scope::ALL, $sql = NULL)
	{
		parent::__construct();
		$this->setCampo($campo); 
		$this->setTipo($tipo);
		$this->variable = is_null($variable) ? $campo : $variable; 
		$this->scope = $scope; 		
		$this->setHidden(true);
		$this->setEnabled(false);	
		$this->sql = $sql; 
	}

	public function setValue($value)
	{
		parent::setValue($value); 
		if ( ! is_null($value) && ! is_null($this->sql)) 
			$this->setValueTxt (Database::simpleQuery($this->sql, array($value)));
	}
	
	public function parseRequest()
	{
		// no tengo que hace nada (no llamar a parent::parseRequest()) porque el valor
		// no se actualiza!! (es fijo y se setea en init().
	}
	
	public function init()
	{
		parent::init(); 
		
		$v = Scope::get($this->variable, $this->scope); 
		
		if (is_null($v)) { 
			$this->setValue($this->default);
		} else {
			$this->setValue($v);
		}			
	}

		
	public function getHtml()
	{
		$html = "";
		
		if (! $this->isHidden())
		{	
			$html = "<tr><td class='label'>" . $this->getTxt() . "</td><td class='input'>";
			$html = $html . $this->getValueTxt();
			$html = $html . "</td></tr>";
		}  
		return $html;
	}

	
	
	//---- getters && setters ----//
	
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
	public function getDefault() {
		return $this->default; 
	}
	public function setDefault($default) {
		$this->default = $default; 
	}
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
	}
		
}