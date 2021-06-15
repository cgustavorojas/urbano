<?php

/**
 * Clase genérica de lista para ser extendida a gusto y piaccere. 
 * Permite agregar items de cualquier tipo a una lista (un array) y recorrerlo item a item o setear el puntero donde se desee. 
 * 
 * @author daw
 *
 */

class Lista {
	
	private $current = 0; 
	private $lista = array();

	public function __construct() {
		
	}
	
	/**
	 * Agrega un nuevo item al final de la lista.
	 * @param mixed $element	Item a agregar en la lista.
	 */
	
	public function add($element) {
		array_push($this->lista, $element);  
	}

	/**
	 * Elimina el elemento n de la lista siendo n de 0 a ($this->count()-1)
	 * @param $n
	 * @return unknown_type
	 */
	public function remove($n) {

		$tmp = array();

		for ( $i = 0 ; $i < $this->count() ; ++$i ) {
			if ($i >= $n) {
				if (($i+1) < $this->count()) { array_push($tmp, $this->lista[$i+1]); }
			} else { array_push($tmp,$this->lista[$i]);	}
		}
		$this->lista = $tmp;
	}
	
	/**
	 * Devuelve el primer item no recorrido de la lista.
	 * @return mixed
	 */

	public function next() {

		if (count($this->lista) == 0) { return null ; }  
		
		$current = $this->current; 
		$this->current++;
		
		if ($current >= count($this->lista)) { return null ; }
		
		return $this->lista[$current];
	}
	
	/**
	 * Devuelve la posición actual de puntero
	 * @return int
	 */
	
	public function getPointer() {
		return $this->current;
	}
	
	/**
	 * Posiciona el puntero en cualquier lugar (válido) de la lista. Si el valor que se pasa no es 
	 * válido o se va fuera de los límites de la lista sale de la funcion.
	 * @param int $value Posición que tomará el puntero dentro de la lista
	 */

	public function setPointer($value) {
		if (is_null($value) || !is_numeric($value) || $value >= count($this->lista) || $value < 0 ) { return ; }
		$this->current = $value ;  
	} 

	/**
	 * Devuelve la cantidad de elementos de la lista
	 * @return unknown_type
	 */

	public function count() {
		return count($this->lista);
	}
	
	/**
	 * Devuelve el primer elemento de la lista. Si la lista esta vacía devuelve null.
	 * @return mixed
	 */	

	public function begin() {
		if ($this->count() > 0) { return $this->lista[0]; } 
		else { return null; }
	}
	
	/**
	 * Devuelve el último elemento de la lista.Si la lista esta vacía devuelve null.
	 * @return mixed
	 */	

	public function end() {
		$ptr = ($this->count() - 1);
		if ($this->count() > 0 ) { return $this->lista[$ptr]; }
		else { return null; } 
	}
	
	/**
	 * Vacía la lista. 
	 * @return unknown_type
	 */

	public function clear() {
		$this->lista = array();
	}

	/**
	 * Devuelve true si la lista esta vacía.
	 * @return boolean
	 */
	public function isEmpty() { 
		return ((count($this->lista)==0)?true:false);
	}

	/**
	 * Devuelve el array de elementos de la lista. 
	 * @return array
	 */
	public function toArray() {
		return $this->lista;
	}
}