<?php
/**
 * @package abm
 */

/**
 * Filtro general para valor ingresados en un TEXT BOX (tanto números como strings). 
 * 
 * Se encarga de la lógica propia de un maneja un INPUTBOX, permitiendo setear la
 * longitud máxima del texto ingresado, por ejemplo, y generando código HTML para el INPUT. 
 * 
 * @package abm 
 */
class FiltroTextBox extends Filtro
{
	private $maxLength; 
	private $size; 
	
	public function __construct($txt, $campo = NULL, $size = NULL, $maxlength = NULL)
	{
		parent::__construct();
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		
		if (! is_null($size)) 
			$this->setSize($size);
		
		if (! is_null($maxlength)) 
			$this->setMaxLength($maxlength);
			
		$this->setSelectable();
		
	}
		
	public function getHtml()
	{
		$html = "<tr><td class='label'>" . $this->getTxt() . "</td><td class='input'>";
		
		$maxlength = $this->maxLength > 0 ? "maxlength='" . $this->maxLength . "'" : "";
		$size      = $this->size > 0 ? "size='" . $this->size . "'" : "";
		$name      = "name='" . $this->getId() . "'";
		$id        = "id='" . $this->getId() . "'";
		$value     = "value='" . $this->getValue() . "'";
		
		$onchange = is_null($this->getOnChange()) ? '' : 'onchange="eventHook(\'' . $this->getOnChange() .'\');"';
		
		$html = $html . "<input type='text' $id $name $maxlength $size $value $onchange>";
		
		if (! $this->isValid()) {
			$error = $this->Error();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		
		$html = $html . "</td></tr>";
		return $html; 
	}

	//---- getters && setters ----//
	
	public function getMaxLength() {
		return $this->maxLength; 
	}
	public function setMaxLength($maxLength) {
		$this->maxLength = $maxLength; 
	}
	public function getSize() {
		return $this->size; 
	}
	public function setSize($size) {
		$this->size = $size; 
	}
}