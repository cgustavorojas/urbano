<?php
/**
 * @package abm
 */

/**
 * Un tipo de filtro específico para columnas de tipo boolean
 *
 * @package abm
 */
class FiltroBoolean extends FiltroCombo
{
	public function __construct($txt, $campo = null, $defaultValue = null) {
		parent::__construct ($txt, $campo, array('t' => 'Sí', 'f' => 'No'), Tipo::BOOLEAN);
		$this->setValue($defaultValue);
	}
	
}