<?php
/**
 * @package abm
 */

/**
 * Caso particular de pantalla de query utilizada para seleccionar un registro de un conjunto. 
 * Actualmente no tiene casi diferencias con QueryScreen salvo algún botón menos, pero se deja ya definida la clase
 * específica para el día de mañana poder hacer manejos especiales que difieran según sea una pantalla de consulta
 * general o una selección específica. 
 *
 * @package abm
 */
class LookupScreen extends QueryScreen
{
	public function init()
	{
		$this->setBtnConfigurar(false);
		$this->setBtnImprimir(false);
		$this->setBtnExportar(false);  
		parent::init(); 
	}
}