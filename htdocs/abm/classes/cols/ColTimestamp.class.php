<?php
/**
 * @package abm
 */

/**
 * Tipo particular de columna para fechas con Horas y Minutos.
 *  
 * @package abm 
 *
 */
class ColTimestamp extends ColDate
{
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo (Tipo::TIMESTAMP);
		$this->setAlign (Column::CENTER); 
		$this->setPrintWidth(16);
	}
}