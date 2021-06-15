<?php
/**
 * @package abm
 */

/**
 * Define un tab dentro de una pantalla de tipo ViewScreen. 
 * Cada tipo de ViewTab (subclases) muestra cierto tipo de información, 
 * siempre relacionada con el registro principal que se cargó en el objeto ViewScreen. 
 * 
 * @package abm
 */
class ViewTab
{
	private $titulo; 
	private $viewToInclude; 
	private $screen; 
	private $constraintColumn;
	private $constraintValue; 		
	private $constraintIgual = true; 	
	
	public function __construct($titulo) {
		$this->titulo = $titulo; 
	}
	
	public function setConstraint ($column, $value = 't', $igual = true) 
	{
		$this->constraintColumn = $column; 
		$this->constraintValue = $value; 
		$this->constraintIgual = $igual;
	}

	public function isEnabledForThisRow($row)
	{
		if (is_null($this->constraintColumn)) {
			return true; 
		}
		
		$actualValue = $row[$this->constraintColumn];
		if ($this->constraintIgual)
			return ($actualValue == $this->constraintValue);
		else
			return ($actualValue != $this->constraintValue);
	}

	
	public function getCssForThisRow($row)
	{
		return ''; 
	}
	
	public function init() {}
	
	public function load() {}
	
	public function refrescar() {}
	
	public function getTitulo() {
		return $this->titulo; 
	}
	public function setTitulo($titulo) {
		$this->titulo = $titulo; 
	}
	public function getViewToInclude() {
		return $this->viewToInclude; 
	}
	public function setViewToInclude($viewToInclude) {
		$this->viewToInclude = $viewToInclude; 
	}
	
	/**
	 * @return ViewScreen
	 */
	public function getScreen() {
		return $this->screen; 
	}
	public function setScreen(ViewScreen $screen) {
		$this->screen = $screen; 
	}
}