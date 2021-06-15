<?php
/**
 * @package abm
 */

/**
 * Maneja un array de objetos Input
 * 
 * La mayor parte de las funciones de esta clase lo único que hacen es pasar
 * la acción en cuestión a cada uno de los objetos de la lista 
 * @package abm 
 * @subpackage inputs
 */
class ListaInputs
{
	private $lista = array();
	
	public function getAll() 
	{
		return $this->lista; 
	}
	
	/**
	 * Devuelve el objeto pedido, ya sea por su ID o su orden dentro de la lista
	 * @param $idOrOrden
	 * @return Input
	 */
	public function get($idOrOrden) 
	{
		if (is_numeric($idOrOrden)) {
			return $this->lista[$idOrOrden];
		} else {
			foreach ($this->lista as $f) {
				if ($f->getId() == $idOrOrden) {
					return $f; 
				}
			}
		}
		return NULL; // si llegué hasta acá, el filtro pedido no existe
	}
	
	/**
	 * Setea el flag del input pedido para que se le de foco al mostrar la pantalla.
	 * @param $idOrOrden
	 * @return void
	 */
	public function setFocus($idOrOrden)
	{
		foreach ($this->lista as $f) {
			$f->setFocus(false); 
		}
		$this->get($idOrOrden)->setFocus();
	}

	/**
	 * Setea el foco en el primer control de la lista que sea candidato.
	 * Candidato es aquel que está habilitado, no está oculto y no es read-only. 
	 * @return void
	 */
	public function setFocusToFirstOne()
	{
		foreach ($this->lista as $i)
		{
			if ($i->isSelectable() && $i->isEnabled() && ! $i->isHidden() && ! $i->isReadOnly()) {
				$i->setFocus();
				break;
			}
		}
	}
	
	/**
	 * Devuelve valor del input pedido, ya sea por su ID o su orden dentro de la lista. 
	 * Idéntico a hacer get($idOrOrden)->getValue();
	 * 
	 * @param $idOrOrden
	 * @return unknown_type
	 */
	public function getValue($idOrOrden)
	{
		$obj = $this->get($idOrOrden);
		if (is_null($obj)) {
			return null;
		} else {
			return $obj->getValue(); 
		}
	}
	
	public function add(Input $input) 
	{
		$this->lista[] = $input; 
	}
	
	/**
	 * Pasa la acción de parseRequest() a todos los objetos de la lista.
	 * @return void
	 */
	public function parseRequest()
	{
		foreach ($this->lista as $f) {
			if ($f->isEnabled() && ! $f->isHidden()) 
				$f->parseRequest(); 
		}	
	}
	
	/**
	 * Pasa la acción de parseRow() a todos los objetos de la lista.
	 * @param $row Registro de la base, resultado de Base->getRow()
	 * @return void
	 */
	public function parseRow($row)
	{
		foreach ($this->lista as $input) {
			$input->parseRow($row); 
		}
		
	}
	
	/**
	 * Pasa la acción fillParamter() a todos los objetos de la lista. 
	 * @param ParamStmt $stmt Una sentencia de tipo ParamStmt (ej: InsertStmt, UpdateStmt)
	 * @return void
	 */
	public function fillParameter($stmt)
	{
		foreach ($this->lista as $input) {
			if (!$input->isReadOnly()) {$input->fillParameter($stmt);}
		}
	}
	
	/**
	 * Devuelve true si alguno de los inputs tiene algún valor nuevo.
	 */
	public function isChanged() {
		foreach ($this->lista as $input) {
			if ($input->isChanged())
				return true; 
		}
		return false;
	}

	/**
	 * Devuelve un string con la lista de inputs en formato id = valor; 
	 * @param bool $showNotChanged Si es false, sólo incluye aquellos inputs a los que el usuario le cambió el valor
	 * @param bool $showNulls Si es false, no muestra aquellos inputs cuyo valor sea NULL
	 */
	public function toString($showNotChanged = false, $showNulls = false)
	{
		$txt = ''; 
		$sep = ''; 
		foreach ($this->lista as $i) 
		{
			if (($i->isChanged() || $showNotChanged) 
					&& (! is_null($i->getValue()) || $showNulls)) {
				$txt = $txt . $sep . $i->getId() . '=' . $i->getValue();
				$sep = ', ';
			} 	
		}
		return $txt;
	}
	
	
	/**
	 * Pasa la acción de init() a todos los filtros.
	 * @return void
	 */
	public function init() 
	{
		foreach ($this->lista as $f) {
			$f->init();  
		}	
	}
	
	public function validar() 
	{
		$ok = true; 
		foreach ($this->lista as $f) {
			if (! $f->isHidden())
				$ok  = ($ok && $f->validar());
		}
		return $ok;
		
	}
	
	/**
	 * Verifica que ninguno de los INPUTS tenga errores de validación
	 * 
	 * @return bool 
	 */
	public function isValid()
	{
		foreach ($this->lista as $f) {
			if ( !$f->isValid()) 
				return false; 
		}
		return true; 
	}
	
	/**
	 * Pasa la acción de refrescar() a todos los objetos de la lista.
	 * @return void
	 */
	public function refrescar() 
	{
		foreach ($this->lista as $f) {
			$f->refrescar($this->lista); 
		}	
	}
	
	/**
	 * Arma el código HTML para todos los filtros.
	 * @return string Código HTML
	 */
	public function getHtml()
	{
		$html = "";
		foreach ($this->lista as $f) {
			$html = $html . "\n" . $f->getHtml(); 
		}	
		return $html; 
	}

	/**
	 * Arma el código javascript para todos los filtros.
	 * @return string Código JavaScript
	 */
	public function getJavaScript()
	{
		$js = "";
		foreach ($this->lista as $f) {
			$js = $js . "\n" . $f->getJavaScript(); 
		}	
		return $js; 
	}
	
	/**
	 * Devuelve la cantidad de ítems en la lista. 
	 * @return integer
	 */
	public function getCount() 
	{
		return count($this->lista); 
	}
	
	/**
	 * Pasa la acción de setDefault() a todos los filtros.
	 * @return void
	 */
	public function initDefault() 
	{
		foreach ($this->lista as $f) {
			$f->initDefault();  
		}	
	}
	
	
}
