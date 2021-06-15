<?php
/**
 * @package abm
 */

/**
 * Tipo particular de INPUT que tiene un valor fijo fijado en el código
 * y que no pide ningún dato al usuario. A diferencia de InputReadOnly, 
 * este input sí participa en el insert. 
 *
 * @package
 */
class InputHidden extends Input
{
	public function __construct ($campo, $valor, $tipo = Tipo::STRING)
	{
		parent::__construct();
		$this->setCampo($campo);
		$this->setTipo($tipo);
		$this->setValue ($valor);
		$this->setHidden(true);
		$this->setReadOnly (false); 
	}
	
}