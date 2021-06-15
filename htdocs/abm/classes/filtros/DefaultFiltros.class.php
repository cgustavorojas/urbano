<?php
/**
 * @package abm
 */

/**
 * Conjunto de métodos estáticos que devuelven filtros de tipos básicos
 * pero ya parametrizados para las configuraciones más comunes. 
 * 
 * Es una forma de escribir menos. No se hace nada que no se podría
 * hacer creando los objetos Filtro "a mano". 
 *
 * @package abm
 */
class DefaultFiltros
{
	
	/**
	 * Devuelve un FiltroString configurado para operador LIKE, con wilcard (%) adelante y atrás y 
	 * pasando el valor a ingresado a minúsculas. 
	 * 
	 * @param string $txt Leyenda a mostrar 
	 * @param string $campo Nombre del campo (default: la leyenda normalizada sin espacios y en minúsculas)
	 * @param bool $toLower Si pasar el texto ingresado a minúsculas - default: true
	 * @param bool $appendWildcard Si agregar % al final (default: true)
	 * @param bool $prependWilcard Si agregar % al principio (default: true)
	 * @return FiltroString
	 */
	public static function like ($txt, $campo = null, $toLower = true, $appendWildcard = true, $prependWildcard = true)
	{
		$f = new FiltroString ($txt, $campo, 30, 100);
		$f->setOperador ('LIKE');
		if ($toLower) $f->setToLower();
		if ($appendWildcard) $f->setAppend('%');
		if ($prependWildcard) $f->setPrepend('%');
		return $f; 		
	}
	
	public static function ilike ($txt, $campo = null, $appendWildcard = true, $prependWildcard = true)
	{
		$f = new FiltroString ($txt, $campo, 30, 100);
		$f->setOperador ('ILIKE');
		if ($appendWildcard) $f->setAppend('%');
		if ($prependWildcard) $f->setPrepend('%');
		return $f; 		
	}
	
	public static function estado ($campo = 'estado', $tabla)
	{
		$f = new FiltroComboSql ('Estado', $campo, "SELECT estado, txt FROM $tabla ORDER BY txt", Tipo::STRING);
		return $f; 
	}
	
}