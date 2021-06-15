<?php
/**
 * @package abm
 */

/**
 * Tipo particular de pantalla ViewScreen que con pocos parámetros arma una pantalla a partir de una tabla.
 * 
 * Cada subclase de ViewScreen debería definir una pantalla específica, donde se definen el query a realizar, 
 * las columnas a mostrar, etc. En estas pantallas, el método init() define los pormenores de la pantalla 
 * específica. 
 * 
 * La pantalla QuickView es una pantalla genérica que, basándose en ViewScreen, arma el SQL y las columnas
 * a mostrar en base a 3 parámetros que recibe en el request: 
 * 
 * Tabla: define la tabla a consultar
 * Pk: define cuál es la clave primaria de la tabla
 * Campos (opcional): Qué campos de la tabla mostrar
 * 
 * 
 * @package abm
 */
class QuickView extends ViewScreen
{
	private $campos; 
	
	public function init()
	{
		$this->setTitulo ("Detalles del ítem");
		
		$tabla = $_REQUEST['tabla'];
		$campo = $_REQUEST['pk'];

		if (isset($_REQUEST['campos'])) {
			$this->campos = $_REQUEST['campos'];
		}
		
		$this->setSql("SELECT * FROM $tabla WHERE $campo = \?");
		$this->setPkValue($_REQUEST['id']);
	}
	
	/**
	 * Dado un nombre de un campo, devuelve un string "más bonito" para mostrar al usuario. 
	 * Reemplaza guiones bajos por espacios y pone en mayúsculas la primer letra de cada palabra.
	 *  
	 * @param $name Nombre del campo
	 * @return string Nombre del campo listo para mostrar al usuario
	 */
	public function fieldNameToString($name)
	{
		$s = str_replace("_", " ", $name);
		return ucwords(strtolower($s));
	}
	
	/**
	 * Reemplaza la lógica básica de ViewScreen que sólo carga el registro para
	 * cargar el registro y en base a los campos devueltos, armar los objetos Column y agregarlos 
	 * a la pantalla. 
	 */
	public function load()
	{
		$rs = Database::query($this->getSql(), array ($this->getPkValue()));
		$row = $rs->getRow();
		$this->setRow($row);
		
		$aux = "," . str_replace(" ", "", $this->campos) . ",";	// me aseguro que siempre hay una coma adelante y atrás para hacer la búsqueda directa
		
		$g = new ListaColumns();
		
		for ($i = 0 ; $i < $rs->getNumFields() ; $i++)
		{
			$campo = $rs->getFieldName($i);
			
			// no muestro los campos que son NULL
			if (! is_null($row[$campo]))
			{
				
				// O no seleccionó una lista de campos, o el campo en cuestión está en la lista
				// de campos seleccionados
				if (is_null($this->campos) || (strpos($aux, "," . $campo . ",") !== false))
				{
					$type = $rs->getFieldType ($i);
					$txt  = $this->fieldNameToString($campo);
					
					if (strpos($type, "int") !== false) {
						$g->add (new ColNumber($txt, $campo));
					} else if (strpos($type,"date") !== false) {
						$g->add (new ColDate($txt, $campo));					
					} else {
						$g->add (new ColString($txt, $campo));
					}
				}
			} 
		}
		$this->addGrupo($g);
	}
}