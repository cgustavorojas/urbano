<?php
/**
 * @package cliente
 */

/**
 * Modificación de un grupo de clientes. 
 * 
 * @package cliente
 */
class ClienteGrupoEdit extends EditScreen
{
	public function init()
	{
		$this->setTitulo ( 'Modificar Grupo');
		$this->setTabla ( 'cliente_grupo');
        $this->setCampo('id_grupo');
		
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

        $cant = Database::simpleQuery('select count(*) 
                                       from cliente_grupo 
                                       where trim(upper(nombre)) =trim(upper(?))
                                       and id_grupo <> ?',array($inputs->getValue('nombre'),$this->getPkValue()));

        if ($cant > 0){

            $this->setError('El grupo ingresado ya existe.');
            return false;
        }

        return true;

    }


}