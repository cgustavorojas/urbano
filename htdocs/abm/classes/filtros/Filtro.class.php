<?php
/**
 * @package abm
 */

/**
 * Clase genérica que encapsula la lógica de un filtro
 * de datos dentro de una pantalla de tipo QueryScreen. Cada filtro es responsable
 * de agregar una claúsula WHERE a la sentencia SQL que se ejecuta para obtener 
 * los resultados que se mostrarán en la grilla. 
 * 
 * @property string $txt 		Etiqueta a mostrar al usuario
 * @property string $id  		Identificador único (tanto PHP como JS)
 * @property mixed  $value 		valor que tiene (ingresado por el usuario o sacado de otro lado)
 * @property string $campo 		Campo de la consulta sobre el que se va a aplicar el filtro 
 * @property string $operador	Operador para la filtrado (default: "="). Otras opciones: ">", "<", etc. 
 * @property string $where		En lugar de especificar campo y operador, se puede 
 * 								especificar una cláusula WHERE custom, más elaborada. 
 * 								Ejemplo: "upper(campo1) LIKE ?".
 * @property string $tipo		Tipo de datos de la columna a filtrar. Default: Tipo::STRING.
 * @property string $error		Si hubo un error de validación, el mensaje de error a mostrar.  
 * @property bool   $printable	Si hay que mostrarlo al usuario o funciona oculto
 * @property bool   $active		Si no está activo, el filtro está definido pero no forma parte de 
 * 								la configuración activa, no filtra nada y no se muestra. El usuario
 * 								puede activarlo entrando a la customización de la pantalla. 
 * @property integer $posicion	Columna dentro de la pantalla, para el caso de pantallas multi-columna. 
 * @property string	$onChange	Método al cual llamar en cuando se produce un evento onchange* 
 * 
 * @package abm 
 * @subpackage filtros
 */

class Filtro
{
	//--------------
	// Propiedades
	//
	private $txt; 
	private $id;
	private $value;
	private $valueTxt;
	private $campo;   
	private $operador = '=';
	private $where; 
	private $tipo = Tipo::STRING; 
	private $error; 
	private $printable = true;
	private $active = true; 
	private $posicion = 1;
	private $obligatorio = false; 
	private $onChange;				// método al cual llamar en cuando se produce un evento onchange
	private $selectable = false; 	// si se puede hacer un focus() en javascript a este control
	private $hasFocus = false; 		
	private $enabled = true;
	private $autoFilter = true; 	// si es TRUE, genera en forma automática la cláusula WHERE. Si es false, hay que programar qué hacer con el valor ingresado 
	
	//
	//--------------
	
	
	public function __construct() {}
	

	/**
	 * El ID es una forma unívoca de identificar a este filtro. 
	 * @return string ID para identificar a este filtro. 
	 */
	public function getId() {
		return $this->id;
	}
	
	
	/**
	 * Inicialización del filtro. 
	 * Se llama una vez antes de hacer ninguna operación sobre el mismo.  
	 * 
	 * @return void
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
			$this->id = 'f' . rand();
		}		
	}
	
	/**
	 * Refrescar datos de la base.
	 * Es llamado cada vez que el filtro deba refrescar su lista de valores
	 * posibles a partir de la base (o de alguna otra fuente). 
	 *
	 * @param $filtros Array de todos los filtros (por si necesita un valor de otro campo para refrescarse) 
	 * @return void
	 */
	public function refrescar($filtros) 
	{
		
	}

	/**
	 * Devuelve el código HTML para mostrar el filtro en pantalla.
	 * Cómo mostrar el filtro depende del tipo de filtro del que se trate. 
	 * 
	 * @return string Código HTML
	 */
	public function getHtml()
	{
		return ""; 
	}
	
	/**
	 * Devuelve código javascript auxiliar que el filtro pueda necesitar. 
	 * El objetivo es que el código javascript no se devuelva en getHtml() para
	 * no insertarlo en un lugar inválido de la página.
	 * No devuelve los tags SCRIPT porque asume que los va a agregar el llamador.  
	 * 
	 * @return string Código JavaScript
	 */
	public function getJavaScript()
	{
		return ""; 
	}
	
	/**
	 * Parsea los valores recibidos del browser.
	 * Busca en los parámetros el valor que corresponde a este
	 * filtro y con eso setea $this->value 
	 *  
	 * @return void
	 */
	public function parseRequest()
	{
		if (isset($_POST[$this->id])) {
			if (strlen(trim($_POST[$this->id])) > 0) { 
				$this->setValue($_POST[$this->id]);
			} else {
				$this->setValue(null);
			}
		} 	
	}
	
	/**
	 * Arma la cláusula WHERE correspondiente a este filtro.
	 * Esta clase, al ser casi abstracta, ejecuta una lógica bastante general, tomando
	 * el caso de un campo, un operador y un valor, que puede ser string o numérico. 
	 * Clases descendientes pueden tener una lógica más elaborada.
	 * En el caso que el filtro tenga un valor ingresado inválido, no filtra nada (devuelve un string vacío).  
	 * 
	 * @return string con la cláusula. Ej: "ejercicio = 2009"
	 */
	public function getWhereClause()
	{
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! $this->isAutoFilter()) {
			return ""; 
		}
		
		if (! is_null($this->getValue())) {
			
			$where = $this->getWhere(); 
			$value = Utils::sqlEscape($this->getValue(), $this->getTipo());
			
			if (is_null($where)) {
				$campo = $this->getCampo(); 
				$operador = $this->getOperador();
	
				return " AND $campo $operador $value "; 				
			} else {
				$where = str_replace('?', $value, $where);
				return "AND $where";
			}
		}
		
		return "";
		
	}
	
	/**
	 * Devuelve si el valor ingresado es válido, verificando si previamente se seteó un error con getError().
	 * La condición de error en sí es detectada en validar(). 
	 * @return bool Si el valor ingresado en el filtro es válido
	 */
	public function isValid()
	{
		return is_null($this->error);
	}
		
	/**
	 * Valida que el dato ingresado sea válido y si no lo es setea el mensaje de error con setError()
	 * @return bool TRUE si tiene datos válidos, FASE caso contrario.
	 */
	public function validar()
	{
		$this->setError(NULL);
		return true; 
	}
	
	public function setValue($value) {
		$this->value = $value;
		$this->valueTxt = $value;  
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
		
	//----- getters && setters -----// 

	public function getTxt() {
		return $this->txt; 
	}
	public function setTxt($txt) {
		$this->txt = $txt; 
	}
	public function setId($id) {
		$this->id = $id; 
	}
	public function getValue() {
		return $this->value; 
	}
	public function getValueTxt() {
		return $this->valueTxt;
	}
	public function setValueTxt($valueTxt) {
		$this->valueTxt = $valueTxt;
	}
	public function getCampo() {
		return $this->campo; 
	}
	public function setCampo($campo) {
		$this->campo = $campo;
	}
	public function getOperador() {
		return $this->operador; 
	}
	public function setOperador($operador) {
		$this->operador = $operador; 
	}
	public function getWhere() {
		return $this->where; 
	}
	public function setWhere($where) {
		$this->where = $where;
	}
	public function getTipo() {
		return $this->tipo; 
	}
	public function setTipo($tipo) {
		$this->tipo = $tipo; 
	}
	public function isTipo($tipo) {
		return $this->tipo == $tipo; 
	}
	public function getError() {
		return $this->error;
	}
	public function setError($error) {
		$this->error = $error; 
	}
	public function isPrintable() {
		return $this->printable; 
	}
	public function setPrintable($printable = true) {
		$this->printable = $printable; 
	}
	public function isActive() {
		return $this->active; 
	}
	public function setActive($active) {
		$this->active = $active; 
	}
	public function getPosicion() {
		return $this->posicion; 
	}
	public function setPosicion ($posicion) {
		$this->posicion = $posicion; 
	}
	public function setObligatorio($obligatorio = true) {
		$this->obligatorio = $obligatorio; 
	}	
	public function isObligatorio() {
		return $this->obligatorio; 
	}
	public function setOnChange($onChange)	{
		$this->onChange = $onChange; 		
	}
	public function getOnChange() {
		return $this->onChange; 
	}
	public function isSelectable() {
		return $this->selectable; 
	}
	public function setSelectable($selectable = true) { 
		$this->selectable = $selectable; 
	}
	public function hasFocus() {
		return $this->hasFocus; 
	}
	public function setFocus($focus = true) {
		$this->hasFocus = $focus; 
	}
	public function isEnabled() {
		return $this->enabled; 
	}
	public function setEnabled($enabled) {
		$this->enabled = $enabled; 
	}
	public function setAutoFilter($autoFilter = true) {
		$this->autoFilter = $autoFilter; 
	}	
	public function isAutoFilter() {
		return $this->autoFilter; 
	}
	
}