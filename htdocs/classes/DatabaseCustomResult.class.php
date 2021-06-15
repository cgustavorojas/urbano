<?php
/**
 * @package common
 */

/**
 * Sobre la misma interfaz de DatabaseResult, que encapsula el resultado de una
 * consulta a la base de datos PostgreSQL, esta clase permite encapsular
 * cualquier resultado "formato tabla" (con filas, solumnas y encabezados). 
 * La idea es que un programa puede generar "a mano" un objeto de esta clase
 * con datos que vengan de cualquier lado y pasárselo a las clases o funciones
 * que esperan el resultado de una base de datos sin que éstas se enteren que los
 * datos vienen en realidad de otro lado. 
 *
 * @package common
 */
class DatabaseCustomResult extends DatabaseResult
{
	private $fields = array(); 
	private $types  = array(); 
	private $rows = array();
	private $currentRow = -1; 
	
	public function __construct()
	{
	}
	
	/**
	 * Permite armar la definición del modelo datos de este resultado, agregando de a una 
	 * columna por vez. Para cada columna se define el nombre y su tipo.
	 * 
	 * Ejemplo: $rs->addField('id_usuario', Tipo::NUMBER); 
	 * 
	 * @param string $name Nombre de la columna
	 * @param string $type Tipo de la columna (ver Tipo::STRING, Tipo::NUMBER, etc.)
	 */
	public function addField($name, $type)
	{
		$this->fields[] = $name; 
		$this->types[] = $type; 
	}
	
	/**
	 * Agrega una fila de datos al resultado. 
	 * 
	 * @param array $row Una fila. Debe ser un array asociativo con los nombres de las columnas.   
	 */
	public function addRow($row)
	{
		$this->rows[] = $row; 
	}
	
	/**
	 * Devuelve una fila de datos, en forma de array asociativo con el nombre de las columnas
	 * como clave.
	 * 
	 * @return array La próxima fila o FALSE si no hay más.  
	 */
	public function getRow()
	{
		$this->currentRow++; 
		if ($this->currentRow >= count($this->rows)) {
			$this->currentRow = -1;
			return false; 
		}
			
		return $this->rows[$this->currentRow];
	}
	
	/**
	 * Devuelve una fila de datos, en forma de array asociativo con el nombre de las columnas
	 * como clave (igual a getRow()) pero agregándole también el orden de las columnas 
	 * como clave también (la primer columna es cero). 
	 * 
	 * De esta forma, con esta función se puede mostrar la primer columnas así:  
	 * 
	 * $r = $rs->getRow(); 
	 * echo $r[0];
	 * 
	 * que con getRow() no se puede (habría que saber el nombre de la primer columna). 
	 * 
	 * @return array La próxima fila o FALSE si no hay más. 
	 */
	public function getRowWithIndexes()
	{
		$this->currentRow++; 
		if ($this->currentRow >= count($this->rows)) {
			$this->currentRow = -1; 
			return false; 
		}
		
		$r = $this->rows[$this->currentRow];
		$fields = $this->fields; 
		$count = count($fields); 
		
		for ($i = 0 ; $i < $count ; $i++)
		{
			$r[$i] = $r[$fields[$i]];
		}
		return $r; 
	}
	
	/**
	 * @return int La cantidad de filas.
	 * @see classes/DatabaseResult#getRowCount() 
	 */
	public function getRowCount()
	{
		return count($this->rows);	
	}

	/**
	 * @return int La cantidad de columnas (campos).
	 * @see classes/DatabaseResult#getNumFields()
	 */
	public function getNumFields()
	{
		return count($this->fields); 	
	}
	
	/**
	 * @param int $i Nro. de columna, empezando en cero. 
	 * @return string El nombre de un campo dada su posición (0-based). 
	 * @see classes/DatabaseResult#getFieldName($i)
	 */
	public function getFieldName($i)
	{
		return $this->fields[$i];
	}
	
	/**
	 * @param int $i Nro. de columna, empezando en cero. 
	 * @return int Tipo de la columna dada (ver clase Tipo). 
	 * @see classes/DatabaseResult#getFieldType($i)
	 */
	public function getFieldType($i)
	{
		return $this->types[$i];	
	}
	
	/**
	 * Permite pasar el conjunto de filas y columnas completa en formato array. 
	 * El array debe tener 2 dimensiones: la primera por filas y la segunda, asociativa, por columnas. 
	 * Ejemplo: $rows[0]['campo1']
	 * 
	 * @param array $rows Conjunto de filas y columnas
	 */
	public function setRows($rows)
	{
		$this->rows = $rows;		
	}
	
}