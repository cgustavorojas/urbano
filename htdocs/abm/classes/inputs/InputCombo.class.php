<?php
/**
 * @package abm
 */

/**
 * Subclase de Input que permite seleccionar el valor de un combo de opciones fijo. 
 * La lista de valores del combo se arma con un array en forma estática. 
 * 
 * @property array $valores Array asociativo con la lista de claves y valores del combo
 * 
 * @package abm
 * @subpackage inputs
 */
class InputCombo extends Input
{
	private $lista;
	private $showKey = false;
	private $size = null;
	private $monospace = false; 
	private $multiple = '';
	private $nombre =''; 	

	public function __construct ($txt, $campo, $lista, $tipo = Tipo::STRING, $showKey = false,$multiple='')
	{
		parent::__construct();
		$this->setCampo ($campo); 
		$this->setTxt ($txt);
		$this->setTipo ($tipo); 
		$this->setLista ($lista); 
		$this->setSelectable();
		$this->setShowKey($showKey);
		$this->setMultiple($multiple);
	}
	
	/**
	 * Devuelve el código HTMl para mostrar el combo en pantalla. 
	 * Básicamente es un tag SELECT dentro de una fila de una tabla. 
	 */
	public function getHtml()
	{
		if ($this->isHidden())
			return ''; 
			
		$id  = $this->getId();
		$txt = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
		
		$cssTxt = $this->getCssTxt(); 
		$cssInput = $this->getCssInput();
		
		$disabled = $this->isEnabled() ? '' : 'disabled';
		
		$onChange = is_null($this->getOnChange()) ? '' : 'onchange="eventHook(\'' . $this->getOnChange() .'\');"';
		
		$size = is_null($this->getSize())?'':" size='".$this->getSize()."' ";
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
			
		$mult = $this->getMultiple();
		
		$cssClass = $this->isMonospace() ? 'abm-combo-monospace' : 'abm-combo';
		
		if ($this->nombre == ''){ $nombre = $id;}
		else { $nombre = $this->nombre;}
		
		$html = "<tr><td class='label $cssTxt'>$txt</td><td class='input $cssInput'>";
		$html = $html . "<SELECT class='$cssInput $cssClass' $mult name='$nombre' id='$id' $disabled $size $onChange >";
		
		if (! $this->isObligatorio()) { 
			$html = $html . (is_null($this->getValue()) ? "<option selected></option>" : "<option></option>");
		}
		
		foreach ($this->lista as $key => $valor) {
			$value = $this->getValue();
			
			if (! is_null($value) && ($value == $key)) { 
				$html = $html . "<option selected";
			} else {
				$html = $html . "<option";
			}
			$html = $html . " value='$key'>";
			
			if ($this->isMonospace())
				$valor = str_replace(' ', '&nbsp;', $valor);	// para que respete los espacios en blanco
			
			if ($this->isShowKey())
				$html =	$html . $key . ' - ' . $valor . "</option>"; 
			else
				$html =	$html . $valor . "</option>";
		}
		
		$html = $html . "</SELECT> $help";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		$html = $html . "</td></tr>";
		return $html; 
	}	
	
	
	//---- getters && setters ----//
	public function getLista() {
		return $this->lista; 
	}
	public function setLista ($lista) {
		$this->lista = $lista; 
	}
	public function isShowKey() {
		return $this->showKey; 
	}	
	public function setShowKey($showKey) {
		$this->showKey = $showKey; 
	}
	public function setSize($size) {
		$this->size = $size;
	}
	public function getSize() {
		return $this->size; 
	}
	public function isMonospace() {
		return $this->monospace; 
	}
	public function setMonospace($monospace = true) {
		$this->monospace = $monospace; 
	}
	public function setMultiple($multiple) {
		$this->multiple = $multiple;
	}
	
	public function getMultiple() {
		return $this->multiple; 
	}
	
	public function setNombre($nombre) {
		$this->nombre = $nombre;
	}
	
	public function getNombre() {
		return $this->nombre; 
	}
	
	
	
	
}