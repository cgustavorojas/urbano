<?php
/**
 * @package abm
 */
  

/**
 * Control para el ingreso de Horas y Minutos a través de 2 combos.
 * 
 * @package abm
 */
class InputHora extends Input
{
	private $hora;
	private $minuto;
	
	public function __construct ($txt, $campo = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setTipo(Tipo::DATE);
		$this->setSelectable();
	}

	/**
	 * Setea el valor en la fecha actual.
	 */
	public function setValueToday()
	{
		$this->setValue(date('H:i'));
	}

	public function validar()
	{
		$this->setError(NULL);
		
		if  ( $this->hora < 0 || $this->hora > 23 || $this->minuto < 0  || $this->minuto > 59) {
			$this->setError ("Hora Inválida");
			return false;
		}
		
		return true;
	}

	public function getHtml()
	{
		if ($this->isHidden())
		return '';
			
		$id  = $this->getId();
		$txt = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt();
		
		$idHora = $id . "_hora";
		$idMinuto = $id . "_minuto";
		
		$hora = $this->hora;
		$minuto = $this->minuto;

		$disabled = $this->isEnabled() ? '' : 'disabled';

		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = "<tr><td class='label'>$txt</td><td class='input'>";
		
		$html = $html . "<select class='abm-combo' id='$idHora' name='$idHora' $disabled >";
		for ($i = 0 ; $i <= 23 ; ++$i) {
			$descripcion = str_pad($i, 2, '0',STR_PAD_LEFT);
			$html = $html . "<option ";
			if ($i == $hora) { 
				$html = $html . " selected "; 
			}
			$html = $html . " value='$i'>$descripcion</option>";
		}
		$html = $html . "</select>";

		$html = $html . " : ";		
		$html = $html . "<select class='abm-combo' id='$idMinuto' name='$idMinuto' $disabled >";
		for ($i = 0 ; $i <= 59 ; ++$i) {
			$descripcion = str_pad($i, 2, '0',STR_PAD_LEFT);
			$html = $html . "<option " ;
			if ($i == $minuto) { 
				$html = $html . " selected "; 
			}
			$html = $html . "value='$i'>$descripcion</option>";
		}
		$html = $html . "</select> $help";
		
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
		
		$idHora = $id . "_hora";
		$idMinuto = $id . "_minuto";

		$this->hora = isset($_REQUEST[$idHora]) ? Utils::nullIfBlank($_REQUEST[$idHora]) : null;
		$this->minuto = isset($_REQUEST[$idMinuto]) ? Utils::nullIfBlank($_REQUEST[$idMinuto]) : null;

	}

	/**
	 * Setea el valor del INPUT.
	 * @param int $value Valor a setear (en formato string)
	 * @return void
	 */
	public function setValue($value)
	{
		if (is_null($value)) {
			$this->hora = null;
			$this->minuto = null;
		} else {
			$arr = getdate(strtotime($value));
			$this->hora = $arr['hours'];
			$this->minuto = $arr['minutes'];
		}
	}

	/**
	 * Devuelve la fecha ingresada.
	 * @return string La fecha en formato (Y-m-d H:i)
	 */
	public function getValue() {
		return $this->hora . ':' . $this->minuto . ':00';
	}
	
}