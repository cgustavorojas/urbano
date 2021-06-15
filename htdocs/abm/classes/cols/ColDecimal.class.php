<?php
/**
 * @package abm
 */

/**
 * Tipo específico de columna para datos de tipo decimal. 
 * 
 * @package abm 
 */
class ColDecimal extends Column
{
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo(Tipo::MONEY);		// TODO: definir un tipo específico con un formato específico
		$this->setAlign(Column::RIGHT);
		$this->setPrintWidth(15);
	}
	
}