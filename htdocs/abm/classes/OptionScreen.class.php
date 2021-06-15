<?php
/**
 * @package abm
 */

/**
 * Encapsula una pantalla muy sencilla que muestra al usuario una lista de opciones con radio buttons
 * y en función de la opción que selecciona, lo redirige a otra pantalla.
 * 
 * Ejemplo: frente a un botón que diga "Agregar animal", puedo definir una pantalla que sea: 
 * 
 * 		Seleccione el animal que quiere dar de alta: 
 * 
 * 		  ( ) Perro      => abm/alta.php?screen=PerroAltaScreen
 *		  ( ) Gato       => abm/alta.php?screen=GatoAltaScreen
 * 		  ( ) Tortuga    => abm/alta.php?screen=TortugaAltaScreen
 *   
 * @package abm
 */
class OptionScreen extends Screen
{
	private $opciones = array();
	private $urls     = array();
	private $helps    = array();
	private $msgInfo; 
	

	public function addOpcion ($opcion, $url, $help = null)
	{
		$this->opciones[] = $opcion; 
		$this->urls[] = $url; 
		$this->helps[] = $help; 
	}

	public function getCount()
	{
		return count($this->opciones);
	}	
	
	public function getOpciones() {
		return $this->opciones; 
	}
	
	public function getOpcion($i) {
		return $this->opciones[$i];
	}

	public function getUrl($i) {
		return $this->urls[$i];
	}
	
	public function getHelp($i) {
		return $this->helps[$i];
	}
	
	public function getMsgInfo(){
		return $this->msgInfo; 
	}
	public function setMsgInfo ($msgInfo) {
		$this->msgInfo = $msgInfo; 
	}
	
}