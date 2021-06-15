<?php
/**
 * @package abm
 */

/**
 * Tipo muy básico de Input que es tipo read-only, solo muestra el valor pero no permite editarlo.
 * Si se especifica el parámetro opcional $sql, en lugar de mostrar directamente el valor, ejecuta
 * el query pasando el valor como parámetro, y muestra el resultado. El query debe devolver una
 * única fila y una única columna.   
 * Nota: este campo no se incluye en el insert.
 * @package abm 
 */
class InputReadOnly extends Input
{
	private $sql; 
	
	public function __construct($txt, $campo = NULL, $tipo = Tipo::STRING, $sql = NULL) {
		$this->setTxt($txt); 
		$this->setReadOnly(true);
		$this->setTipo($tipo); 
		$this->setCampo( is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo );
		$this->sql = $sql; 
	}
	
	public function setValue($value)
	{
		parent::setValue($value); 
		if (! is_null($value) && ! is_null($this->sql))
			$this->setValueTxt(Database::simpleQuery($this->sql, array($value)));
	}
		
	public function getHtml()
	{
		$html =  "<tr><td class='txt " . $this->getCssTxt() . "'>" . $this->getTxt() . "</td><td class='input " . $this->getCssInput() ."'>"; 
		$html = $html . $this->getValueTxt() . "</td></tr>";
		return $html;  
	}
	
	/**
	 * Siendo read-only, nunca puede tener errores
	 * @return true
	 */
	public function validar() {
		return true; 
	}
	
	/**
	 * Inhabilita el comportamiento default de la clase Input que es tomar el valor del request.
	 * Como este control es read-only, se queda con su valor inicial y no toma modificaciones.
	 */
	public function parseRequest() 
	{
		// No tengo que hacer nada, es read-only !
	}
	
	public function getSql() {
		return $this->sql; 
	}
	public function setSql($sql) {
		$this->sql = $sql; 
	}
	
}