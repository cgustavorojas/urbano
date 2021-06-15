<?php
/**
 * @package abm
 */

/**
 * Filtro que no pide datos al usuario sino que saca un valor del request (get, post) o de la session.
 * 
 * Lo que hace es tomar el dato del entorno al momento de inicialización, y lo mantiene mientras
 * viva. No actualiza su valor en cada request con parseRequest(). Util, por ejemplo, para tomar
 * el valor de una variable global como "ejercicio". O para recibir parámetros desde otra pantalla.
 * 
 * Seteando algunas propiedades, se puede modificar ligeramente su comportamiento para 
 * mostrar el valor filtrado por pantalla, por ejemplo o para tomar 2 datos del ambiente: una 
 * clave y un valor a mostrar al usuario. 
 * 
 * @package abm 
 */
class FiltroRequest extends Filtro
{
	
	private $variable;
	private $scope = Scope::ALL;
	private $default;  
	

	public function __construct ($campo, $variable = null, $tipo = Tipo::STRING, $scope = Scope::ALL)
	{
		parent::__construct();
		$this->setCampo($campo); 
		$this->setTipo($tipo);
		$this->variable = is_null($variable) ? $campo : $variable; 
		$this->scope = $scope; 		
		$this->setPrintable(false);	
	}

	public function init()
	{
		parent::init(); 
		
		$v = Scope::get($this->variable, $this->scope); 
		
		if (is_null($v)) 
			$this->setValue($this->default);
		else
			$this->setValue($v);			
	}

		
	public function getHtml()
	{
		$html = "";
		
		if ($this->isPrintable())
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
	
}