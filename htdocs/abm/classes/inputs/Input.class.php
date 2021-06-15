<?php
/**
 * @package abm
 */

/**
 * Maneja un control de entrada de datos en una pantalla de alta o edición de registros.
 * Cada subclase es un tipo específico de control.
 * 
 *  @property string 	$id			Identificador único de este input, válido para referenciar en PHP y en Javascript. 
 *  @property string	$campo		Campo del registro que este input va a editar. 
 *  @property string	$txt		Leyenda para mostrar al usuario.
 *  @property bool		$enabled	Si enabled = false, se muetra el control pero no se permite editarlo
 *  @property bool		$readOnly	Es parecido a enabled, pero éste puede ser modificado en forma dinámica, mientras que si es readOnly, es siempre readOnly
 *  @property bool		$hidden		El control no se muestra al usuario, se usa internamente para valores calculados
 *  @property bool		$obligatorio	Tira error si el control no se llena con algún dato. Default: false. 
 *  @property string	$value		Valor ingresado
 *  @property string	$valueTxt	Para el caso de inputs tipo combo, donde el valor del campo es distinto del valor a mostrar, el valor a mostrar.
 *  @property string	$tipo		Tipo de datos del campo a modificar
 *  @property string	$error		Si hubo un error de validación, el mensaje de error.
 *  @property bool		$selectable	Si se puede hacer focus() en javascript a este control. Default: false.
 *  @property string	$help		Texto libre de ayuda que aparece en ciertas pantallas como un tooltip.
 *  @property string	$helpTitle	Titulo la ayuda que aparece en ciertas pantallas como un tooltip.
 *  @property bool		$isHelp		Define si la ayuda fue seteada y por ende habilitada para su visualización.  
 *  @property bool		$hasFocus	Si tiene el foco en la pantalla 
 *  @property string	$onChange	Método al cual llamar en cuando se produce un evento onchange
 *  @property bool		$persistent	si está en true se incluye en el insert/update, sino no
 * @package abm
 * @subpackage inputs 
 */
class Input
{
	//----------------
	// Propiedades
	//
	private $id; 
	private $campo; 
	private $txt; 
	private $enabled = true;
	private $readOnly = false;  
	private $hidden = false; 
	private $obligatorio = false; 
	private $value = NULL;			// del tipo correcto según el $tipo
	private $valueTxt = NULL; 		// descripción a mostrar al usuario
	private $tipo = Tipo::STRING;  
	private $error;
	private $selectable = false; 	// si se puede hacer un focus() en javascript a este control
	private $help;
	private $helpTitle;
	private $isHelp = false;
	private $hasFocus = false; 		
	private $onChange;				// método al cual llamar en cuando se produce un evento onchange
	private $originalValue; 		// se usa para saber si el usuario ingresó algo o no.
	private $persistent = true; 
	
	//
	//---------------- 
	
	private $cssTxt = "";
	private $cssInput = ""; 
	
	public function __construct() {}
	
	/**
	 * Devuelve el valor del control, en una variable del tipo correcto. 
	 * @return El valor ingresado, del tipo correcto
	 */
	public function getValue() {
		return $this->value; 
	}
	
	/**
	 * Setea el valor del control y de su leyenda (valueTxt).  
	 * Para convertir a partir de valores en forma de texto, usar parseValue(). 
	 * @param $value Valor a setear. Debe corresponder con el tipo de datos del control. 
	 */
	public function setValue($value) 
	{
		$this->value = $value; 
		$this->valueTxt = $value; 	
	}
	
	/**
	 * Setea el valor del control a partir de una cadena de texto. 
	 * Usado primariamente para setear el valor del control a partir del lo enviado por el browser.
	 * Cómo convertir la cadena de texto a su tipo correcto puede ser modificado en subclases específicas.  
	 * @param $valueAsString Valor a setear, en formato cadena de texto. 
	 */
	public function parseValue($valueAsString)
	{
		if (is_null($valueAsString)) {
			$this->setValue(null);
			return; 
		}
		
		$v = trim ($valueAsString);
		if (strlen($v) == 0) {
			$this->setValue(null);
			return; 
		}
		
		//TODO: ver qué hacer con cada tipo específico
		switch($this->tipo)
		{
			case Tipo::STRING:
					$this->setValue($v);			
					break;

			case Tipo::NUMBER:
					$this->setValue($v); 
					break; 
					
			case Tipo::MONEY:
					$this->setValue($v); 
					break; 
		
			case Tipo::DATE:
					$this->setValue($v); 
					break;
					 
			case Tipo::BOOLEAN:
					if ($v == 't' || $v === true || $v == 1)
						$this->setValue('t'); 
					else 
						$this->setValue('f');
					break;
		}
	}
	
	/**
	 * Toma de los parámetros recibidos por POST o GET el que corresponde a este input. 
	 * Termina llamando a parseValue() pasándole el valor que vino en el parámetro del request. 
	 */
	public function parseRequest()
	{
		if (isset($_REQUEST[$this->id])) {
			$this->parseValue($_REQUEST[$this->id]);
		} else {
			$this->setValue(NULL); 
		}
	}
	
	/**
	 * Toma del registro el valor que corresponde a este input.
	 * Termina llamando a setValue() con el valor de la columna que corresponda. 
	 */
	public function parseRow($row) 
	{
		if (isset ($row[$this->campo]) && ! is_null($row[$this->campo])) {
			$this->setValue($row [ $this->campo ]);
		} else { 
			$this->setValue(NULL);
		}
		$this->originalValue = $this->getValue(); 				
	}
	
	/**
	 * Oportunidad que tienen descendientes para una primera inicialización. 
	 * Esta versión genérica simplemente toma en cuenta algunos defaults.  
	 */
	public function init() 
	{
		if (is_null($this->id) && ! is_null($this->campo)) { 
			$this->id = Utils::toLowerNoBlanks ($this->campo); 
		}		
		if (is_null($this->id) && ! is_null($this->txt)) { 
			$this->id = Utils::toLowerNoBlanks ($this->txt); 
		}		
		if (is_null($this->id)) {
			$this->id = 'i' . rand();
		}
	}
	
	public function isChanged() {
		return	(is_null($this->originalValue) && ! is_null($this->value)) ||
				(! is_null($this->originalValue) && is_null($this->value)) ||
				(! is_null($this->originalValue) && ! is_null($this->value) && $this->originalValue != $this->value);
	}
	
	/**
	 * Oportunidad que tienen descendientes para traer datos del entorno (lookups, etc.).
	 * 
	 * @param $inputs Array de todos los inputs (por si necesita un valor de otro campo para refrescarse) 
	 */
	public function refrescar($inputs) {}
	
	
	/**
	 * Agrega en el ParamStmt recibido como parámetro, el valor correspondiente a este filtro. 
	 * Sirve tanto si se recibe un InsertStmt o un UpdateStmt. 
	 */
	public function fillParameter(ParamStmt $stmt)
	{
		if ((! $this->readOnly) and ($this->isPersistent()))  {
			$stmt->add ($this->getCampo(), $this->getValue(), $this->getTipo());
		}
	}
	
	/**
	 * Devuelve el código HTML para mostrar este control.
	 * Debe ser implementada en subclases específicas. 
	 */
	public function getHtml() 
	{
		return ""; 
	}
	
	/**
	 * Devuelve el código JavaScript para customizar este control. 
	 * Debe (puede) ser implementada en subclases específicas. 
	 */
	public function getJavaScript()
	{
		return "";
	}
	
	/**
	 * Verifica si el valor ingresado es válido, verificando se previamente se seteó un error con setError() 
	 * @return bool Si el valor ingresado en el filtro es válido
	 */
	public function isValid()
	{
		return is_null($this->error);
	}

	/**
	 * Valida el dato ingresado. 
	 * En caso de haber error, el mensaje se puede recuperar con getError().
	 * @return bool TRUE si no hay error, FALSE si lo hay
	 */
	public function validar()
	{
		$this->setError(NULL);
		if ($this->isObligatorio() && is_null($this->getValue())) {
			$this->setError("requerido");
			return false; 
		}
		return true; 
	}
	
	/**
	 * Algunos controles, se componen en pantalla por varios elementos HTML. 
	 * El ID del componente (getId()) puede no ser el ID del elemento HTML que hay que
	 * seleccionar para hacer foco. Ejemplo: un campo fecha con id "fecha" en realidad
	 * se compone de 3 INPUTS: fecha_dia, fecha_mes y fecha_anio. En este caso, 
	 * getId() puede devolver "fecha" y getSelectableId() devolver "fecha_dia". 
	 * 
	 * @return Por default, lo mismo que getId().
	 */
	public function getSelectableId() {
		return $this->getId();
	}
	
	//---- getters && setters ----//
	
	public function getId() {
		return $this->id; 
	}
	public function setId($id) {
		$this->id = $id; 
	}
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
	public function isEnabled() {
		return $this->enabled; 
	}
	public function setEnabled($enabled) {
		$this->enabled = $enabled; 
	}
	public function isReadOnly() {
		return $this->readOnly; 
	}
	public function setReadOnly($readOnly) {
		$this->readOnly = $readOnly; 
	}
	public function isHidden(){
		return $this->hidden; 
	}
	public function setHidden($hidden) {
		$this->hidden = $hidden; 
	}
	public function isObligatorio() {
		return $this->obligatorio; 
	}
	public function setObligatorio($obligatorio = true) {
		$this->obligatorio = $obligatorio; 
	}
	public function getTipo() {
		return $this->tipo; 
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo; 
	}
	public function getCssTxt() {
		return $this->cssTxt; 
	}
	public function setCssTxt($cssTxt) {
		$this->cssTxt = $cssTxt; 
	}
	public function getCssInput() {
		return $this->cssInput; 
	}
	public function setCssInput($cssInput) {
		$this->cssInput = $cssInput; 
	}
	public function getError() {
		return $this->error; 
	}
	public function setError($error) {
		$this->error = $error; 
	}
	public function getValueTxt() {
		return $this->valueTxt; 
	}
	public function setValueTxt($valueTxt) {
		$this->valueTxt = $valueTxt; 
	}
	public function isSelectable() {
		return $this->selectable; 
	}
	public function setSelectable($selectable = true) { 
		$this->selectable = $selectable; 
	}
	public function setHelp($help,$helpTitle = NULL) {
		$this->help      = $help;
		$this->helpTitle = is_null($helpTitle) ? $this->getTxt() : $helpTitle;
		$this->isHelp    = true;
	}
	public function getHelp() {
		return $this->help;
	}
	public function getHelpTitle() {
		return $this->helpTitle;
	}
	public function isHelp() {
		return $this->isHelp;
	}
  	public function getHtmlHelp()
  	{
		return $this->isHelp() ? '<a class="tt" href="#">
	                        <img src="imagenes/ayuda.gif">
	                        <span class="tooltip">
	                            <span class="top">AYUDA EN LÍNEA</span>
	                            <span class="middle">'.$this->getHelpTitle().'</span>
	                            <span class="bottom">'.$this->getHelp().'</span>
	                        </span>
	                    </a>' : '';
  	}
	public function hasFocus() {
		return $this->hasFocus; 
	}
	public function setFocus($focus = true) {
		$this->hasFocus = $focus; 
	}
	public function setOnChange($onChange)	{
		$this->onChange = $onChange; 		
	}
	public function getOnChange() {
		return $this->onChange; 
	}
	
	public function setPersistent($persistent){
		$this->persistent = $persistent;
	}
	
	public function isPersistent(){
		return $this->persistent;
	}
	/**
	 * Permite a inputs más específicos definir un valor por defecto para el input cuando se incializa de cero en una pantalla de alta. 
	 */
	public function initDefault() {}
	
	
}