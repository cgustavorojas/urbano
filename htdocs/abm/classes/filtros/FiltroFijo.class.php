<?php
/**
 * @package abm
 */

/**
 * Filtro que no pide datos al usuario sino que simplemente tiene un valor fijo para filtrar. 
 * 
 * Es casi como poner la claúsula WHERE dentro de la sentencia SQL, pero permite más flexibilidad
 * en el código cuando el valor fijo viene sacado de algún otro lado o para contemplar el caso de
 * NULL. 
 * 
 * @package abm 
 */
class FiltroFijo extends Filtro
{
	public function __construct ($campo, $valor, $tipo = Tipo::STRING)
	{
		parent::__construct();
		$this->setCampo($campo); 
		$this->setTipo($tipo);
		$this->setValue($valor);
		$this->setPrintable(false);	
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
}