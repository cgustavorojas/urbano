<?php
/**
 * @package gral
 */

/**
 * Visualización de los datos de un usuario particular, 
 * con tabs para lista de perfiles y unidades ejecutoras asignadas. 
 * 
 * @package gral
 */
class GralUsuarioView extends ViewScreen
{
	public function init()
	{
		$this->setPermiso('GRAL_VIEW_USUARIO');
		$this->setTitulo ('Detalle del Usuario');
		$this->setSql('SELECT u.id_usuario, u.usuario, u.estado, u.fecha_alta, u.fecha_baja,
  								u.apellido, u.nombre, u.txt, u.email,  
								u.telefono, u.dni,  
								u.celular,
								u.is_admin,
								u.fecha_cambio, u.forzar_cambio
						FROM 
								gral_usuario u
								
						WHERE u.id_usuario = '.$_REQUEST['id']);

		$a = DefaultActions::altaCustom('GralUsuarioPassword', 'Cambiar contraseña', array(new ParamCampo('id_usuario')));
		$this->addAction($a, 'GRAL_USUARIO_PASSWD');
		
		$a = DefaultActions::edit ('GralUsuarioEdit', 'id_usuario');
		$this->addAction($a, 'GRAL_USUARIO_EDIT');
		
		$g = new ListaColumns('Datos de Login');
		$g->add (new ColNumber('Usuario #', 'id_usuario'));
		$g->add (new ColString('Usuario'));
		$g->add (new ColString ('Estado'));
		$g->add (new ColDate('Fecha Alta'));
		$g->add (new ColDate('Fecha Baja'));
		$this->addGrupo($g);
		
		$g = new ListaColumns ('Contraseña');
		$g->add (new ColBoolean('Debe cambiarla?', 'forzar_cambio'));
		$g->add (new ColDate('Fecha Últ. cambio', 'fecha_cambio'));
		$this->addGrupo($g, 1);
		
		$g = new ListaColumns ('Datos de la Persona');
		$g->add (new ColString('Apellido'));
		$g->add (new ColString('Nombre'));
		$g->add (new ColString('Mostrar como', 'txt'));
		$g->add (new ColString('Teléfono', 'telefono'));
		$g->add (new ColString('Email'));
		$this->addGrupo($g, 2);
		
		
		$t = new TabQuery ('Perfiles');
		
		$t->setSql ('SELECT up.id_usuario_perfil, 
							s.descripcion as sistema, 
							p.id_perfil, p.descripcion, 
							case when up.with_grant = 0 then \'NO\'
							     when up.with_grant = 1 then \'SI\'
							     else null end as with_grant
						FROM gral_usuario_perfil up
						  INNER JOIN gral_perfil p USING (id_perfil)
						  INNER JOIN gral_sistema s USING (id_sistema) 
						  WHERE up.id_usuario = ? ORDER BY s.descripcion, p.id_perfil');
		
		$t->addCol (new ColString('Sistema'));
		$t->addCol (new ColString ('Perfil', 'descripcion'));
		$t->addCol (new ColString('¿Delegado?', 'with_grant'));
		
		$t->addAction (DefaultActions::mselect('GralUsuarioPerfilMS', 'Agregar', array (new ParamCampo('id_usuario'), new ParamFijo('with_grant', 'false'))), 'GRAL_USUARIO_PERFILES');
		$t->addAction (DefaultActions::mselect('GralUsuarioPerfilMS', 'Agregar y delegar', array (new ParamCampo('id_usuario'), new ParamFijo('with_grant', 'true'))), 'GRAL_USUARIO_PERFILES');
		$t->addActionItem(DefaultActions::quickDelete('gral_usuario_perfil', 'id_usuario_perfil', false), 'GRAL_USUARIO_PERFILES');
		
		$this->addTab ($t);



		$this->addTab (GralDocUtils::defaultTabNotas('gral_usuario', 'id_usuario', 'GRAL_USUARIO_EDIT',$_REQUEST['id']));
		
		parent::init();
	}
	
	public function onRowLoad($row) {
		$this->setTitulo('Datos del usuario ' . $row['txt']);
	}
}