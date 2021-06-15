<?php
/**
 * @package abm
 */

/**
 * Tipo particular de columna para datos con Horas y Minutos.
 *  
 * @package abm 
 *
 */
class ColTime extends Column
{
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo (Tipo::TIME);
		$this->setAlign (Column::CENTER); 
		$this->setPrintWidth(16);
	}
}