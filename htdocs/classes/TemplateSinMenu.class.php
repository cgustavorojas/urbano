<?php
/**
 * @package common
 */

class TemplateSinMenu extends Template
{
	public function __construct()
	{
		parent::__construct('sinmenu');
		$this->addCss('/css/css.css');
		$this->addCss('/css/estilos.css');
		$this->addJs('/lib/prototype/prototype.js');
		$this->addJs('/include/js/ajax.js');
		$this->addJs('/include/js/funciones_campo_ingreso.js');
	}
}