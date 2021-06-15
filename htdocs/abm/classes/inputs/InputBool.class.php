<?php
/**
 * @package abm
 */


/**
 * Input específico para ingresar valores true/false.
 *
 * ATENCION: como el tipo boolean no es parte del standard ANSI SQL, tiene varios
 * problemas a la hora de su implementación correcta porque su interpretación es específica de 
 * PostgreSQL. En consecuencia, muchas veces es preferible definir la columna en la base
 * como integer, asumiendo por convención que 1=true y 0=false. 
 * 
 * Este control hace exactamente eso. Al usuario le muestra un check-box como si 
 * se tratara de un campo boolean, pero en la base en realidad está trabajando con 
 * un campo integer de valores posibles 1 ó 0. 
 * 
 * Para un control que trabaje con campos PostgreSQL "boolean", usar la clase InputBoolean.
 *  
 * @package abm 
 */
class InputBool extends InputCombo
{

	public function __construct ($txt, $campo = null, $obligatorio = false)
	{
		parent::__construct($txt, $campo, array (1 => 'Sí', 0 => 'No'), Tipo::NUMBER);
		$this->setObligatorio($obligatorio);
	}
}