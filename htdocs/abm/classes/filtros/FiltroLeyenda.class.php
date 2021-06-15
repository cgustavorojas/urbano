<?php
/**
 * @package abm
 */

/**
 * Clase pavota de filtro que en realidad no filtra nada. 
 * Se usa para poder mostrar una leyenda al usuario en el lugar donde iría habitualmente
 * un filtro. 
 *
 * @package abm
 */
class FiltroLeyenda extends Filtro
{
	/**
	 * Crea un nuevo filtro. 
	 * @param string $txt Nombre del filtro
	 * @param string  $valor Valor a mostrar (leyenda)
	 * @return FiltroLeyenda
	 */
	public function __construct ($txt, $valor, $tipo = Tipo::STRING)
	{
		$this->setTxt($txt);
		$this->setValue($valor);
		$this->setValueTxt(UserPref::load()->toString($valor, $tipo));	
		$this->setPrintable(true);
		$this->setTipo($tipo);
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
		
	/**
	 * Al implementar un getWhereClause() en blanco, el filtro no participa del filtrado de registros.
	 */
	public function getWhereClause() {
		return "";
	}	
}