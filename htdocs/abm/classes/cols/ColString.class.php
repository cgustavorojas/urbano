<?php
/**
 * @package abm
 */

/**
 * Tipo particular de columna para cadenas de texto
 *  
 * @package abm 
 *
 */
class ColString extends Column
{
	
	public function __construct ($txt, $campo = NULL, $maxLength = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo (Tipo::STRING);
		$this->setAlign (Column::LEFT); 
		$this->setPrintWidth(20);
		$this->setMaxLength($maxLength);
	}
	
}