<?php
/**
 * @package abm
 */

/**
 * Lista de filtros dentro de una pantalla de tipo QueryScreen.
 * 
 * La lista de filtros es la lista de inputs que se pedirán al usuario
 * para filtrar los resultados que se mostrarán en la grilla. Esta clase
 * mantiene una lista de objetos de tipo Filtro. Cada subclase de ésta
 * tiene la lógica para un tipo determinado de filtro: string, numérico, etc.
 * 
 * La mayor parte de las funciones de esta clase lo único que hacen es pasar
 * la acción en cuestión a cada uno de los filtros que componen la lista. 
 * 
 * @package abm 
 * @subpackage filtros
 */
class ListaFiltros
{
	private $lista = array();
	
	public function getAll() 
	{
		return $this->lista; 
	}
	
	/**
	 * Devuelve el filtro pedido, ya sea por su ID o su orden dentro de la lista
	 * @param $idOrOrden
	 * @return Filtro
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
	 * Setea el flag del filtro pedido para que se le de foco al mostrar la pantalla.
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
			//if ($i->isSelectable() && $i->isEnabled() && ! $i->isHidden() && ! $i->isReadOnly()) {
			if ($i->isSelectable() && $i->isEnabled()) {
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
	
	public function add(Filtro $filtro) 
	{
		$this->lista[] = $filtro; 
	}
	
	/**
	 * Pasa la acción de parseRequest() a todos los filtros.
	 * @return void
	 */
	public function parseRequest()
	{
		foreach ($this->lista as $f) {
			if ($f->isActive())
				$f->parseRequest(); 
		}	
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
			if ($f->isActive())
				$ok  = ($ok && $f->validar());
		}
		return $ok;
	}
	
	
	/**
	 * Pasa la acción de refrescar() a todos los filtros.
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
	public function getHtml($posicion = null)
	{
		$html = "";
		foreach ($this->lista as $f) {
			if ($f->isActive() && $f->isPrintable() && (is_null($posicion) || $f->getPosicion() == $posicion)) { 
				$html = $html . "\n" . $f->getHtml(); 
			}
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
			if ($f->isActive())
				$js = $js . "\n" . $f->getJavaScript(); 
		}	
		return $js; 
	}
	
	/**
	 * Arma una cláusula WHERE dinámica en base al valor de los filtros. 
	 * La cláusula es de la forma "1=1 AND xxxx AND yyyy AND zzzz" donde
	 * xxxx, yyyy, zzzz son las cláusula where de cada filtro individual. 
	 * @return string Cláusula where (ej: 1=1 AND ejercicio = 2009 AND inciso = 1)
	 */
	public function getWhereClause()
	{
		$sql = "1=1 "; 
		
		foreach ($this->lista as $f) {
			if ($f->isActive())
				$sql = $sql . $f->getWhereClause(); 
		}
		
		return $sql; 
	}
	
	/**
	 * Devuelve la cantidad de ítems en la lista. 
	 * @return integer
	 */
	public function getCount() {
		return count($this->lista); 
	}
	
	/**
	 * Devuelve un array con los filtros activos que tienen algún valor seteado. 
	 * La clave del array es la leyenda del filtro (txt) y el valor es el valor seteado.
	 * @return array Lista de filtros (clave/valor) 
	 */
	public function getPrintableValues()
	{
		$arr = array();
		foreach ($this->lista as $f)
		{
			if ($f->isActive() && $f->isPrintable() && ! is_null($f->getValue())) {
				$arr [$f->getTxt()] = $f->getValueTxt();				
			}
		}
		return $arr;
	}
	
	/**
	 * Devuelve cuántas columnas hacen falta mostrar todos los filtros. 
	 * @return integer Cantidad de columnas 
	 */
	public function getPosiciones() 
	{
		$ret = 1; 	
		foreach ($this->lista as $f) { 
			if ($f->getPosicion() > $ret)
				$ret = $f->getPosicion(); 
		} 
		return $ret; 
	}	

}
