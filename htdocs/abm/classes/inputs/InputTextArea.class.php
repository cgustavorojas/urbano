<?php
/**
 * @package abm
 */

/**
 * Clase especializada de Input que muestra un area de texto para ingreso de varias líneas.
 * 
 * @package abm 
 */
class InputTextArea extends Input
{
	private $cols; 
	private $rows; 
	private $wrap = false;

	public function __construct($txt, $campo = NULL, $cols = 60, $rows = 10, $obligatorio = false)
	{
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		
		$this->setCols($cols);
		$this->setRows($rows);
		$this->setObligatorio($obligatorio);
		$this->setSelectable();
	}
		
	public function getHtml()
	{
		if ($this->isHidden())
			return ''; 
			
		$txt      = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
		$cssClass = $this->isObligatorio() ? "label obligatorio" : "label";
		
		$html = "<tr><td class='$cssClass' valign='top'>$txt</td><td class='input'>";

		$cols = $this->cols; 
		$rows = $this->rows; 
		$id   = $this->getId();
		$value = $this->getValue();
		$disabled  = $this->isEnabled() ? '' : 'disabled';
		$wrap   = $this->wrap ? 'on' : 'off';
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = $html . "<textarea wrap='$wrap' id='$id' name='$id' cols='$cols' rows='$rows' $disabled class='abm-textarea'>$value</textarea> $help";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		
		$html = $html . "</td></tr>";
		return $html; 
	}
	
	
	//---- getters && setters ----//

	public function getWrap() {
		return $this->wrap; 
	}
	public function setWrap($wrap = true) {
		$this->wrap = $wrap; 
	}
	public function getRows() {
		return $this->rows; 
	}
	public function setRows($rows) {
		$this->rows = $rows;
	}
	public function getCols() {
		return $this->cols; 
	}
	public function setCols($cols) {
		$this->cols = $cols; 
	}	
	
}