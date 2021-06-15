<?php
/**
 * @package abm
 */

/**
 * Tipo particular de columna para datos numéricos.
 *  
 * @package abm 
 *
 */
class ColNumber extends Column
{
	
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo (Tipo::NUMBER);
		$this->setAlign (Column::CENTER); 
		$this->setPrintWidth(5);
	}
	
}