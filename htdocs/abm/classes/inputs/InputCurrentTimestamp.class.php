<?php
/**
 * @package abm
 */

/**
 * Un Input oculto que asigna como valor fijo el timestamp actual.
 * 
 * Tiene algunos vueltas de tuerca medio raras para que funcione bien en pantallas de Update. Normalmente las pantallas
 * de update, sólo meten en la sentencia update los campos que el usuario modificó. Por ende, un campo oculto de tipo read-only
 * nunca iría en el update y en este caso se quiere que vaya, porque es util usarlo para identificar el momento en el que se hace 
 * la modificación.  
 * 
 * De forma similar, en la pantalla de edit en realidad no carga el valor actual que tiene el campo en el base, sino que carga
 * siempre el valor de la fecha/hora actual.  
 *   
 * @package abm
 */
class InputCurrentTimestamp extends Input
{
	public function __construct($campo = 'fecha')
	{
		parent::__construct();
		$this->setCampo($campo);
		$this->setHidden(true);
		$this->setTipo(Tipo::DATE);
		$this->setValue(Utils::now()); 
		$this->setEnabled(true);
		$this->setReadOnly(false); 		
	}
	
	public function isChanged() {
		return true; 
	}	

	public function parseRow($row) {}
}