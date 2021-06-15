<?php
/**
 * @package common
 * @author 
 */

/**
 * Case base para InsertStmt y UpdateStmt. 
 * Un ParamStmt es un objeto que encapsula una sentencia INSERT o UPDATE (lo que tienen en común es que 
 * llevan una lista de campos con valores) y permite ejecutar el SQL sin necesidad de escribir SQL sino
 * llenando propiedades y finalmente llamando a execute();
 *  
 * @package common
 */
class ParamStmt
{
	private $campos = array(); 
	private $valores = array();  
	private $tipos = array();
	private $errorMsgs = array(); 	
	
		
	/**
	 * Agrega un campo a la lista que formará parte de la sentencia. 
	 * 
	 * @param $campo Nombre el campo
	 * @param $valor El valor (puede ser NULL)
	 * @param $tipo Tipo de datos (ver clase Tipo)
	 */
	public function add ($campo, $valor, $tipo)
	{
		$this->campos[] = $campo;
		$this->valores[] = $valor;
		$this->tipos[] = $tipo;  
	}
	
	/**
	 * Ejecuta la sentencia SQL. Debe ser implementada en subclases.
	 * En caso de error lanza una excepción.  
	 */
	public function execute() {}
	
	/**
	 * Devuelve un string con la lista de parámetros.
	 * Usada primariamente en logs y funciones por el estilo. 
	 * Formato: campo1 = valor, campo2 = valor2, ... 
	 */	
	public function toString() 
	{
		$str = '';
		$sep = '';
		for ($i = 1 ; $i < count($this->campos) ; $i++)
		{
			$str .= $sep . $this->campos[$i] . '=' . $this->valores[$i]; 
			$sep = ', '; 
		}
		return $str;
	}
	
	/**
	 * Dada una exception que fue atrapada, se fija si existe algún mensaje de error customizado para reemplazar
	 * el mensaje default de la base de datos. De ser así, lo reemplaza. 
	 * En cualquier caso, vuelve a lanzar la exception para arriba. 
	 *  
	 * @param Exception $e
	 */
	protected function handleException($e)
	{
		$msg = $e->getMessage();
		foreach ($this->errorMsgs as $key => $value) {
			if (strpos(strtolower($msg), strtolower($key)) !== false) {
				throw new Exception ($value);
			}
		}
		throw($e); 
	}
	
	/**
	 * Agrega una descripción a un posible mensaje de error que devuelva la base. 
	 * 
	 * @param $msg Mensaje de error (o parte de él). Ej: 'violates foreign key'
	 * @param $descripcion Texto descriptivo. Ej: 'No se puede eliminar el registro porque existen registros asociados'
	 */
	public function addErrorMsg($msg, $descripcion)
	{
		$this->errorMsgs[$msg] = $descripcion;
	}
	
	//------ getters && setters ------//
	
	public function getCampos() {
		return $this->campos; 
	}
	public function getValores() {
		return $this->valores; 
	} 
	public function getTipos() {
		return $this->tipos; 
	}
	public function getErrorMsgs() {
		return $this->errorMsgs;
	}
	public function setErrorMsgs($errorMsgs) {
		$this->errorMsgs = $errorMsgs;
	}
	
}