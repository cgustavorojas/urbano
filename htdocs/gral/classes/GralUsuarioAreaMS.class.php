<?php
/**
 * @package gral
 */

/**
 * Asocia una o varias areas a un usuario.
 * Muestra la lista de areas (evitando las que el usuario ya tiene asociadas) y permite seleccionar
 * una o varias. Por cada area seleccionada, inserta un registro en gral_usuario_area.  
 *
 * @package gral
 */
class GralUsuarioAreaMS extends MultiSelectScreen
{
	private $id_usuario; 
	
	public function init()
	{
		$this->id_usuario = $_REQUEST['id_usuario'];
		 
		$this->setTitulo ('Seleccione las areas a asociar a este usuario'); 
		
		$this->setSql ('SELECT id_area, cod_movdoc as "Pentragrama", txt as "Descripción" 
							FROM gral_area a 
							WHERE a.id_area NOT IN (SELECT id_area FROM gral_usuario_area WHERE id_usuario = ' . $this->id_usuario . ')
							AND #FILTROS# 
							ORDER BY cod_movdoc');
		
		$f = new FiltroString ('Movdoc', 'cod_movdoc', 5);
		$f->setToUpper();
		$this->addFiltro($f);
		
		$f = DefaultFiltros::like ('Descripción', 'lower(a.txt)', 30);
		$this->addFiltro($f, 2);
		
		parent::init();
	}
	
	/**
	 * Por cada area seleccionada, ingresa un registro en gral_usuario_area
	 */
	public function guardar()
	{
		foreach ($this->getSelected() as $id_area) {
			Database::execute ('INSERT INTO gral_usuario_area (id_usuario, id_area) VALUES (?, $2)', array ($this->id_usuario, $id_area));		
		}
		return true; 
	}
	
}