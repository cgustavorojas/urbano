<?php
/**
 * @package abm
 */

/**
 * Define un parámetro que formará parte de la URL de una acción. 
 * El parámetro tiene un nombre y el valor lo puede tomar de 
 * diversas fuentes: un valor fijo, una variable del entorno o 
 * una columna del registro actual en una consulta SQL.
 * Cada subclase es un tipo particular de parámetro.  
 * 
 * @package abm 
 */
class Parametro
{
	private $txt;

	public function __construct ($txt) 
	{
		$this->txt = $txt; 
	}
	
	/**
	 * Devuelve el valor del parámetro, que deberá ser calculado según el tipo de parámetro.
	 * Cada subclase de Parametro tiene una forma particular de obtener su valor a partir de la entrada 
	 * 
	 * @return string Valor del parámetro
	 */
	public function getValue($input) 
	{
		return "";
	}
	
	//---- getters && setters ----//
	
	public function getTxt() {
		return $this->txt; 
	}
	public function setTxt($txt) {
		$this->txt = $txt; 
	}


}