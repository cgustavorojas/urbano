<?php
/**
 * @package common
 */

class TemplateDefault extends Template
{
	public function __construct()
	{
		parent::__construct();
		$this->addCss('/css/css.css');
		$this->addCss('/abm/css/abm.css');
		$this->addCss('css/estilos.css>');
		
		$this->addJs('/lib/prototype/prototype.js');
		$this->addJs('/include/js/ajax.js');
		$this->addJs('/include/js/funciones_campo_ingreso.js');
		$this->addJs('/include/js/imprimir_div.js');
	}
	
	
}