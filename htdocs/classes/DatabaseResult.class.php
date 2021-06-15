<?php
/**
 * @package common
 * @author 
 */


/**
 * Encapsula un resultado de una consulta a la base de datos. 
 * 
 * @package common
 */
class DatabaseResult
{
	private $rs; 
	
	public function __construct ($rs)
	{
		$this->rs = $rs; 
	}

	/**
	 * El array asociativo devuelto tiene como índice el nombre de las columnas del query. 
	 * 
	 * @return array (asociativo) La próxima fila o FALSE si no hay más filas disponibles
	 */
	public function getRow()
	{
		if (!$this->rs){ return false; }
		else {
		return $this->rs->fetch(PDO::FETCH_LAZY);
		}
		
	}
	
	
	/**
	 * A diferencia de getRow() que devuelve un array asociativo únicamente con los nombres de las columnas, 
	 * esta función devuelve un array asociativo que tiene tanto los nombres como los índices (la primer columna es cero). 
	 *  
	 * @return array (asociativo) La próxima fila o FALSE si no hay más filas disponibles
	 */
	public function getRowWithIndexes()
	{
		if (!$this->rs) {return null;}
		
		return $this->rs->fetch(PDO::FETCH_NUM); 
	}
	
	
	/**
	 * @return int Cantidad de registros
	 */
	public function getRowCount()
	{
		if (!$this->rs) {return 0;}
		
		return $this->rs->rowCount(); 
	}
	
	public function getNumFields() 
	{
		if (!$this->rs) {return 0;}
		
		return $this->rs->columnCount();
	}
	
	public function getFieldName($i) 
	{
		$aux = $this->rs->getColumnMeta($i);
		return $aux['name']; 
	}

	/**
	 * Devuelve el tipo de datos del campo $i. 
	 * Ojo: el tipo de datos es el que informa directamente la base de datos (sin conversión). Cada base 
	 * de datos tiene distintos tipos y los llama en forma distinta. Para representación reducida de tipos,
	 * ver la clase Tipo y en particular Tipo::fromSqlType().  
	 * 
	 * @param int $i número de campo
	 * @return string	Nombre del tipo de datos (según la base de datos)
	 */
	public function getFieldType($i) 
	{
		return pg_field_type ($this->rs, $i); 
	}
	
}