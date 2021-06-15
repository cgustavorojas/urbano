<?php
/**
 * @package abm
 */

/**
 * Tipo especial de columna que toma 2 campos (un código numérico y una descripción) y arma una columna codigo - descripción. 
 * @package abm
 */
class ColCodigoTxt extends Column
{
	private $campoTxt; 
	
	public function __construct ($txt, $campo = NULL, $campoTxt = NULL, $maxLength = NULL)
	{
		$this->setTxt($txt);
		$this->setCampo (is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);
		$this->setCampoTxt (is_null($campoTxt) ? $this->getCampo() . "_txt" : $campoTxt);		
		$this->setTipo (Tipo::STRING);
		$this->setAlign (Column::LEFT); 
		$this->setPrintWidth(20);
		$this->setMaxLength($maxLength);
	}
	
	public function getValueHtml($row, UserPref $userPrefs)
	{
		$maxLength = $this->getMaxLength();
		$campo = $row[$this->getCampo()];
		$campoTxt = $row[$this->getCampoTxt()]; 
		
		$s = '';
		if (! is_null($campo))
			$s = trim($campo);
			
		if (! is_null ($campoTxt)) 
			$s = $s . ' - ' . trim($campoTxt);
		
		if ( ! is_null($maxLength) && ($maxLength > 0) && (strlen($s) > $maxLength)) {
			$s = substr($s, 0, $maxLength - 3) . '...';
		}				
				
		$href = $this->buildHref($row);
		
		if (! is_null($href)) {
			$s = "<a href='$href'>$s</a>";
		}
		
		return $s; 
	}
	
	//----- getters && setters -----//
	
	public function getCampoTxt() {
		return $this->campoTxt; 
	}
	public function setCampoTxt($campoTxt) {
		$this->campoTxt = $campoTxt; 
	}
}