<?php
/**
 * @package abm
 */


/**
 * Un Input que no pregunta nada al usuario sino que agrega como campo el ID del usuario actualmente logueado.
 * 
 * Tiene algunos vueltas de tuerca medio raras para que funcione bien en pantallas de Update. Normalmente las pantallas
 * de update, sólo meten en la sentencia update los campos que el usuario modificó. Por ende, un campo oculto de tipo read-only
 * nunca iría en el update y en este caso se quiere que vaya, porque es util usarlo para identificar al usuario que hace 
 * la modificación.  
 * 
 * De forma similar, en la pantalla de edit en realidad no carga el valor actual que tiene el campo en el base, sino que carga
 * siempre el valor del usuario actualmente logueado. 
 *   
 * @package abm
 */
class InputCurrentUser extends Input
{
	public function __construct($campo = 'id_usuario')
	{
		parent::__construct();
		$this->setCampo($campo);
		$this->setHidden(true);
		$this->setTipo(Tipo::NUMBER);
		$this->setValue(Seguridad::getCurrentUserId());
		$this->setEnabled(true);
		$this->setReadOnly(false);
	}
	
	public function isChanged() {
		return true; 
	}
	
	public function parseRow($row) {}
}