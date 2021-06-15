<?php
/**
 * @package abm
 */

/**
 * Tipo especializado de input para ingresar valores Multiples.
 * 
 * Requiere enviar una consulta Sql que al ejecutarla retorne los posibles valores que se asignarán 
 * a la tabla de relación junto con el Id de la tabla en cuestión. 
 * Va a requerir un afterGuardar para realizar los inserts de estos valores con el Id nuevo de la tabla.
 *  
 * @package abm
 */
class InputMultiple 
{
	private $inputs; //lista de inputs
	private $valueTxt = Null; //valor a mostrar
	private $campo = Null; //identificador del control
	
	public function __construct($txt,$campo=null,$sql,$tipo ='checkbox')
	{
	
		$this->setTxt ($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo ); 
		
		$cons = Database::query($sql);
		
		while ($fila = $cons->getRow()){
			
			array_push($this->valores,$fila);
			
		}
		
	}
	


	
	/**
	 * Los checkbox son traicioneros a la hora de codificarlos en HTML,por eso se guarda un arreglo
	 * con los valores que se seleccionaron. Requiere que la tabla de valores a seleccionar sea
	 * de tipo id,txt 
	 */
	public function parseRequest()
	{
		$arreglo = array();
	
		foreach ($this->valores as $valor){
			
		if ($_REQUEST['txt'.$valor['id']] == 'on'){array_push($arreglo,$valor['id']);} 
		}

		$this->setValue($arreglo);
		
	}
	
	
	/**
	 * Devuelve el cÃ³digo HTML para mostrar en la pÃ¡gina. 
	 * 
	 * Los checkbox son traicioneros a la hora de codificarlos en HTML, porque si tienen valor false, el browser no los envÃ­a
	 * como parte de los parÃ¡metros. Para evitar esto, mediante javascript se maneja un input adicional, oculto, de ID
	 * "xxxx_value", que tiene valores t o f. 	
	 */
	public function getHtml()
	{
		if ($this->isHidden())
			return '';

		//$id  = $this->getId();
		//$id2 = $id . "_value";
		$txt = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
			
		$disabled = $this->isEnabled() ? '' : 'disabled';
		//$checked = $this->getValue() == 't' ? 'checked' : '';
		//$value2 = $this->getValue();
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = "<tr><td class='label'>$txt</td><td class='input'>";
		
		foreach ($this->valores as $valor){

		$checked = '';
		$id = 'txt'.$valor['id'];	
		$id2 = 'id'.$valor['id'];
		
		if ($_REQUEST['txt'.$valor['id']] == 'on'){$value2 = $valor['id'];	$checked ='checked';} 
		else {$value2 ='vacio';}
	 
		$html = $html . "<input type='hidden' name='$id2' id='$id2' value='$value2'>";
		//onchange=\"\$('$id2').value=this.checked ? 't' : 'f'\"
		$html = $html . "<input type='checkbox' $checked name='$id' id='$id'  $disabled >&nbsp;&nbsp;&nbsp;".$valor['txt']."  </br>";
		}
		$html = $html . "</td></tr>";
		
		return $html; 		
	}
	
}