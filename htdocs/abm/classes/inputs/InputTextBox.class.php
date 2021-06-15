<?php
/**
 * @package abm
 */

/**
 * Clase especializada de Input base para todos los inputs que ingresan datos a través de un TextBox
 * 
 * @property boolean	$hideText	Si es true, muestra asteriscos en lugar del texto ingresado (campo password).*
 * @property integer	$maxLength	Longitud máxima que acepta el control
 * @property integer	$size		Tamaño del control en pantalla (no necesariamente igual que maxLength) 
 * @package abm 
 */
class InputTextBox extends Input
{
	private $maxLength; 
	private $size;
	private $hideText = false; 
	

	public function __construct($txt, $campo = NULL, $size = NULL, $maxlength = NULL, $obligatorio = false)
	{
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		
		if (! is_null($size)) 
			$this->setSize($size);
		
		if (! is_null($maxlength)) 
			$this->setMaxLength($maxlength);
		
		$this->setObligatorio($obligatorio);
		$this->setSelectable();
	}
		
	public function getHtml()
	{
		if ($this->isHidden())
			return ''; 
			
		$txt      = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
		$cssClass = $this->isObligatorio() ? "label obligatorio" : "label";
		
		$html = "<tr><td class='$cssClass'>$txt</td><td class='input'>";
		
		$maxlength = $this->getMaxLength() > 0 ? "maxlength='" . $this->getMaxLength() . "'" : "";
		$size      = $this->getSize() > 0 ? "size='" . $this->getSize() . "'" : "";
		$name      = "name='" . $this->getId() . "'";
		$id        = "id='" . $this->getId() . "'";
		$value     = "value='" . $this->getValue() . "'";
		$disabled  = $this->isEnabled() ? '' : 'disabled';
		$type 	   = $this->isHideText() ? 'password' : 'text';
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$onchange = is_null($this->getOnChange()) ? '' : 'onchange="eventHook(\'' . $this->getOnChange() .'\');"';
		
		$html = $html . "<input $id type='$type' $name $maxlength $disabled $size $value $onchange > $help";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		
		$html = $html . "</td></tr>";
		return $html; 
	}
	
	/**
	 * Setea tanto maxlength como size en $num.
	 * Típica función de vagancia para no llamar a setSize() y luego a setMaxLength(). 
	 * @param int $num Cantidad de posiciones
	 */
	public function setSizeAndLength($num) 
	{
		$this->setSize($num); 
		$this->setMaxLength($num); 
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
	public function isHideText() {
		return $this->hideText; 
	}
	public function setHideText($hideText = true) {
		$this->hideText = $hideText; 
	}
	
}