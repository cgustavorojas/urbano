<?php
/**
 * @package common
 * @author 
 */

/**
 * Encapsula la lógica de hacer un UPDATE en la base de datos. 
 * En lugar de estar escribiendo el SQL a mano, esta clase permite en forma OO, setear propiedades
 * como la tabla a modificar, ir agregando los campos y los valores y finalmente llamar a execute(). 
 * 
 * @property string $tabla	Nombre de tabla sobre la que hacer UPDATE
 * @property string	$campo	Nombre del campo que es clave primaria de la tabla
 * @property mixed  $pkValue Valor de la primary key
 */
class UpdateStmt extends ParamStmt
{
	private $tabla; 
	private $campo; 
	private $pkValue; 
	
	public function __construct ($tabla, $pkCol, $pkValue = null)
	{
		$this->tabla = $tabla; 
		$this->pkCol = $pkCol; 	
		$this->pkValue = $pkValue; 
	}
	
	/**
	 * Ejecuta el UPDATE-
	 * En caso de error, lanza una excepción.
	 */
	public function execute()
	{
		$tabla = $this->tabla; 
		$campos = $this->getCampos();
		$arr_campos = $this->getCampos();
		$sql = "UPDATE $tabla SET ";
		$pkCol = $this->getPkCol();
		
		$i = 1;
		for ( ; $i <= count($campos) ; $i++) 
		{
			if ($i > 1)
				$sql = $sql . ", ";
				
			$arr_campos[$i-1] = ':'.$arr_campos[$i-1];	
			$sql = $sql . $campos[$i-1] . ' = ' . $arr_campos[$i-1];  
		}
		
		$arr_campos[$i-1] = ":".$pkCol;
		
		$sql = $sql . " WHERE $pkCol = :".$pkCol;
		 
		$valores = $this->getValores(); 
		$valores[] = $this->getPkValue(); 

		try {//var_dump($sql);var_dump($valores);var_dump( $arr_campos);die;
			var_dump( $arr_campos);
			Database::execute($sql, $valores,$arr_campos,'U');
		} catch (Exception $e) {
			$this->handleException ($e); 
		}
	}
	
	//---------------- getters && setters -------------------------//

	public function getPkValue() {
		return $this->pkValue; 
	}
	public function setPkValue($pkValue) {
		$this->pkValue = $pkValue; 
	}
	public function getPkCol() {
		return $this->pkCol; 
	}
	public function setPkCol($pkCol) {
		$this->pkCol = $pkCol; 
	}
	public function getTabla() {
		return $this->tabla; 
	}
	public function setTabla ($tabla) {
		$this->tabla = $tabla; 
	}
}