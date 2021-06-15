<?php
/**
 * @package abm
 */


/**
 * Una acción es un link que el usuario puede seleccionar, sea a nivel registro o a nivel pantalla. 
 * La acción define a dónde apuntar el link, que se forma a partir de valores fijos y 
 * valores variables tomados del entorno o del registro actual, usando los parámetros.  
 *
 * @package abm
 *
 */
class Action
{
	/** Etiqueta a mostrar al usuario */
	private $txt;
	/** Link a disparar si el usuario selecciona la acción (dinámicamente se le agregan los parámetros) */
	private $href;
	/** Nombre del archivo de imagen (sin ruta) */
	private $img;  
	/** Lista de objetos Parametro con los parámetros a pasar para formar href */
	private $params = array(); 
	/** Si es true, cierra la ventana actual antes de ejecutar la acción */
	private $closeScreen = false; 
	/** Clase CSS a agregar (no reemplaza las defaults) */
	private $cssClass = ''; 
	/** Ventana en la cual abrir la acción (si es NULL, pantalla actual) */
	private $target;
	/** Opciones para abrir la ventana nueva definida en target (ver parámetro 3 de la función js window.open() */ 
	private $targetOpts; 

	/** Si se especifica constraintColumn y constraintValue, la acción sólo estará disponible si la columna del registro actual tiene el valor
	 *  especificado (o *no* tiene el valor especificado, si constraintIgual = false)
	 */
	private $constraintColumn;
	private $constraintValue; 
	private $constraintIgual = true;

	private $enabled = true; 
	
	/** si se especifica, se pedirá al usuario una confirmación antes de ejecutar esta acción */
	private $msgConfirm; 
	/** si se especifica, a la URL llamada por esta acción se le pasarán los valores de los filtros de la pantalla actual */
	private $sendFiltros = false; 
	
	/**
	 * Crea una nueva acción
	 * @param string $txt Leyenda a mostrar al usuario
	 * @param string $href URL a la cual ir 
	 * @param string $img Ruta y archivo del ícono
	 * @param array $params Lista de objetos Parametro
	 * 
	 * @return Action
	 */
	public function __construct($txt, $href, $img = NULL, $params = array())
	{
		if (! is_null ($params)) {
			if (! is_array($params)) {
				$params = array($params);
			}
		}
		$this->txt = $txt; 
		$this->href = $href;
		$this->img = $img; 
		$this->params = $params; 	
	}
	
	/**
	 * Define una condición que debe cumplir el registro para que esta acción esté habilitada sobre el mismo. 
	 * La condición se da por un campo del registro que tiene que ser igual a un determinado valor o, en el 
	 * caso que $negative sea TRUE, que el campo NO sea igual a ese valor. 
	 * 
	 * @param string $column Nombre de la columna que debe cumplir la condición
	 * @param mixed $value Valor que debe tener la columna para que aplique la acción
	 * @param boolean $igual (default=TRUE) Si es FALSE, la condición es inversa y la columna NO tiene que ser igual al valor para habilitar la acción
	 */
	public function setConstraint ($column, $value = 't', $igual = true) 
	{
		$this->constraintColumn = $column; 
		$this->constraintValue = $value;
		$this->constraintIgual = $igual;  
	}

	public function isEnabledForThisRow($row)
	{
		if (!$this->isEnabled())
			return false; 
		
		if (is_null($this->constraintColumn)) {
			return true; 
		}
		
		$actualValue = $row[$this->constraintColumn];
		if ($this->constraintIgual)
			return ($actualValue == $this->constraintValue);
		else
			return ($actualValue != $this->constraintValue);
	}
	
	public function addParam(Parametro $param) 
	{
		$this->params[] = $param; 
	}
	

	/**
	 * Devuelve una URL que ya tiene formateados todos los parámetros. 
	 * 
	 * @param $input Dependiendo del tipo de acción y de parámetro, puede ser usado o no (en general, es el registro actual)  
	 * @return string Una URL de la forma path/to/file.php?param1=value1&param2=value2
	 */
	public function buildHref($input = NULL)
	{
		$href = $this->href;

		$sep = strpos($href, '?') ? '&' : '?'; 
		
		foreach ($this->params as $p) {
			$href = $href . $sep . $p->getTxt() . '=' . urlencode($p->getValue($input));
			$sep = "&";
		}	
  	
		if ($this->getCloseScreen()) {
			$href = 'cerrar.php?relative=true&url=' . urlencode($href);
		}
		
		return $href; 
	}
	
	//---- getters && setters ----/
	
	public function getTxt() {
		return $this->txt; 
	}
	public function setTxt($txt) {
		$this->txt = $txt; 
	}
	public function getHref() {
		return $this->href; 
	}
	public function setHref($href) {
		$this->href = $href; 
	}
	public function getParams() {
		return $this->params; 
	}
	public function getImg() {
		return $this->img; 
	}
	public function setImg($img) {
		$this->img = $img;
	}
	public function getCloseScreen() {
		return $this->closeScreen; 
	}
	public function setCloseScreen($closeScreen = true) { 
		$this->closeScreen = $closeScreen; 
	}
	public function getCssClass() {
		return $this->cssClass; 
	}
	public function setCssClass($cssClass) {
		$this->cssClass = $cssClass;
	}
	
	public function getMsgConfirm() {
		return $this->msgConfirm; 
	}
	public function setMsgConfirm($msgConfirm) {
		$this->msgConfirm = $msgConfirm; 
	}
	public function getSendFiltros() {
		return $this->sendFiltros; 
	}
	public function setSendFiltros($sendFiltros = true) {
		$this->sendFiltros = $sendFiltros; 
	}
	public function getTarget() {
		return $this->target;
	}
	public function setTarget($target) {
		$this->target = $target; 
	}
	public function getTargetOpts() {
		return $this->targetOpts;
	}
	public function setTargetOpts($targetOpts) {
		$this->targetOpts = $targetOpts; 
	}
	public function setEnabled($enabled = true) {
		$this->enabled = $enabled; 
	}
	public function isEnabled() {
		return $this->enabled; 
	}
}