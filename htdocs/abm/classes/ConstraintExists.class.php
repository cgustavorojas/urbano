<?php
/**
 * @package abm
 */

/**
 * Un constraint que chequea que existan registros asociados al registro
 * que se quiere borrar. 
 * 
 * @package abm
 */
class ConstraintExists extends Constraint
{
	public function validar(DeleteScreen $deleteScreen)
	{
		$condicion = $this->getCondicion();
		$pk 	   = $deleteScreen->getPkValue();
		
		$sql = "SELECT COUNT(*) FROM ($condicion) a";
		
		$count = Database::simpleQuery($sql, array ($pk));
		
		return ($count > 0);
	}
}