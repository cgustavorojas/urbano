<?php
/**
 * @package gral
 */

/**
 * Consulta del log general del sistema (tabla gral_log). 
 * 
 * @package gral
 */
class GralLogQuery extends QueryScreen
{
	public function init()
	{
		$this->setTitulo ('Log General');
		
		$this->setSql ("SELECT l.*, DATE_FORMAT(fecha, '%d/%m/%y %T') as hora, 
							u.apellido || ', ' || u.nombre as usuario_txt, u.usuario
						FROM gral_log l 
						LEFT JOIN gral_usuario u USING (id_usuario)");
		
		
		$this->addOrder ('Fecha (desc)', 'fecha desc');
		$this->addOrder ('Fecha (asc)', 'fecha');
		$this->addOrder ('Evento', 'evento, fecha desc');
		$this->addOrder ('Usuario', 'id_usuario, fecha desc');
		
		//--------------------------------- Filtros ----------------------------------------//

		// Para los filtros fecha, hago un cast de la columna fecha a tipo date ("fecha::date") para que ignore 
		// la hora, dado que el campo en la tabla es de tipo timestamp.
		
		$f = new FiltroDate ('Desde fecha', ' convert(fecha,date) ');
		$f->setOperador ('>=');
		$f->setId ('fecha_desde');
		$f->setValueToday();
		$this->addFiltro($f);
		$f = new FiltroDate ('Hasta fecha', ' convert(fecha,date) ');
		$f->setOperador ('<=');
		$f->setId ('fecha_hasta');
		$f->setValueToday();
		$this->addFiltro($f);		
		
		
		$f = new FiltroString('Evento', 'evento', 15);
		$f->setOperador('LIKE');
		$f->setToLower();
		$this->addFiltro($f);
		
		$f = new FiltroString('Usuario', 'usuario', 15);
		$f->setToLower();
		$this->addFiltro($f, 2);
		
		
		$f = new FiltroString('Tabla');
		$f->setToLower();
		$this->addFiltro($f, 3);
		
		$f = new FiltroNumber('PK', 'pk', 5);
		$this->addFiltro($f, 3);
		
		
		//--------------------------------- Columnas ----------------------------------------//
		
		$this->addColInactive (new ColNumber('#', 'id_log'));
		
		$this->addCol(new ColDate ('Fecha'));
		$this->addCol(new ColString ('Hora'));
		$c = new ColString ('Usuario', 'usuario');
		$c->setCampoHover('usuario_txt');
		$c->setHrefView('GralUsuarioView', 'id_usuario');
		$this->addCol($c);
		
		$this->addColInactive (new ColString ('Apellido'));
		$this->addColInactive (new ColString ('Nombre'));
		
		$this->addCol (new ColString('Evento'));
		$this->addColInactive (new ColString('IP'));
		
		$this->addColInactive (new ColString('Tabla'));
		$this->addCol(new ColNumber('ID', 'pk'));
				
		$this->addCol(new ColString('Datos adicionales', 'data', 40));
		
		
		parent::init();
		
	}
}