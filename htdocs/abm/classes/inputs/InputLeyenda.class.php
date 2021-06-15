<?php

class InputLeyenda extends Input
{
	public function __construct ($txt, $valor, $tipo)
	{
		parent::__construct();
		$this->setCampo(Utils::toLowerNoBlanks($txt));
		$this->setTxt($txt);
		$this->setTipo($tipo); 
		$this->setValue($valor);
		$this->setValueTxt(UserPref::load()->toString($valor, $tipo)); 
		$this->setReadOnly(true);
	}
	
	public function parseRequest() {}
	public function parseRow($row) {}
	public function fillParameter(ParamStmt $stmt) {}
	public function isValid() { return true; }

	public function getHtml()
	{
		$html =  "<tr><td class='txt " . $this->getCssTxt() . "'>" . $this->getTxt() . "</td><td class='input " . $this->getCssInput() ."'>"; 
		$html = $html . $this->getValueTxt() . "</td></tr>";
		return $html;  
	}
	
}