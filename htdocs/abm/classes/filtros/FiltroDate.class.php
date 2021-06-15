<?php
/**
 * @package abm
 */

/**
 * Filtra un campo de tipo fecha. 
 * Cambiando el operador, puede funcionar como fecha desde o fecha hasta. 
 *
 * @package abm
 */
class FiltroDate extends Filtro
{
	private $dia; 
	private $mes; 
	private $anio; 
		
	public function __construct ($txt, $campo = NULL, $operador = '=')
	{
		parent::__construct();
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setOperador($operador);	
		$this->setSelectable();	
	}
	
	/**
	 * Devuelve el código HTML para mostrar el filtro en pantalla. 
	 * Asume que está dentro de una tabla (devuelve código encerrado en un TR).
	 * @return string
	 * @see abm/classes/Filtro#getHtml()
	 */
	public function getHtml()
	{
			
		$id  = $this->getId();
		$txt = $this->getTxt(); 
		
		$idDia = $id . "_dia";
		$idMes = $id . "_mes";
		$idAnio = $id . "_anio";
		
		$dia = $this->dia; 
		$mes = $this->mes;
		$anio = $this->anio;
		
		$html = "<tr><td class='label'>$txt</td><td class='input'>";

		$html = $html . "<input id='$idDia' name='$idDia' type='text' size='2' maxlength='2' class='input_date_dia' value='$dia'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idMes' name='$idMes' type='text'  size='2' maxlength='2' class='input_date_mes' value='$mes'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idAnio' name='$idAnio' type='text'  size='4' maxlength='4' class='input_date_anio' value='$anio'>";
		
		$html = $html . "&nbsp;<a onclick='return getCalendar($(\"$idDia\"),$(\"$idDia\"),$(\"$idMes\"),$(\"$idAnio\"));' href='javascript: void(0);'>";
		$html = $html . "<img border='0' src='../include/calendario/calendar.png'/></a>";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		$html = $html . "</td></tr>";
		return $html;
	} 

	public function parseRequest()
	{
		$id = $this->getId(); 
		
		$idDia = $id . "_dia";
		$idMes = $id . "_mes";
		$idAnio = $id . "_anio";
		
		$this->dia  = isset($_REQUEST[$idDia])  ? Utils::nullIfBlank($_REQUEST[$idDia])  : null; 
		$this->mes  = isset($_REQUEST[$idMes])  ? Utils::nullIfBlank($_REQUEST[$idMes])  : null; 
		$this->anio = isset($_REQUEST[$idAnio]) ? Utils::nullIfBlank($_REQUEST[$idAnio]) : null; 
	}	
	
	/**
	 * Setea el valor del filtro. 
	 * @param int $value Valor a setear (en formato string) 
	 * @return void
	 */
	public function setValue($value)
	{
		if (is_null($value)) { 
			$this->dia = null; 
			$this->mes = null; 
			$this->anio = null;
		} else {
			$arr = getdate(strtotime($value));
			$this->dia = $arr['mday'];
			$this->mes = $arr['mon'];
			$this->anio = $arr['year'];
		}
	}	
	
	/**
	 * Devuelve la fecha ingresada.
	 * @return string La fecha en formato ISO (Y-m-d)
	 */
	public function getValue() {
		if (is_null($this->dia))
			return null; 
			
		if (strlen($this->dia) == 0)
			return null;
			
		return $this->anio . '-' . $this->mes . '-' . $this->dia; 
	}	
	
	public function getValueTxt() {
		$v = $this->getValue(); 
		
		if (is_null($v))
			return null;

		$pref = UserPref::load();
		return $pref->toString($v, Tipo::DATE);
	}
	
	public function validar()
	{
		$this->setError(NULL);
		
		if (is_null($this->dia) && is_null($this->mes) && is_null($this->anio)) {
			if ($this->isObligatorio()) {
				$this->setError("requerido");
				return false;
			} else {
				return true;	// está vacío y no es obligatorio, no tiene sentido chequear nada más
			} 
		}
		
		if (is_null($this->dia) || is_null($this->mes) || is_null($this->anio)) {
			$this->setError("fecha incompleta");
			return false; 
		}
		
		if (! (is_numeric($this->dia) && is_numeric($this->mes) && is_numeric($this->anio))) {
			$this->setError ("fecha inválida");
			return false; 
		}
				
		if (! checkdate($this->mes, $this->dia, $this->anio)) {
			$this->setError ("fecha inválida");
			return false; 
		}
		return true; 
	}
		
	/**
	 * Setea el valor en la fecha actual.
	 */
	public function setValueToday()
	{
		$this->setValue(date('Y-m-d'));	
	}
	 	
	/**
	 * Devuelve el ID del INPUT correspondiente al día.
	 * @return int   
	 */
	public function getSelectableId() {
		return $this->getId() . "_dia";
	}	
}