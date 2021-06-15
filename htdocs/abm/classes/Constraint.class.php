<?php
/**
 * @package abm
 */

/**
 * Un constraint es un chequeo que se debe cumplir para poder eliminar un registro.
 * Los constraint se agregan a las pantalla DeleteScreen y son verificados antes
 * de ejecutar la sentencia de eliminación. Si el constraint no se cumple, se muestra
 * un mensaje al usuario y el registro no se elimina. 
 *  
 * @package abm
 */
class Constraint
{
	private $condicion; 
	private $error; 

	public function __construct ($condicion, $error)
	{
		$this->condicion = $condicion; 
		$this->error = $error; 
	}
	
	/**
	 * Verifica si se cumple o no la condición. 
	 * @param DeleteScreen $deleteScreen La pantalla a la que pertenece este constraint
	 * @return bool true si pasó el chequeo, false si no. 
	 */
	public function validar(DeleteScreen $deleteScreen)
	{
		return true; 
	}
	
	//---- getters && setters ----//
	
	public function getCondicion() {
		return $this->condicion;
	}
	public function setCondicion($condicion) {
		$this->condicion = $condicion; 
	}
	public function getError() {
		return $this->error; 
	}
	public function setError($error) {
		$this->error = $error; 
	}
}