<?php
/**
 * @package abm
 */

/**
 * Tipo específico de columna para datos de tipo moneda. 
 * 
 * @package abm 
 */
class ColMoney extends Column
{
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo(Tipo::MONEY);
		$this->setAlign(Column::RIGHT);
		$this->setPrintWidth(15);
	}
	
}