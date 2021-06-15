<?php
/**
 * @package cliente
 */

/**
 * Modificación de un cliente. 
 * 
 * @package cliente
 */
class ClienteClienteEdit extends EditScreen
{
	public function init()
	{
		$this->setTitulo ( 'Modificar Cliente');
		$this->setTabla ( 'cliente_cliente');
        $this->setCampo('id_cliente');
		
		$i = new InputString ('Nombre', 'nombre', 50, 100);
		$i->setObligatorio();
		$this->addInput ($i); 
		
        $i = new InputString ('Apellido', 'apellido', 50, 100);
		$i->setObligatorio();
		$this->addInput ($i); 

        $i = new InputString ('Email', 'email', 50, 100);
		$i->setObligatorio();
		$this->addInput ($i); 
		
        $i = new InputComboSql('Grupo','id_grupo','select id_grupo,nombre from cliente_grupo order by nombre',Tipo::NUMBER);
        $i->setObligatorio();
		$this->addInput ($i); 
				
		$i = new InputTextArea ('Observaciones', 'obs');
		$i->setObligatorio();
		$this->addInput ($i); 

		parent::init();
	}

	public function validar(){

		if (!parent::validar()){

			return false;
		}

		$inputs = $this->getInputs();

        
		if (!filter_var($inputs->getValue('email'), FILTER_VALIDATE_EMAIL)) {
			$this->setError('Email inválido');
			return false;
		}
		
		
		$cant = Database::simpleQuery('select count(*) 
									   from cliente_cliente 
									   where trim(upper(email)) =trim(upper(?))
                                       and id_cliente <> ?',array($inputs->getValue('email'),$this->getPkValue()));

        if ($cant > 0){

            $this->setError('El email ingresado ya existe.');
            return false;
        }

		


		return true;



	}




}