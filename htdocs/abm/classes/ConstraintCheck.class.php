<?php
/**
 * @package abm
 */

/**
 * Tipo de validación simple que valida que el registro a eliminar cumpla
 * con cierto criterio (del propio registro). 
 * Ejemplo: campo1 = 'abc'
 * @package abm
 */
class ConstraintCheck extends Constraint
{
	public function validar(DeleteScreen $deleteScreen)
	{
		$tabla = $deleteScreen->getTabla(); 
		$campo = $deleteScreen->getCampo(); 
		$condicion = $this->getCondicion(); 
		$pk = $deleteScreen->getPkValue();
			
		$sql = "SELECT COUNT(*) FROM $tabla WHERE $campo = \? AND $condicion";
		
		$count = Database::simpleQuery($sql, array($pk));
		
		return ($count == 1);	
	}
}