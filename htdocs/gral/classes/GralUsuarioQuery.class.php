<?php
/**
 * @package gral
 */

/**
 * Administración de usuarios. 
 * Muestra la lista de usuarios. Permite agregar, modificar, eliminar, asignar perfiles, etc. 
 * @package gral
 */
class GralUsuarioQuery extends QueryScreen
{
	public function init()
	{
		$this->setTitulo ('Administración de usuarios');
		
		$this->setPermiso('GRAL_USUARIOS');
				
		$this->setSql('SELECT u.id_usuario, u.usuario, u.apellido, u.nombre, u.txt, u.email,  
								u.telefono, u.dni,u.celular, u.is_admin 
						FROM 
								gral_usuario u
								where #FILTROS#	');
								
								
		
		$f = new FiltroMultiSearch('Búsqueda');
		$f->add('u.usuario');
		$f->add('u.nombre');
		$f->add('u.apellido');
		$f->add('u.email');
		$this->addFiltro($f,1);
								
		
		$this->addCol(new ColNumber('ID', 'id_usuario'));
		$this->addCol(new ColString('Usuario', 'usuario'));
		$this->addCol(new ColString('Apellido', 'apellido'));
		$this->addCol(new ColString('Nombre', 'nombre'));
		$this->addColInactive(new ColString('Email', 'email'));
		

       
		parent::init();
	}
}