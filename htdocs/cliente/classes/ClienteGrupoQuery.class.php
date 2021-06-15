<?php
/**
 * @package cliente
 */

/**
 * Administración de grupos de clientes. 
 * Muestra la lista de grupos de clientes. Permite agregar, modificar, eliminar. 
 * @package cliente
 */
class ClienteGrupoQuery extends QueryScreen
{
	public function init()
	{
		$this->setPermiso('CLIENTE_MENU_GRUPO');
				
		$this->setSql('SELECT   cliente_grupo.id_grupo,
                                cliente_grupo.nombre
						FROM 
								cliente_grupo 
                        where #FILTROS#	');
								
								
		
		$this->addOrder ('Nombre', 'nombre');
		
		$f = new FiltroMultiSearch('Búsqueda');
        $f->add('cliente_grupo.nombre');
        $this->addFiltro($f,1);
        

	
		$this->addCol(new ColNumber('ID', 'id_grupo'));
		$this->addCol(new ColString('Nombre', 'nombre'));
        

        $this->addAction (DefaultActions::alta('ClienteGrupoAlta'), 'CLIENTE_MENU_GRUPO');

    	$this->addActionItem(DefaultActions::view('ClienteGrupoView', 'id_grupo'), 'CLIENTE_MENU_GRUPO');
		
		$this->addActionItem(DefaultActions::edit('ClienteGrupoEdit', 'id_grupo'), 'CLIENTE_MENU_GRUPO');
		
		$a = DefaultActions::quickDelete('cliente_grupo', 'id_grupo', true, 'cliente_grupo.del');
		$this->addActionItem($a, 'CLIENTE_MENU_GRUPO');

		
		
		
		parent::init();
	}
}