<?php
/**
 * @package abm
 */

/**
 * Define un TAB dentro de una pantalla de tipo VIEW, que mostrará el contenido de una columna
 * del registro principal de la pantalla, de tipo texto libre multilínea. 
 * 
 * Maneja el caso de un registro que tiene, por ejemplo, una columna "notas" que guarda un texto largo,
 * de varias líneas y que no se presta para ser mostrado al mismo nivel de las otras columnas. Entonces
 * se define un tab de este tipo, que en lugar de mostrar registros asociados como TabQuery, muestra 
 * en ese espacio el texto libre.
 *  
 * Como todas las clases de tipo ViewTab, tiene además de la definición de la clase, un archivo template con 
 * el código HTML a mostrar, dentro de la subcarpeta "views". En este caso, "view-tabtext.php"
 
 * @package abm
 */
class TabText extends ViewTab
{
	private $campo; 
	private $html = false; 
	
	public function __construct ($titulo, $campo = NULL)
	{
		parent::__construct ($titulo);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($titulo) : $campo); 
		$this->setViewToInclude('view-tabtext.php');
	}
	
	public function getCampo() {
		return $this->campo; 
	}
	public function setCampo($campo) {
		$this->campo = $campo; 
	}
	/**
	 * Define si el contenido es HTML o simplemente texto
	 */
	public function setHtml($html = true) {
		$this->html = $html; 
	}
	public function isHtml() {
		return $this->html; 
	}
}