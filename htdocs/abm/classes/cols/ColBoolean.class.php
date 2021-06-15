<?php
/**
 * @package abm
 */

/**
 * Tipo específico de columna para campos de tipo boolean.
 * @package abm
 */
class ColBoolean extends Column
{
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo(Tipo::BOOLEAN);
		$this->setAlign(Column::CENTER);
		$this->setPrintWidth(5);
	}
}

