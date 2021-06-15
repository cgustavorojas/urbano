<?php
/**
 * @package gral
 */

/**
 * Lista los parámetros de configuración general del sistema (tabla gral_config) y permite editar sus valores. 
 *
 * @package gral
 */
class GralConfigQuery extends QueryScreen
{
	public function init()
	{
		$this->setTitulo ('Parámetros de Configuración');
		
		$this->setSql ('SELECT cfg.*, s.descripcion as sistema
							FROM gral_config  cfg
								INNER JOIN gral_sistema s USING (id_sistema) 
							WHERE #FILTROS# 
							ORDER BY id_config');
		
		$this->setPermiso("GRAL_CONFIG");
		
		//--------------------- Filtros --------------------------//
		
		$f = new FiltroString('Parámetro', 'id_config', 40, 100);
		$f->setAppend('%');
		$f->setToLower();
		$f->setOperador('LIKE');
		$this->addFiltro($f);

		$f = new FiltroString('Valor', 'valor', 40, 100);
		$f->setAppend('%');
		$f->setPrepend('%');
		$f->setToLower();
		$f->setOperador('LIKE');
		$this->addFiltro($f);

		$f = new FiltroComboSql ('Sistema', 'id_sistema', 'SELECT id_sistema, descripcion FROM gral_sistema ORDER BY descripcion', Tipo::STRING);
		$this->addFiltro($f);
		
		//--------------------- Acciones --------------------------//
		
		$this->addActionItem (DefaultActions::edit('GralConfigEdit', 'id_config'),"GRAL_CONFIG");
		
		//--------------------- Columnas --------------------------//
		
		$this->addCol (new ColString('Parámetro', 'id_config'));
		$this->addCol (new ColString('Valor'));
		$this->addCol (new ColString('Sistema'));
		$this->addCol (new ColString('Descripción', 'txt', 60));
		
		parent::init();
	}
}