<?php
/**
 * @package abm
 */

/**
 * Filtra que un campo de tipo fecha esté dentro de un cierto rango. 
 * Pide al usuario fecha desde / fecha hasta
 * @package abm
 */
class FiltroDateCompuesto extends Filtro
{
	private $fecha_desde;
	private $fecha_hasta;
	
	public function __construct($txt, $fecha_desde, $fecha_hasta)
	{
		parent::__construct();
		$this->setTxt($txt);
		$this->fecha_desde=$fecha_desde;
		$this->fecha_hasta=$fecha_hasta;
	}
	
	
	/**
	 * Arma la cláusula WHERE correspondiente a este filtro.
	 * Esta clase, al ser casi abstracta, ejecuta una lógica bastante general, tomando
	 * el caso de un campo, un operador y un valor, que puede ser string o numérico. 
	 * Clases descendientes pueden tener una lógica más elaborada.
	 * En el caso que el filtro tenga un valor ingresado inválido, no filtra nada (devuelve un string vacío).  
	 * 
	 * @return string con la cláusula. Ej: "ejercicio = 2009"
	 */
	public function getWhereClause()
	{
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! is_null($this->getValue())) {
			
			$where = $this->getWhere(); 
			$value = Utils::sqlEscape($this->getValue(), $this->getTipo());
			
			if (is_null($where)) {
				$campo = $this->getCampo(); 
				$operador = $this->getOperador();
	
				return " AND $campo $operador $value "; 				
			} else {
				$where = str_replace('?', $value, $where);
				return "AND $where";
			}
		}
		
		return "";
		
	}
	
	
}