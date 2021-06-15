<?php

class FiltroTextArea extends Filtro
{
	private $cols; 
	private $rows; 
	private $monospace = true; 
	
	public function __construct($txt, $campo = null, $cols = 60, $rows = 10)
	{
		parent::__construct();
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setSelectable();		
		$this->cols = $cols; 
		$this->rows = $rows;  
	}
	
	
	public function getHtml()
	{
		$html = "<tr><td class='label'>" . $this->getTxt() . "</td><td class='input'>";

		$cols      = $this->cols;
		$rows	   = $this->rows; 
		$id        = $this->getId();
		$value     = $this->getValue();
		
		$cssClass = $this->isMonospace() ? 'abm-textarea-monospace' : 'abm-textarea';
		
		$html = $html . "<textarea id='$id' name='$id' cols='$cols' rows='$rows' class='$cssClass'>$value</textarea>";
				
		$html = $html . "</td></tr>";
		return $html; 
	}
	

	public function setCols($cols) {
		$this->cols = $cols; 
	}
	public function setRows($rows) {
		$this->rows = $rows; 
	}
	public function getCols() {
		return $this->cols; 
	}
	public function getRows() {
		return $this->rows; 
	}
	public function isMonospace() {
		return $this->monospace; 
	}
	public function setMonospace($monospace = true) {
		$this->monospace = $monospace; 
	}
	
}