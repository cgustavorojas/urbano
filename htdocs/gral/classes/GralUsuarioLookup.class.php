<?php
/**
 * @package gral
 */

class GralUsuarioLookup extends LookupScreen
{
	public function init()
	{
		$this->setTitulo ('Seleccione un usuario');

		$this->setSql('SELECT id_usuario, usuario, apellido, nombre, txt  
							FROM gral_usuario u
							WHERE id_usuario > 0 AND  #FILTROS#');	// id_usuario > 0 para que no puedan seleccionar al usuario admin

		$this->addOrder ('Usuario', 'usuario');
		$this->addOrder ('ID', 'id desc');
		$this->addOrder ('Nombre', 'nombre, apellido');
		$this->addOrder ('Apellido', 'apellido, nombre');
		$this->addOrder ('Fecha Creación', 'fecha_alta desc');
		
		$f = new FiltroString('Usuario', 'usuario', 10, 10);
		$f->setToLower();
		$f->setOperador('LIKE');
		$f->setAppend('%');
		$this->addFiltro($f);
		
		$f = new FiltroString('Apellido', 'upper(apellido)');
		$f->setToUpper();
		$f->setOperador("LIKE");
		$f->setAppend("%");
		$this->addFiltro($f, 2);
		
		$f = new FiltroString('Nombre', 'upper(nombre)');
		$f->setToUpper();
		$f->setOperador("LIKE");
		$f->setAppend("%");
		$this->addFiltro($f, 2);
		
		$f = new FiltroNumber ('ID', 'id_usuario', 5, 5);
		$this->addFiltro($f, 3);
		
		$this->addCol(new ColNumber('ID', 'id_usuario'));
		$this->addCol(new ColString('Usuario'));
		$this->addCol(new ColString('Apellido'));
		$this->addCol(new ColString('Nombre'));
		$this->addCol(new ColString('Mostrar como', 'txt'));

		$this->addActionItem(DefaultActions::seleccionar('id_usuario', 'id_usuario'));
		
		parent::init(); 
	}
}