<?php
/**
 * @package common
 * @author 
 */

/**
 * Encapsula la lógica para hacer un INSERT en la base de datos. 
 * En lugar de estar escribiendo el SQL a mano, esta clase permite en forma OO, setear propiedades
 * como la tabla donde insertar, ir agregando los campos y los valores y finalmente llamar a execute(). 
 * 
 * @property string	$tabla	Nombre de la tabla
 * 
 * @package common
 */
class InsertStmt extends ParamStmt
{
	private $tabla;
	private $serialCol; 
	private $serialId;

	
	public function __construct ($tabla) {
		$this->tabla = $tabla; 
	}
	
	
	/**
	 * Ejecuta el INSERT.
	 * En caso de error, lanza una excepción. 
	 *  
	 */
	public function execute()
	{
		$tabla = $this->tabla; 
		$campos = implode (',', $this->getCampos());
		$placeholders = "";
		$arr_campos = $this->getCampos();
		
		for ($i = 1 ; $i <= count($this->getCampos()) ; $i++) 
		{
			if ($i > 1)
				$placeholders = $placeholders . ',';
			$placeholders =  $placeholders .':'. $arr_campos[$i-1]; 
		}
		
		//Armo una sentencia del tipo: INSERT INTO mitabla (campo1, campo2, campo3) VALUES (?, $2, $3)
		$sql = "INSERT INTO $tabla ($campos) VALUES ($placeholders)"; 
		
	
		Database::execute($sql, $this->getValores(),$placeholders,'I');
		
		if (! is_null($this->serialCol)) {
			$seq = $tabla . '_' . $this->serialCol . '_seq';
			//$this->serialId = Database::simpleQuery("SELECT currval('$seq')");
			$this->serialId = Database::getSerialId();
		}
	}

	public function getSerialId() {
		return $this->serialId;
	}
	//----- getters && setters -----//
	
	public function getTabla() {
		return $this->tabla; 
	}
	public function setTabla($tabla) {
		$this->tabla = $tabla; 
	}
	public function getSerialCol() {
		return $this->serialCol; 
	}
	public function setSerialCol($serialCol) {
		$this->serialCol = $serialCol; 
	}
	
}