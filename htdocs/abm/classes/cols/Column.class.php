<?php
/**
 * @package abm
 */

/**
 * Define una columna en una grilla de resultados.
 *
 * @property string	$id			Identificador Ãºnico para esta columna. Por default, el campo o la leyenda. 
 * @property string $campo		Nombre del campo de la tabla a mostrar.
 * @property string $campoHover	Nombre del campo de la tabla a mostrar en el tooltip (texto que aparece en el mouse hover). Debe ser tipo string. 
 * @property string $txt		Etiqueta (tÃ­tulo) de la columna.
 * @property string $printTxt	Etiqueta (tÃ­tulo) de la columna para impresiones. Default: igual a $txt. 
 * @property mixed  $valor		Valor para celdas con valores fijos (que no se calculan en base a un campo).
 * @property mixed  $href		URL a la que ir cuando el usuario clickea esta celda.  
 * @property string $tipo		Tipo de datos de la columna.
 * @property array  $params		ParÃ¡metros con los que construir la URL.
 * @property int    $printWidth	Ancho de la columna al momento de imprimir (PDF, no pantalla).
 * @property string $align		AlineaciÃ³n de la celda (Column::CENTER, Column::LEFT, Column::RIGHT).
 * @property int	$maxLength	Cantidad mÃ¡xima de caracteres a mostrar. Si el valor sobrepasa esta cantidad, es truncado. 
 * @property string $cssClass	Clase CSS a aplicar a todos los valores de la columna
 * 
 * @package abm 
 * @subpackage columns
 */
class Column
{
	const CENTER = "center";
	const LEFT   = "left";
	const RIGHT  = "rigth";
	
	//------------
	// Propiedades
	//
	private $id; 
	private $campo; 
	private $campoHover; 
	private $hover; 
	private $txt; 
	private $printTxt; 
	private $valor; 
	private $href;
	private $campoLink; // si no es nulo este campo se genera un link  
	private $tipo = Tipo::STRING;
	private $params = array(); 
	private $printWidth = 10; 
	private $align = Column::CENTER;  
	private $maxLength;
	private $active = true; 
	private $cssClass = ''; 	
	private $cssCond = array();
	//
	//------------ 
	
	
	public function __construct($txt, $campo = NULL, $tipo = Tipo::STRING)
	{
		$this->txt = $txt; 
		$this->campo = is_null ($campo) ? Utils::toLowerNoBlanks($txt) : $campo; 
		$this->tipo = $tipo; 
	}
	
	
	/**
	 * El ID es una forma unÃ­voca de identificar a este filtro. 
	 * Lo correcto serÃ­a setear siempre un valor a la propiedad ID y listo, pero para 
	 * no tener que setearle siempre un ID distinto, la clase usa cierta lÃ³gica para devolver un ID. 
	 * Si no hay seteado un ID unÃ­voco, devuelve como ID el nombre del campo que se estÃ¡ filtrando, 
	 * si Ã©ste no estÃ¡, la leyenda y, de Ãºltima, genera un ID aleatorio y lo devuelve. 
	 * 
	 * @return string ID para identificar a este filtro. 
	 */
	public function getId() {
		if (! is_null($this->id))
			return $this->id; 
			
		if (! is_null($this->campo)) 
			return Utils::toLowerNoBlanks($this->campo);
			
		if (! is_null($this->txt))
			return Utils::toLowerNoBlanks($this->txt);

		$this->id = "c" . rand();
		return $this->id; 
	}
	
	/**
	 * Devuelve una URL que ya tiene formateados todos los parÃ¡metros. 
	 * 
	 * @param $row Registro actual  
	 * @return string Una URL de la forma path/to/file.php?param1=value1&param2=value2 o NULL si no se especificÃ³ href
	 */
	public function buildHref($row)
	{
		if (is_null($this->href))
			return NULL;
			
		$href = $this->href;

		$sep = strpos($href, '?') ? '&' : '?'; 
		
		foreach ($this->params as $p) {
			$href = $href . $sep . $p->getTxt() . '=' . urlencode($p->getValue($row));
			$sep = '&';
		}	
  	
		return $href; 		
	}
	
	/*
	 * Llena el campo donde va a estar el link para la columna, fuera del sistema 
	 */
	
	public function setCampoLink($campoLink){
		
		$this->campoLink = $campoLink;
	}
	
	/**
	 * Devuelve el valor de la columna para el registro pasado como parÃ¡metro, incluyendo links. 
	 * 
	 * @param  array 	$row	Registro actual.
	 * @param  UserPref $prefs	Preferencias del usuario respecto de visualizaciÃ³n.
	 *  
	 * @return string CÃ³digo HTML (puede tener links) del valor para la celda actual. 
	 */
	public function getValueHtml($row, UserPref $userPrefs)
	{
		$maxLength = $this->getMaxLength();
		$campo = $this->getCampo();
		$campoHover = $this->getCampoHover(); 
		
		$s = $userPrefs->toString($row[$campo], $this->getTipo());

		$title = null; 
		
		if ( ! is_null($maxLength) && ($maxLength > 0) && (strlen($s) > $maxLength)) {
			$title = $s;
			$s = substr($s, 0, $maxLength - 3) . '...';
		}				

		if ( ! is_null($campoHover)) { 
			$title = $row[$campoHover];
		}
		
		if (! is_null($this->hover)) {
			$title = $this->hover; 
		}
		
		$cssClass = is_null($this->getCssClass()) ? '' : $this->getCssClass(); 
		$auxTitle = is_null($title) ? '' : "title='$title'";
		
		$cssClass = $cssClass . $this->calcularCssCond($row);
		
		$href = $this->buildHref($row);
		
		if (! is_null($this->campoLink)){
			//$s = "<a href='".$row[$this->campoLink]."'>$s</a>";
			$s = "<a href='..$s'>$s</a>";
		}	
		elseif (! is_null($href)) {
			$s = "<a href='$href'>$s</a>";
		}
		
		$s = "<span class='$cssClass' $auxTitle>" . $s . '</span>';
		

		
		return $s; 
	}
	
	/**
	 * Atajo en lugar de llamar a setHref() addParam() para el caso particular de llamar
	 * a una pantalla de tipo View, pasÃ¡ndole un parÃ¡metro ID con el valor de una columna. 
	 * @param $screen Nombre de la pantalla de tipo ViewScreen a llamar
	 * @param $campo Nombre del campo a pasarle como parÃ¡metro ID
	 * @return void
	 */
	public function setHrefView($screen, $campo)
	{
		$this->setHref("view.php?screen=$screen");
		$this->addParam(new ParamCampo ('id', $campo));
	}
	
	
	public function clearCssCond() {
		$this->cssCond = array(); 
	}
	/**
	 * Define una formateo CSS condicional. Si se cumple la condiciÃ³n de que la columan $column es igual a $value,
	 * se aplica a la columna el estilo css $cssClass. 
	 * Si $igual es false, la condiciÃ³n se da vuelta y en lugar de preguntar por "igual a" pregunta por "distinto de".
	 * 
	 * @param string $cssClass Estilo CSS a aplicar
	 * @param string $column Nombre de la columna
	 * @param mixed $value Valor que debe tomar (o no tomar)
	 * @param bool $igual Si es TRUE, la columna debe ser igual a $value. Si es FALSE, debe ser distinta.
	 */
	public function addCssCond($cssClass, $columna, $valor = 't', $igual = true)
	{
		$cond = array ('cssClass' => $cssClass, 'columna' => $columna, 'valor' => $valor, 'igual' => $igual);
		$this->cssCond[] = $cond; 
	}
	
	/**
	 * En funciÃ³n del registro de la base pasado como parÃ¡metro, calcula cuÃ¡les son los estilos que debe aplicar.
	 * 
	 * @param array $row El registro actual de la consulta 
	 * @return string La cadena de CSS lista para aplicar al atributo "class" de HTML
	 */
	protected function calcularCssCond($row)
	{
		$css = ''; 

		foreach ($this->cssCond as $cond) 
		{
			$cssClass = $cond['cssClass'];
			$igual = $cond['igual'];
			$columna = $cond['columna'];
			$valor = $cond['valor'];

			if (($igual && ($row[$columna] == $valor)) || ((!$igual) && ($row[$columna] <> $valor))) {
				$css = $css . " " . $cssClass;
			}	
		}
		return $css; 
	} 
	
	//---- getters && setters ----//
	
	public function getCampo() {
		return $this->campo; 
	}
	public function setCampo($campo) { 
		$this->campo = $campo; 
	}
	public function getTxt() {
		return $this->txt; 
	}
	public function setTxt($txt) {
		$this->txt = $txt; 
	}
	public function getValor() {
		return $this->valor; 
	}
	public function setValor($valor) {
		$this->valor = $valor; 
	}
	public function getHref() {
		return $this->href; 
	}
	public function setHref($href) {
		$this->href = $href; 
	}
	public function addParam(Parametro $param) {
		$this->params[] = $param; 
	}
	public function getParams() {
		return $this->params; 
	}
	public function getTipo() {
		return $this->tipo; 
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo; 
	}
	public function getPrintWidth() {
		return $this->printWidth; 
	}
	public function setPrintWidth ($printWidth) {
		$this->printWidth = $printWidth; 
	}
	public function getAlign() {
		return $this->align; 
	}
	public function setAlign($align) {
		$this->align = $align; 
	}
	public function getMaxLength() {
		return $this->maxLength; 
	}
	public function setMaxLength($maxLength) {
		$this->maxLength = $maxLength; 
	}
	public function isActive() {
		return $this->active; 
	}
	public function setActive($active) {
		$this->active = $active; 
	}
	public function setId($id) {
		$this->id = $id; 
	}
	public function getPrintTxt() {
		if (is_null($this->printTxt))
			return $this->txt; 
		return $this->printTxt; 
	}
	public function setPrintTxt($printTxt) {
		$this->printTxt = $printTxt; 
	}
	public function setCampoHover($campoHover) {
		$this->campoHover = $campoHover; 
	}
	public function getCampoHover() {
		return $this->campoHover; 
	}
	public function getCssClass() {
		return $this->cssClass; 
	}
	public function setCssClass($cssClass) {
		$this->cssClass = $cssClass;
	}
	public function setHover($hover) {
		$this->hover = $hover; 
	}
	public function getHover() {
		return $this->hover; 
	}
}