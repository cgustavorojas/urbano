<?php
/**
 * @package gral
 */

/**
 * Da de alta un nuevo usuario.
 *  
 * @package gral
 */
class GralUsuarioAlta extends AltaScreen
{
	public function init()
	{
		$this->setTitulo ('Agregar Usuario');
		
		$this->setPermiso('GRAL_USUARIO_ALTA');
		
		$this->setLogEvento ('gral_usr.add');
		$this->setLogDetallado();
		
		$this->setTabla('gral_usuario');
		$this->setSerialCol ('id_usuario');
		$this->setForwardView('GralUsuarioView');

        
		$i = new InputString('Usuario', 'usuario', 10, 25, true);
		$i->setToLower();
		$this->addInput($i);

        $i = new InputString('Mostrar como', 'txt', 50, 120);
		$this->addInputOb($i);

        $i = new InputString('Nombre', 'nombre', 50, 100);
		$this->addInput($i);

        $i = new InputString('Apellido', 'apellido', 50, 100);
		$this->addInput($i);

        $i = new InputString('Teléfono', 'telefono', 50, 100);
		$this->addInput($i);

		$i = new InputString('Celular', 'celular', 50, 100);
		$this->addInput($i);
				
		$i = new InputString('Email', 'email', 50, 100);
		$this->addInput($i);

		$i = new InputNumber('Documento', 'dni',  10, 8);
		$this->addInput($i);
		
		
        parent::init();
	}
	
	public function validar()
	{
		if (!parent::validar())
			return false; 
			
		$usuario = $this->getInputs()->getValue('usuario');
		
		$count = Database::simpleQuery('SELECT COUNT(*) FROM gral_usuario WHERE usuario = ?', $usuario); 

		if ($count > 0) {
			$this->setError ('El usuario ya existe'); 
			return false; 
		}
		
		return true; 
	}

        /* public function afterGuardar(){

		//toma el id de la actividad y lo encripta con md5
		$id_user = $this->getSerialId();

		$sql = 'Select count(*) from gral_usuario_jurisdiccion WHERE id_usuario = ?';

                $params = array($id_user);

		$count = Database::execute($sql,$params);

                if ($count > 0 ) {

                    $sql = 'DELETE FROM gral_usuario_jurisdiccion WHERE id_usuario = ?';

                    $params = array($id_user);

		    Database::execute($sql,$params);

                   $sql = 'INSERT INTO gral_usuario_jurisdiccion (id_usuario, id_jurisdiccion)
                       (Select ?, id from gral_jurisdiccion)';

                   $params = array($id_user);

		   Database::execute($sql,$params);
                };
                   

		parent::afterGuardar();
	}
         */

}
