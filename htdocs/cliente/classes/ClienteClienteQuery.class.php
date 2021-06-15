<?php
/**
 * @package cliente
 */

/**
 * Administración de clientes. 
 * Muestra la lista de clientes. Permite agregar, modificar, eliminar, asignar perfiles, etc. 
 * @package cliente
 */
class ClienteClienteQuery extends QueryScreen
{
	public function init()
	{
		$this->setPermiso('CLIENTE_MENU_CLIENTE');
				
		$this->setSql('SELECT cliente_cliente.id_cliente,
                              cliente_cliente.nombre,
                              cliente_cliente.apellido,
                              cliente_cliente.email,
                              cliente_grupo.nombre as grupo
						FROM 
								cliente_cliente 
                        JOIN    cliente_grupo using(id_grupo)
                        where #FILTROS#	');
								
								
		$this->addOrder ('ID', 'id_cliente');
		$this->addOrder ('Nombre', 'nombre, apellido');
		$this->addOrder ('Apellido', 'apellido, nombre');
		
		$f = new FiltroMultiSearch('Búsqueda');
        $f->add('cliente_cliente.nombre');
        $f->add('cliente_cliente.apellido');
        $f->add('cliente_cliente.email');
        $this->addFiltro($f,1);
        
		$f = new FiltroComboSql('Grupo','cliente_cliente.id_grupo','select id_grupo,nombre from cliente_grupo order by nombre');
        $this->addFiltro($f,2);


	
		$this->addCol(new ColNumber('ID', 'id_cliente'));
		$this->addCol(new ColString('Nombre', 'nombre'));
        $this->addCol(new ColString('Apellido', 'apellido'));
		$this->addCol(new ColString('Email', 'email'));
		$this->addCol(new ColString('Grupo', 'grupo'));
		

        $this->addAction (DefaultActions::alta('ClienteClienteAlta'), 'CLIENTE_MENU_CLIENTE');

  		
		$this->addActionItem(DefaultActions::edit('ClienteClienteEdit', 'id_cliente'), 'CLIENTE_MENU_CLIENTE');
		
		$a = DefaultActions::quickDelete('cliente_cliente', 'id_cliente', true, 'cliente_cliente.del');
		$this->addActionItem($a, 'CLIENTE_MENU_CLIENTE');
		
		parent::init();
	}
}