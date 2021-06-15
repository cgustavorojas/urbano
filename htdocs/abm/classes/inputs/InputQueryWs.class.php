<?php
/**
 * @package abm
 */

/**
 * Un INPUT que selecciona un registro de otra tabla, a través de un botón que abre
 * una nueva pantalla de tipo QueryScreen.
 * 
 * @property string 	$txt	Leyenda a mostrar al usuario
 * @property string		$campo	Nombre del campo a actualizar
 * @property string		$screen	Nombre de la pantalla (de tipo QueryScreen) a llamar para elegir el registro
 * @property string		$sql	Consulta SQL (que debe tener 1 parámetro) para obtener la descripción del registro a partir de su valor
 * 
 * @package abm
 */
class InputQueryWs extends Input
{
	private $screen;
	private	$wsclient; 
	private $size = 35; 
	private $params = array(); 
		
	/**
	 * Crea un nuevo InputQuery. 
	 * @param string $txt Leyenda a mostrar al usuario
	 * @param string $campo Columna de la tabla. Default: leyenda en minúsculas y sin blancos
	 * @param string $screen Nombre de la pantalla de tipo QueryScreen para seleccionar ítems
	 * @param string $wsclient Sentencia SQL que dado el valor en ?, selecciona la descripción a mostrar
	 * @param array $params Array asociativo con lista opcional de parámetros a pasar a la pantalla $screen
	 * @return void
	 */
	public function __construct ($txt, $campo = null, $screen, $wsclient = array(), $params = array())
	{
		$this->setTxt ($txt); 	
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo ); 
		$this->setScreen ($screen);
		$this->setWsClient($wsclient);
		$this->params = $params;   
	}

	/**
	 * Consulta el webservice especificado instanciando una clase 'class' (que hereda de \classes\WsClient.class.php)
	 * llamando al método 'call' que debe devolver un array del cual se devolverá el índice indicado por $wsfield
	 * @return unknown_type
	 */
	public function getWsValue()
	{
		$wsclient = $this->getWsClient(); 
		$class = $wsclient['class']; 
		$call  = $wsclient['call'];
		$wsfield = $wsclient['field'];
		$id    = $this->getValue(); 
		
		eval ("\$ws = new $class;");
		eval ("\$rs = \$ws->$call($id);");

		if (is_object($rs))	$rs = get_object_vars($rs); 
			
		return $rs[$wsfield];
	}
		
	public function setValue($value)
	{
		parent::setValue($value);	

		if (! is_null($this->getValue())) 
		{
			$this->setValueTxt($this->getWsValue());
		}
	}
	
	
	public function getHtml()
	{
		if ($this->isHidden())
			return '';
			
		$id  = $this->getId();
		$id2 = $id . "_txt";
		$id3 = $id . "_btn1";
		$id4 = $id . "_btn2";
		
		$txt      = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
		$cssClass = $this->isObligatorio() ? "label obligatorio" : "label";
		$size     = $this->size; 
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = "<tr><td class='$cssClass'>$txt</td><td class='input'>";
				
		$value = $this->getValue(); 
		$valueTxt = $this->getValueTxt(); 
		
		if (is_null($value)) {
			$html = $html . "<input type='hidden' name='$id' id='$id'>";
			$html = $html . "<input size='$size' disabled type='text' name='$id2' id='$id2'>";
		} else {
			$html = $html . "<input type='hidden' name='$id' id='$id' value='$value'>";
			$html = $html . "<input size='$size' disabled type='text' name='$id2' id='$id2' value='$valueTxt'>";
		}
		
		if ($this->isEnabled()) {
			$html = $html . "&nbsp;<a id='$id3' href='#'><img src='imagenes/lupa.gif' alt='Mostrar Lista' width='16' height='16'></a>";
			$html = $html . "&nbsp;<a id='$id4' href='#'><img src='imagenes/delete.gif' alt='Limpiar Valor' width='16' height='16'></a>";
		}
		
		$html = $html . " " . $help;
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
				
		$html = $html . "</td></tr>";
		return $html; 
	}
	
	public function getJavaScript()
	{
		if ($this->isHidden() || ! $this->isEnabled())
			return ''; 
			
		$id  = $this->getId();
		$id2 = $id . "_txt";
		$id3 = $id . "_btn1";
		$id4 = $id . "_btn2";
		$screen = $this->getScreen();
		$url1 = "query.php?screen=$screen&id1=$id&id2=$id2";

		foreach ($this->params as $key => $value) {
			if (! is_null($value)) {
				$url1 = $url1 . "&$key=$value";
			}
		}
		
		return "					
			Event.observe ('$id3', 'click', function() {
				saveValuesAndGoto('$url1');
				return false; 
			});
			
			Event.observe ('$id4', 'click', function() {
				$('$id').value = '';
				$('$id2').value = '';
				return false; 
			});
			
		";		
	}
	
	public function getScreen() {
		return $this->screen; 
	}
	public function setScreen($screen) {
		$this->screen = $screen; 
	}
	public function getWsClient() {
		return $this->wsclient; 
	}
	public function setWsClient($wsclient) {
		$this->wsclient = $wsclient; 
	}
	public function setParams($params) {
		$this->params = $params; 
	}
	public function getParams() {
		return $this->params;
	}
	public function getSize() {
		return $this->size; 
	}
	public function setSize($size) {
		$this->size = $size; 
	}
}