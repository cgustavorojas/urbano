<?php
/**
 * @package cliente
 */

/**
 * Alta de un grupo de clientes. 
 * 
 * @package cliente
 */
class ClienteGrupoAlta extends AltaScreen
{
	public function init()
	{
		$this->setTitulo ( 'Agregar Grupo');
		$this->setTabla ( 'cliente_grupo');
		
		$i = new InputString ('Nombre', 'nombre', 50, 100);
		$i->setObligatorio();
		$this->addInput ($i); 

        		
		parent::init();
	}

    public function validar()
    {
        if (!parent::validar()){

            return false;
        }

        $inputs = $this->getInputs();

        $cant = Database::simpleQuery('select count(*) from cliente_grupo where trim(upper(nombre)) =trim(upper(?))',$inputs->getValue('nombre'));

        if ($cant > 0){

            $this->setError('El grupo ingresado ya existe.');
            return false;
        }

        return true;

    }


}