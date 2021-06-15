<?php
/**
 * @package common 
 */

/**
 * Encapsula la lógica para darle una marco estético a la pantalla. 
 * Cada programa es responsable únicamente de generar el contenido principal de la pantalla, 
 * y delega en la clase Template el mostrar encabezados, menúes, pies de página, etc.
 * 
 * Se podrían instanciar templates "a mano", pero la clase también provee dos métodos estáticos
 * (tplDefault() y tplSinMenu() que devuelven instancias prearmadas de los templates más usados.  
 * 
 * @package common
 */
class Template 
{
	private $title = 'Urbano';
	private $css = array();
	private $js  = array(); 
	private $name; 
	
	private static $tplDefault;
	private static $tplSinMenu;

	
	/**
	 * Devuelve la instancia por defecto del template default del sistema. 
	 * Por ahora está hardcodeado a la clase TemplateDefault, pero podría
	 * ser configurable.
	 *  
	 * @return Template
	 */
	public static function getTplDefault() {
		if (is_null(Template::$tplDefault)) {
			Template::$tplDefault = new TemplateDefault; 
		}
		return Template::$tplDefault; 
	}
	
	/**
	 * Devuelve la instancia por defecto del template para pantallas sin menú (popups). 
	 * Por ahora está hardcodeado a la clase TemplateSinMenu, pero podría
	 * ser configurable.
	 *  
	 * @return Template
	 */
	public static function getTplSinMenu() {
		if (is_null(Template::$tplSinMenu)) {
			Template::$tplSinMenu = new TemplateSinMenu; 
		}
		return Template::$tplSinMenu; 
	}
	
	public function __construct($name = 'default') 
	{
		$this->name = $name;
	}
	
	public function sendHeader() 
	{
		$name = $this->name; 
		$t = $this; 
		include (dirname(__FILE__) . "/../template/$name-header.php");
	}
	
	public function sendFooter()
	{
		$name = $this->name;
		$t = $this;
		include (dirname(__FILE__) . "/../template/$name-footer.php");
	}
	
	public function getName() {
		return $this->name; 
	}
	public function setName($name) {
		$this->name = $name;
	}
	public function getJs() {
		return $this->js; 
	}
	public function setJs($js) {
		$this->js = is_array($js) ? $js : array($js);
	}
	public function addJs($js) {
		$this->js[] = $js; 
	}
	public function getCss() {
		return $this->css; 
	}
	public function setCss($css) {
		$this->css = is_array($css) ? $css : array($css);
	}
	public function addCss($css) {
		$this->css[] = $css; 
	}
	public function getTitle() {
		return $this->title; 
	}
	public function setTitle($title) {
		$this->title = $title; 
	}
}