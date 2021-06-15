<?php
/**
 * @package abm
 */

/**
 * Filtra que un campo de tipo fecha esté dentro de un cierto rango. 
 * Pide al usuario fecha desde / fecha hasta
 * @package abm
 */
class FiltroDateRange extends Filtro
{
	private $diaD;
	private $mesD;
	private $anioD;

	private $diaH;
	private $mesH;
	private $anioH;

	public function __construct($txt, $campo = NULL)
	{
		parent::__construct();
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
	}
	
		public function getHtml()
	{
			
		$id  = $this->getId();
		$txt = $this->getTxt(); 
		
		// Desde
		$idDiaD = $id . "_dia_d";
		$idMesD = $id . "_mes_d";
		$idAnioD = $id . "_anio_d";

		$diaD = $this->diaD; 
		$mesD = $this->mesD;
		$anioD = $this->anioD;

		// Hasta
		$idDiaH = $id . "_dia_h";
		$idMesH = $id . "_mes_h";
		$idAnioH = $id . "_anio_h";

		$diaH = $this->diaH; 
		$mesH = $this->mesH;
		$anioH = $this->anioH;
		
		$html = "<tr><td class='label'>$txt</td><td class='input'>";

		$html = $html . "entre ";
		$html = $html . "<input id='$idDiaD' name='$idDiaD' type='text' size='2' maxlength='2' class='input_date_dia' value='$diaD'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idMesD' name='$idMesD' type='text'  size='2' maxlength='2' class='input_date_mes' value='$mesD'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idAnioD' name='$idAnioD' type='text'  size='4' maxlength='4' class='input_date_anio' value='$anioD'>";
		
		$html = $html . "&nbsp;<a onclick='return getCalendar($(\"$idDiaD\"),$(\"$idDiaD\"),$(\"$idMesD\"),$(\"$idAnioD\"));' href='javascript: void(0);'>";
		$html = $html . "<img border='0' src='../include/calendario/calendar.png'/></a>";

		$html = $html . " y ";
		$html = $html . "<input id='$idDiaH' name='$idDiaH' type='text' size='2' maxlength='2' class='input_date_dia' value='$diaH'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idMesH' name='$idMesH' type='text'  size='2' maxlength='2' class='input_date_mes' value='$mesH'>";
		$html = $html . "&nbsp;/&nbsp;";
		$html = $html . "<input id='$idAnioH' name='$idAnioH' type='text'  size='4' maxlength='4' class='input_date_anio' value='$anioH'>";
		
		$html = $html . "&nbsp;<a onclick='return getCalendar($(\"$idDiaH\"),$(\"$idDiaH\"),$(\"$idMesH\"),$(\"$idAnioH\"));' href='javascript: void(0);'>";
		$html = $html . "<img border='0' src='../include/calendario/calendar.png'/></a>";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		$html = $html . "</td></tr>";
		return $html;
	} 
	
	public function getWhereClause()
	{
		$where = '';
		if (! $this->isValid()) {
			return ""; 
		}
		
		if (! $this->isAutoFilter()) {
			return ""; 
		}
		
		//die($this->getValueDesde());
		if (! is_null($this->getValueDesde()) && ! is_null($this->getValueHasta())) {
			
			$where = $this->getWhere(); 
			$valueDesde = Utils::sqlEscape($this->getValueDesde(), $this->getTipo());
			$valueHasta = Utils::sqlEscape($this->getValueHasta(), $this->getTipo());
			
			if (is_null($where)) {
				$campo = $this->getCampo(); 
				//$operador = $this->getOperador();
	            
				return " AND $campo BETWEEN $valueDesde AND $valueHasta ";
			} else {
				$where = str_replace('?', $value, $where);
				return "AND $where";
			}
		}
		
		return "";
		
	}
	
	
	public function getValueDesde() {
		
		if (is_null($this->diaD))
			return null; 
			
		if (strlen($this->diaD) == 0)
			return null;
			
		return $this->anioD . '-' . $this->mesD . '-' . $this->diaD; 
	}	
	
	
	public function getValueHasta() {
		if (is_null($this->diaH))
			return null; 
			
		if (strlen($this->diaH) == 0)
			return null;
			
		return $this->anioH . '-' . $this->mesH . '-' . $this->diaH; 
	}	

public function parseRequest()
	{
		$id = $this->getId(); 
		
		$idDiaD = $id . "_dia_d";
		$idMesD = $id . "_mes_d";
		$idAnioD = $id . "_anio_d";

		$idDiaH = $id . "_dia_h";
		$idMesH = $id . "_mes_h";
		$idAnioH = $id . "_anio_h";
	
		$this->diaD  = isset($_REQUEST[$idDiaD])  ? Utils::nullIfBlank($_REQUEST[$idDiaD])  : null; 
		$this->mesD  = isset($_REQUEST[$idMesD])  ? Utils::nullIfBlank($_REQUEST[$idMesD])  : null; 
		$this->anioD = isset($_REQUEST[$idAnioD]) ? Utils::nullIfBlank($_REQUEST[$idAnioD]) : null; 
	
		$this->diaH  = isset($_REQUEST[$idDiaH])  ? Utils::nullIfBlank($_REQUEST[$idDiaH])  : null; 
		$this->mesH  = isset($_REQUEST[$idMesH])  ? Utils::nullIfBlank($_REQUEST[$idMesH])  : null; 
		$this->anioH = isset($_REQUEST[$idAnioH]) ? Utils::nullIfBlank($_REQUEST[$idAnioH]) : null; 
		
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
		
		if (is_null($this->diaD) && is_null($this->mesD) && is_null($this->anioD)) {
			if ($this->isObligatorio()) {
				$this->setError("requerido");
				return false;
			} else {
				return true;	// está vacío y no es obligatorio, no tiene sentido chequear nada más
			} 
		}
		
	if (is_null($this->diaH) && is_null($this->mesH) && is_null($this->anioH)) {
			if ($this->isObligatorio()) {
				$this->setError("requerido");
				return false;
			} else {
				return true;	// está vacío y no es obligatorio, no tiene sentido chequear nada más
			} 
		}
		
		
		if (is_null($this->diaD) || is_null($this->mesD) || is_null($this->anioD)) {
			$this->setError("fecha incompleta");
			return false; 
		}
		
		if (is_null($this->diaH) || is_null($this->mesH) || is_null($this->anioH)) {
			$this->setError("fecha incompleta");
			return false; 
		}
		
		
		if (! (is_numeric($this->diaD) && is_numeric($this->mesD) && is_numeric($this->anioD))) {
			$this->setError ("fecha inválida");
			return false; 
		}

	if (! (is_numeric($this->diaH) && is_numeric($this->mesH) && is_numeric($this->anioH))) {
			$this->setError ("fecha inválida");
			return false; 
		}
		
		if (! checkdate($this->mesD, $this->diaD, $this->anioD)) {
			$this->setError ("fecha inválida");
			return false; 
		}
		if (! checkdate($this->mesH, $this->diaH, $this->anioH)) {
			$this->setError ("fecha inválida");
			return false; 
		}
		return true; 
	}
		

	public function getSelectableId() {
		return $this->getId() . "_dia_d";
	}	


	
	
}