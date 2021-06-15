<?php
/**
 * @package cliente
 */

/**
 * Visualización de los datos de un grupo y sus clientes
 * 
 * @package cliente
 */
class ClienteGrupoView extends ViewScreen
{
	public function init()
	{
		$this->setPermiso('CLIENTE_MENU_GRUPO');
		$this->setTitulo ('Detalle del Grupo');
		$this->setSql('SELECT   cliente_grupo.id_grupo,
                                cliente_grupo.nombre
                        FROM 
                                cliente_grupo 
								
						WHERE cliente_grupo.id_grupo = '.$_REQUEST['id']);

		
		$g = new ListaColumns('');
		$g->add (new ColString('Nombre'));
		$this->addGrupo($g);
		
		
		$t = new TabQuery ('Clientes');
		
		$t->setSql ('SELECT cliente_cliente.id_cliente,
                            cliente_cliente.nombre,
                            cliente_cliente.apellido,
                            cliente_cliente.email
                    
                    FROM 
                            cliente_cliente 
                    where  cliente_cliente.id_grupo = '.$_REQUEST['id']);
		
        $t->addCol (new ColNumber('Id','id_cliente'));
		$t->addCol (new ColString('Nombre'));
		$t->addCol (new ColString ('Apellido','apellido'));
		$t->addCol (new ColString('Email', 'email'));
		
		
		$this->addTab ($t);

		parent::init();
	}
	
	
}