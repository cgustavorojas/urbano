<?php
/**
 * @package gral
 */

/**
 * Modifca los datos un usuario existente.
 *  
 * @package gral
 */
class GralUsuarioEdit extends EditScreen
{
	public function init()
	{
		$this->setTitulo ('Editar Datos del Usuario');
		
		$this->setPermiso('GRAL_USUARIO_EDIT');
		
		$this->setTabla('gral_usuario');
		$this->setCampo ('id_usuario');
		$this->setLogEvento ('gral_usr.edit');
		$this->setLogDetallado();
		
		$i = new InputString('Usuario', 'usuario', 25, 25, true);
		$i->setToLower();
		$this->addInput($i);

        $i = new InputString('Nombre', 'nombre', 50, 100);
		$this->addInput($i);

        $i = new InputString('Apellido', 'apellido', 50, 100);
		$this->addInput($i);

        $i = new InputString('Mostrar como', 'txt', 50, 100);
		$this->addInput($i);

        $i = new InputString('Email', 'email', 50, 100);
		$this->addInput($i);

        $i = new InputString('Teléfono', 'telefono', 50, 100);
		$this->addInput($i);
		
		$i = new InputString('Celular', 'celular', 50, 100);
		$this->addInput($i);

		$i = new InputNumber('Documento', 'dni',  10, 8);
		$this->addInput($i);
		
       parent::init();
	}


}