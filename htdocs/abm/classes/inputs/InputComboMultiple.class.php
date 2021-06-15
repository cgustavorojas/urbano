<?php
/**
 * @package abm
 */

/**
 * Input de tipo combo donde la lista de valores sale de una consulta SQL.
 * 
 * @property string	$sql	Consulta SQL para obtener la lista de valores. Debe retornar exactamente 2 columnas: la primera
 * 							es la clave y la segunda el valor para mostrar.  
 * @package abm
 */
class InputComboMultiple extends InputCombo
{
		
	public function __construct ($txt, $campo, $lista, $size = 7, $tipo = Tipo::NUMBER, $showKey = false)
	{
		parent::__construct($txt, $campo, array(), $tipo, $showKey);
		$this->setSize($size); 	
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
			
		$cssClass = $this->isMonospace() ? 'abm-combo-monospace' : 'abm-combo';
		
		$html = "<tr><td class='label $cssTxt'>$txt</td><td class='input $cssInput'>";
		$html = $html . "<SELECT class='$cssInput $cssClass' name='".$id."[]' id='$id' multiple $size $disabled $onChange >";
		
		if (! $this->isObligatorio()) { 
			$html = $html . (is_null($this->getValueAsArray()) ? "<option selected></option>" : "<option></option>");
		}
		
		foreach ($this->getLista() as $key => $valor) {
			$value = $this->getValueAsArray();
			
			if (! is_null($value) && (in_array($key,$value))) { 
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

	public function parseValue($value)
	{
		if (is_null($value)) {
			$this->setValue(null);
			return; 
		}
		if (is_array($value)) {
			if (count($value) == 0) {
				$this->setValue(null);
				return; 
			}

			$value = implode (',', $value);

		} else 
		{
			$v = trim ($value);
			if (strlen($v) == 0) {
				$this->setValue(null);
				return; 
			}
		}

		$this->setValue($value);
	}

	/**
	 * Agrega en el ParamStmt recibido como parámetro, el valor correspondiente a este filtro. 
	 * Sirve tanto si se recibe un InsertStmt o un UpdateStmt. 
	 */
	public function fillParameter(ParamStmt $stmt)
	{
		if (! $this->isReadOnly()) {
			$stmt->add ($this->getCampo(), $this->getValue(), $this->getTipo());
			// TODO (maybe): $stmt->add ($this->getCampo(), $this->getValueAsSql(), $this->getTipo());
		}
	}
	
	//---- getters && setters ----// 
	
	public function getValueAsArray()
	{	
		if (is_null($this->getValue()))
			return array(); 
			
		return explode(',', $this->getValue());	
	}
	
	public function getValueAsSql()
	{
		switch ($this->getTipo())
		{
				case Tipo::DATE:
				case Tipo::STRING:
				case Tipo::TIMESTAMP:
					foreach ($this->getValueAsArray() as $v)
					{	$aux[] = "'" . $v . "'";	}
					$value = implode (',', $aux); unset ($aux, $v); 
					break;
				case Tipo::MONEY:
				case Tipo::NUMBER:
					$value = $this->getValue(); 
					break;
		}
		return $value ; 
	} 
}