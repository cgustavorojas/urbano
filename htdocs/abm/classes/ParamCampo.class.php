<?php
/**
 * @package abm
 */

/**
 * Clase particular de Parametro que toma su valor de una columna del registro actual de una consulta SQL. 
 * @package abm 
 */
class ParamCampo extends Parametro
{
	private $campo;

	public function __construct($txt, $campo = NULL) 
	{
		parent::__construct($txt);
		if (is_null ($campo))
			$this->campo = Utils::toLowerNoBlanks($txt);
		else
			$this->campo = $campo; 
	}
	
	/**
	 * Devuelve el valor de la columna $campo del registro $input. 
	 *
	 * @param $input Registro actual
	 * @return string Valor del campo pedido
	 */
	public function getValue($input)
	{
		return $input[ $this->campo ];
	}
	
	//---- getters && setters ----//
		
	public function getCampo() {
		return $this->campo; 
	}
	public function setCampo($campo) {
		$this->campo = $campo; 
	}
}