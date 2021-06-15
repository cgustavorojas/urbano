<?php

class InputSubtitulo extends Input
{
	public function __construct($txt)
	{
		$this->setTxt($txt); 
		$this->setId(Utils::toLowerNoBlanks($txt));
	}
	
	public function parseRequest() {}
	public function parseRow($row) {}
	public function fillParameter(ParamStmt $stmt) {}
	public function isValid() { return true; }
	
	public function getHtml()
	{
		$txt = $this->getTxt(); 
		return "</table><h3>$txt</h3><table>";
	}
}