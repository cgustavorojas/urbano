<?php
/**
 * @package gral
 */

/**
 * Edita un parámetro de configuración general del sistema (tabla gral_config). 
 * La PK de la tabla es el código del parámetro, alfabético.
 * Esta pantalla es llamada desde GralConfigQuery
 *
 * @package gral
 */
class GralConfigEdit extends EditScreen
{
	public function init()
	{
		$this->setTitulo ('Editar Parámetro de Configuración');
		
		$this->setPermiso("GRAL_CONFIG");
		
		$this->setTabla ('gral_config');
		$this->setCampo ('id_config');
		$this->setTipo (Tipo::STRING);
		
		$this->addInput(new InputReadOnly ('Código', 'id_config'));
		$this->addInput(new InputReadOnly ('Descripción', 'txt'));	
		$this->addInput(new InputString ('Valor', 'valor', 50, 100, true));
		
		parent::init();
	}
	
	/**
	 * Valida que el valor ingresado (que siempre se ingresa como string) cumpla con los 
	 * requesitos de tipo, mínimo, máximo, etc. 
	 */
	public function validar()
	{
		//TODO: validar valor ingresado según campo tipo, minimo, maximo, maxlength
		return true; 
	}
	
	/**
	 * Como la clase Config guarda en memoria la configuración al momento de login y no la vuelve a refrescar, 
	 * fuerzo un refresco para no obligar al usuario a salir y volver a entrar para ver reflejados los cambios. 
	 */
	public function afterGuardar()
	{
		Config::getInstance()->reload();
	}
}