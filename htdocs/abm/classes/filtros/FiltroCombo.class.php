<?php
/**
 * @package abm
 */

/**
 * Filtro básico que permite seleccionar de una lista fija de valores. 
 * 
 * Para una versión similar pero que trae valores de una consulta SQL, 
 * ver FiltroComboSql. 
 * @package abm 
 */
class FiltroCombo extends Filtro
{
	private $lista; 
	private $monospace = false; 
	private $emptyTxt = ''; 
	
	/**
	 * Inicializa el control con los valores más comunes. 
	 *  
	 * @param string $campo Nombre de la columna a filtrar
	 * @param string $txt Leyenda a mostrar 
	 * @param array $valores Lista de valores posibles (array asociativo)
	 */
	public function __construct ($txt, $campo, $lista, $tipo = Tipo::STRING)
	{
		parent::__construct(); 
		
		$this->setCampo($campo);
		$this->setTxt($txt); 
		$this->lista = $lista; 	
		$this->setTipo($tipo); 
		$this->setSelectable();
	}
	
	public function getValueTxt() 
	{
		$value = $this->getValue();
		if (is_null($value))
			return NULL;
		return $this->lista[$value];	
	}
	
	/**
	 * Devuelve el código HTMl para mostrar el filtro en pantalla. 
	 * Básicamente es un tag SELECT dentro de una fila de una tabla. 
	 */
	public function getHtml()
	{
		$cssClass = $this->isMonospace() ? 'abm-combo-monospace' : 'abm-combo';
		
		$onChange = is_null($this->getOnChange()) ? '' : 'onchange="eventHook(\'' . $this->getOnChange() .'\');"';
		
		$id = $this->getId(); 
		
		$html = "<tr><td class='label'>" . $this->getTxt() . "</td><td class='input'>";
		$html = $html . "<SELECT class='$cssClass' name='$id' id='$id' $onChange>";
		
		
		$myvalue = $this->getValue(); 
		
		if (! $this->isObligatorio()) { 
			$txt = $this->getEmptyTxt(); 
			$html = $html . (is_null($this->getValue()) ? "<option value='' selected>$txt</option>" : "<option value=''>$txt</option>");
		}
		
		foreach ($this->lista as $key => $valor) {
			if (! is_null($myvalue) && $myvalue == $key) { 
				$html = $html . "<option selected";
			} else {
				$html = $html . "<option";
			}
			$html = $html . " value='" . $key . "'>";
			
			if ($this->isMonospace())
				$valor = str_replace(' ', '&nbsp;', $valor);	// para que respete los espacios en blanco
			
			$html = $html . $valor . "</option>";
		}
		
		$html = $html . "</SELECT>";
		$html = $html . "</td></tr>";
		return $html; 
	}

	//---- getters && setters ----//
	
	public function getLista() {
		return $this->lista; 
	}
	public function setLista($lista) {
		$this->lista = $lista; 
	}
	public function isMonospace() {
		return $this->monospace; 
	}
	public function setMonospace($monospace = true) {
		$this->monospace = $monospace; 
	}
	
	public function setEmptyTxt($emptyTxt) {
		$this->emptyTxt = $emptyTxt; 
	}
	public function getEmptyTxt() {
		return $this->emptyTxt; 
	}
}