<?php
/**
 * @package abm
 */

/**
 * Un control de entrada bastante particular. Sirve para ingresar campos de texto libre, pero 
 * donde se quiere dar al usuario una lista de opciones posibles. El usuario puede seleccionar
 * de dicha lista, o seleccionar la opción "Otros" e ingresar un texto libre.
 * 
 * En cualquiera de los casos, el control devuelve un único valor de texto que a todo efecto
 * es como si el usuario lo hubiera ingresado en forma libre en un InputString. 
 *
 * @package abm
 */
class InputStringCombo extends InputTextBox
{
	private $toUpper = false; 
	private $toLower = false;
	private $lista = array();
	
	
	public function __construct($txt, $campo = null, $lista, $size = null, $maxlength = null)
	{
		parent::__construct ($txt, $campo, $size, $maxlength);
		$this->lista = $lista; 
	}
	
	/**
	 * Después de la lógica normal de asignar al control el valor que vino en el request, 
	 * si corresponde pasa el valor a mayúsculas o minúsculas. 
	 */
	public function parseRequest() 
	{
		parent::parseRequest(); 
		
		if (! is_null($this->getValue()) && $this->toUpper) { 
			$this->setValue(strtoupper($this->getValue()));
			return; 
		}
		if (! is_null($this->getValue()) && $this->toLower) { 
			$this->setValue(strtolower($this->getValue()));
			return; 
		}
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
		$id        = $this->getId();
		$id2	   = $id . "_combo";
		$value     = $this->getValue();
		$disabled  = $this->isEnabled() ? '' : 'disabled';
		$type 	   = $this->isHideText() ? 'password' : 'text';
		
		$help = $this->isHelp() ? $this->getHtmlHelp() : ''; 

		$html = $html . "<select class='abm-como' name='$id2' id='$id2' onchange=\"onStringComboChange_$id();\">";

		$selected = false; 
		foreach ($this->lista as $key => $item) 
		{
			if (!is_null($value) && ($value == $item)) {
				$selected = true;  
				$html = $html . "<option selected>$item</option>";
			} else {
				$html = $html . "<option>$item</option>";
			}			
		}
		if (! $selected && ! is_null($this->getValue())) {
			$html = $html . "<option selected value='*'>Otros</option>";
		} else {
			$html = $html . "<option value='*'>Otros</option>";
		}
				
		$html = $html . "</select>&nbsp;";
		
		$html = $html . "<input id='$id' type='text' name='$id' value='$value' $maxlength $disabled $size > $help";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}

		$html = $html . "</td></tr>";
		return $html; 
	}

	public function getJavaScript()
	{
		$id = $this->getId();
		$id2 = $id . '_combo';
		
		$js = parent::getJavaScript(); 
		
		$js = $js . "
					 function onStringComboChange_$id()
				     {
				          var combo = \$('$id2').value;

				          if (combo == '*') {
				            \$('$id').show();
				            \$('$id').value = '';
				            \$('$id').focus();
				          } else if (combo == '') {
				          	\$('$id').hide();
				          	\$('$id').value = '';
				          } else { 
				            \$('$id').hide();
				          	\$('$id').value = combo;
				          }
				          
		             }
		             onStringComboChange_$id(); 
		             ";

		return $js; 
	}
	
	
	public function isToUpper() {
		return $this->toUpper; 
	}
	public function setToUpper($toUpper = true) {
		$this->toUpper = $toUpper; 
	}
	public function isToLowerr() {
		return $this->toLower; 
	}
	public function setToLower($toLower = true) {
		$this->toLower = $toLower; 
	}	
	public function getLista() {
		return $this->lista; 
	}
	/**
	 * Setea la lista de valores
	 * @param array $lista Array simple de valores estilo array('valor 1', 'valor 2', 'etc')
	 */
	public function setLista($lista)
	{
		$this->lista = $lista; 
	}
}