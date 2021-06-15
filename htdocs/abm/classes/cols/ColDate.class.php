<?php
/**
 * @package abm
 */

/**
 * Tipo particular de columna para fechas
 *  
 * @package abm 
 *
 */
class ColDate extends Column
{
	
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo (Tipo::DATE);
		$this->setAlign (Column::CENTER); 
		$this->setPrintWidth(10);
	}
	
}