<?php
/**
 * @package gral
 */

/**
 * Permite asignar una password nueva a un usuario.
 *  
 * No es el cambio de la propia password sino forzarle una password nueva (sin necesidad de
 * conocer la anterior) a otro usuario. 
 * Esta opción sólo debe ser asignada a administradores del sistema.
 * 
 * Se usa un template de pantalla de alta, pero en realidad la función guardar()
 * está modificada para hacer un UPDATE y cambiar sólo el campo password
 *  
 * @package gral
 */
class GralUsuarioPassword extends AltaScreen
{
	private $id_usuario; 
	
	public function init()
	{
		$this->setTitulo ('Asignar Nueva Contraseña');
		
		$i = new InputString ('Contraseña', 'password', 25, 25, true);
		$i->setValue(substr(md5(rand() . rand() . rand()),1,8));
		$this->addInput ($i);
		$this->id_usuario = $_REQUEST['id_usuario'];
		
		$i = new InputBoolean('Forzar cambio', 'forzar_cambio');
		$i->setValue(true);
		$this->addInput ($i);
		
		$i = new InputBoolean('Enviar mail', 'enviar_mail');
		$i->setValue(true);
		$this->addInput ($i);
		
		parent::init();
	}
	
	public function guardar()
	{
		$passwd = $this->getInputs()->getValue('password');
		$forzar_cambio = $this->getInputs()->getValue('forzar_cambio');
		$enviar_mail   = $this->getInputs()->getValue('enviar_mail');

		if ($forzar_cambio == 'f'){$forzar_cambio = 0;}
		else if ($forzar_cambio == 't'){$forzar_cambio = 1;}
		
		
		
		Database::query('UPDATE gral_usuario SET password = md5(?), forzar_cambio = ? WHERE id_usuario = ?',
			array ($passwd, $forzar_cambio, $this->id_usuario));
			
		Utils::log ('gral_usr.passwd', 'gral_usuario', $this->id_usuario);	
			
		if ($enviar_mail == '1') {
			$rs = Database::query ('SELECT email, txt FROM gral_usuario WHERE id_usuario = ?', $this->id_usuario)->getRow();
			
			$m = new Email(); 
			$m->setSubject('Contraseña Gestión'); 
			$m->addTo ($rs['email'], $rs['txt']);
			$m->setBody ('Su nueva contraseña es: %passwd%');
			$m->addVar ('passwd', $passwd);
			try { 
				$m->send();
			} catch (Exception $e) {
				throw new Exception ('La contraseña se cambió, pero no se pudo enviar mail (' . $e->getMessage() . ')');
			} 
		}
		
		return true;
	}
}