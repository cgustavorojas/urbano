<?php
/**
 * @package abm
 */

/**
 * Enumeración de constantes para tipos de datos
 *
 * @package abm 
 */
class Tipo
{
	const STRING    = "String";
	const NUMBER    = "Number";
	const MONEY     = "Money";
	const DATE      = "Date";
	const BOOLEAN   = "Boolean";
	const TIMESTAMP = "Timestamp";
	
	/**
	 * Convierte un tipo devuelto por la base de datos (ej: int4, timestamp, etc.)
	 * a uno de los tipos pre-definidos de arriba. 
	 */
	public static function fromSqlType($type)
	{
		
		if (strpos($type,'char') !== false) {
				return Tipo::STRING;
		} else if (strpos($type, 'int') !== false) {
				return Tipo::NUMBER;  
		} else if (strpos($type,'float') !== false || strpos($type, 'numeric') !== false) {
				return Tipo::MONEY;
		} else if (strpos($type,'timestamp') !== false) {
				return Tipo::TIMESTAMP;
		} else if (strpos($type,'date') !== false) {
				return Tipo::DATE;
		} else if (strpos($type,'int') !== false) {
				return Tipo::BOOLEAN;
		} 
		return null;	
	}
}