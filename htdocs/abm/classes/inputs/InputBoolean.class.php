<?php
/**
 * @package abm
 */

/**
 * Tipo especializado de input para ingresar valores true/false.
 * 
 * ATENCION: el tipo boolean es específico de PostgreSQL y tiene sus particularidades. 
 * PHP tiene problemas para convertir en forma automática el tipo nativo de PHP "true/false"
 * en los campos boolean de la base, porque ésta espera en cambio los literales 't' ó 'f'. 
 * En consecuencia, este control maneja tipo boolean pero "a la postgres", o sea, 
 * donde 't' significa true y 'f' significa false. 
 * 
 * Si el valor va a ser pasado directamente a la base, no hay problema porque es transparente.
 *  
 * Tener cuidado con esto porque falla: 
 *      if ($mi_input->getValue()) {...}
 *      
 * Debe escribirse así: 
 *      if ($mi_input->getValue() == 't') {...}
 * 
 *  
 * @package abm
 */
class InputBoolean extends Input
{
	public function __construct($txt, $campo = null)
	{
		parent::__construct();
		$this->setTxt ($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo ); 
		$this->setTipo (Tipo::BOOLEAN);
	}
	
	/**
	 * Puede recibir varios tipos de datos, que convierte siempre a boolean. 
	 * Acepta 1, true ó 't' como valores verdaderos. 
	 * Acepta 0, false ó 'f' como valores falsos. 
	 *  
	 * @see abm/classes/Input#setValue($value)
	 */
	public function setValue($value)
	{
		if ($value == 't' || $value == 1 || $value === true) {
			parent::setValue('t');
		} else {
			parent::setValue('f'); 
		} 
	}
	
	
	
	/**
	 * Los checkbox son traicioneros a la hora de codificarlos en HTML, porque si tienen valor false, el browser no los envía
	 * como parte de los parámetros. Para evitar esto, mediante javascript se maneja un input adicional, oculto, de ID
	 * "xxxx_value", que tiene valores 0 ó 1. 
	 */
	public function parseRequest()
	{
		$id = $this->getId();
		$id2 = $id . "_value";
		
		if (isset($_REQUEST[$id2])) {
			$this->setValue($_REQUEST[$id2]);
		} else {
			$this->setValue(null); 
		}		
	}
	
	
	/**
	 * Devuelve el código HTML para mostrar en la página. 
	 * 
	 * Los checkbox son traicioneros a la hora de codificarlos en HTML, porque si tienen valor false, el browser no los envía
	 * como parte de los parámetros. Para evitar esto, mediante javascript se maneja un input adicional, oculto, de ID
	 * "xxxx_value", que tiene valores t o f. 	
	 */
	public function getHtml()
	{
		if ($this->isHidden())
			return '';

		$id  = $this->getId();
		$id2 = $id . "_value";
		$txt = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
			
		$disabled = $this->isEnabled() ? '' : 'disabled';
		$checked = $this->getValue() == 't' ? 'checked' : '';
		$value2 = $this->getValue();
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = "<tr><td class='label'>$txt</td><td class='input'>";
		
		$html = $html . "<input type='hidden' name='$id2' id='$id2' value='$value2'>";
		$html = $html . "<input type='checkbox' $checked name='$id' id='$id' onchange=\"\$('$id2').value=this.checked ? 't' : 'f'\" $disabled > $help";
		
		$html = $html . "</td></tr>";
		return $html; 		
	}
	
}