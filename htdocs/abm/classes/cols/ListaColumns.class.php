<?php
/**
 * @package abm
 */


/**
 * Mantiene una lista de columnas. 
 * Da un pequeño valor agregado sobre tener simplemente un array. 
 * 
 * @property string $titulo		No se usa siempre, permite definir un título para este conjunto de columnas. 
 * @property int	$posicion	No se usa siempre, permite definir una posición para este conjunto de columnas (respecto de otros
 * 								conjuntos de columnas). 
 * 
 * @package abm 
 * @subpackage columns
 */
class ListaColumns
{
	private $lista = array();
	private $titulo;  
	private $posicion = 1; 
	
	private $constraintColumn;
	private $constraintValue; 	
	
	public function __construct($titulo = NULL) {
		$this->titulo = $titulo; 
	}
	
	public function setConstraint ($column, $value = 't') 
	{
		$this->constraintColumn = $column; 
		$this->constraintValue = $value; 
	}

	public function isEnabledForThisRow($row)
	{
		if (is_null($this->constraintColumn)) {
			return true; 
		}
		
		$actualValue = $row[$this->constraintColumn];
		return ($actualValue == $this->constraintValue);
	}
		
	public function add (Column $col) { 
		$this->lista[] = $col; 
	}
	
	public function getAll() {
		return $this->lista; 
	}
	
	public function getCount() {
		return count($this->lista); 
	}
	
	public function getByTxt($txt) {
		foreach ($this->lista as $col) {
			if ($col->getTxt() == $txt)
				return $col; 
		}
		return NULL;
	}
	
	/**
	 * Devuelve la columna pedida, ya sea por su ID o su orden dentro de la lista
	 * Si no encuentra la columna por ID ni por Orden, lanza una exception.
	 * @param $idOrOrden
	 * @return Filtro
	 */
	public function get($idOrOrden) 
	{
		if (is_numeric($idOrOrden)) {
			return $this->lista[$idOrOrden];
		} else {
			foreach ($this->lista as $c) {
				if ($c->getId() == $idOrOrden) {
					return $c; 
				}
			}
		}
		throw new Exception ("ListaColumns::get() no encontró una columna con ID " . $idOrOrden);
	}

	public function getTitulo() {
		return $this->titulo; 
	}
	public function setTitulo($titulo) {
		$this->titulo = $titulo;
	}
	public function getPosicion() {
		return $this->posicion; 
	}
	public function setPosicion ($posicion) {
		$this->posicion = $posicion; 
	}
}