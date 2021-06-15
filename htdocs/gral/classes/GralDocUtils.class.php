<?php
/**
 * @package gral
 */

/**
 * Rutinas auxiliares para el manejo de documentos respaldatorios y archivos del módulo general.
 * 
 * @package gral 
 */
class GralDocUtils
{
	/**
	 * Crea un objeto ViewTab y lo configura con los elementos standard (columnas, acciones, etc.). 
	 * Forma de uso: dentro de una pantalla de tipo ViewScreen, se crea un tab de este tipo y se
	 * lo agrega con addTab(). De esta forma, automáticamente, se agrega la posibilidad de agregar
	 * archivos adjuntos al objeto en cuestión. 
	 * Ejemplo (adjuntar archivos a un formulario de gastos): 
	 *    public function init() 
	 *    {
	 *       ...
	 *       ...
	 *       $t = GralDocUtils::defaultTabArchivos ('ejecucion.eg_cabecera', 'egcab');
	 *       $this->addTab ($t);
	 *       ...
	 *    } 
	 *    
	 *  El parámetro opcional $permiso_rw define el permiso que tiene que tener el usuario
	 *  para poder hacer modificaciones (si no lo tiene es read-only). Si no se especifica, es read-write para todos.  
	 */
	public static function defaultTabArchivos($tabla, $campoPk, $permiso_rw = NULL,$titulo='Archivos',$pk=NULL)
	{
		$t = new TabQuery($titulo);
	
		$t->setSql ("SELECT a.*, u.usuario
						FROM gral_archivo a
							LEFT OUTER JOIN gral_usuario u USING (id_usuario)
						WHERE tabla = '$tabla' and pk = ?
						ORDER BY f_alta DESC");
		
		$t->addCol(new ColDate ('Fecha', 'f_alta'));
		$t->addCol(new ColString('Usuario'));
		$t->addCol(new ColString ('Archivo'));
		$t->addCol(new ColString ('Título', 'txt', 80));
				
		
		
		$a = new Action ('Agregar', '../gral/archivo.php?cmd=add', null, array (
														new ParamFijo ('pk', $pk),
														new ParamFijo ('tabla', $tabla)));
		$a->setCssClass (DefaultActions::CSS_ALTA);
		$t->addAction ($a,$permiso_rw);
														
		$t->addActionItem(DefaultActions::edit('GralArchivoEdit', 'id_archivo'), $permiso_rw);
		
		$t->addActionItem (new Action ('Eliminar', '../gral/archivo.php?cmd=delete', DefaultActions::IMG_DELETE, 
										array (new ParamCampo ('id', 'id_archivo'))), $permiso_rw);
														
		$t->addActionItem (new Action ('Download', '../gral/archivo.php?cmd=download', DefaultActions::IMG_DOWNLOAD, 
										array (new ParamCampo ('id', 'id_archivo'))));
										
		return $t; 
	}	
	
	/**
	 * Crea un objeto ViewTab y lo configura con los elementos standard (columnas, acciones, etc.). 
	 * Forma de uso: dentro de una pantalla de tipo ViewScreen, se crea un tab de este tipo y se
	 * lo agrega con addTab(). De esta forma, automáticamente, se agrega la posibilidad de agregar
	 * notas al objeto en cuestión. 
	 * Ejemplo (adjuntar notas a un formulario de gastos): 
	 *    public function init() 
	 *    {
	 *       ...
	 *       ...
	 *       $t = GralDocUtils::defaultTabNotas ('ejecucion.eg_cabecera', 'egcab');
	 *       $this->addTab ($t);
	 *       ...
	 *    } 
	 *    
	 *  El parámetro opcional $permiso_rw define el permiso que tiene que tener el usuario
	 *  para poder hacer modificaciones (si no lo tiene es read-only). Si no se especifica, es read-write para todos.  
	 *  
	 *  Además de tener el permiso adecuado, sólo el que escribió una nota puede editarla o eliminarla. La idea es que
	 *  cualquier otro usuario, si tiene algo que decir, agregue una nota nueva. 
	 */
	public static function defaultTabNotas($tabla, $campoPk, $permiso_rw = NULL,$pk = NULL)
	{
		$t = new TabQuery('Notas');
	
		$t->setSql ("SELECT n.*, u.usuario
						FROM gral_nota n
							LEFT OUTER JOIN gral_usuario u USING (id_usuario)
						WHERE tabla = '$tabla' and pk = $pk
						ORDER BY fecha DESC, id_nota DESC");
		
		$t->addCol(new ColDate ('Fecha'));
		$t->addCol(new ColString('Usuario'));
		$t->addCol(new ColString ('Título', 'txt', 80));
		
		$a = DefaultActions::alta('GralNotaAlta', array (new ParamCampo ('pk', $campoPk),
													   new ParamFijo ('tabla', $tabla)
													   ));
		$t->addAction ($a, $permiso_rw); 
		
		$t->addActionItem(DefaultActions::view('GralNotaView', 'id_nota'));
		
		$a = DefaultActions::edit('GralNotaEdit', 'id_nota');
		$a->setConstraint('id_usuario', Seguridad::getCurrentUserId());	// solo puede editar una nota el mismo usuario que la creó
		$t->addActionItem($a, $permiso_rw);
		
		$a = DefaultActions::quickDelete('gral_nota', 'id_nota');
		$a->setConstraint('id_usuario', Seguridad::getCurrentUserId());	// solo puede eliminar una nota el mismo usuario que la creó
		$t->addActionItem($a, $permiso_rw);

		return $t; 
	}
	
	/**
	 * Crea un objeto ViewTab y lo configura con los elementos standard (columnas, acciones, etc.). 
	 * Forma de uso: dentro de una pantalla de tipo ViewScreen, se crea un tab de este tipo y se
	 * lo agrega con addTab(). De esta forma, automáticamente, se agrega la posibilidad de agregar
	 * notas al objeto en cuestión. 
	 * Ejemplo (adjuntar doc. resp. a un formulario de gastos): 
	 *    public function init() 
	 *    {
	 *       ...
	 *       ...
	 *       $t = GralDocUtils::defaultTabDr ('ejecucion.eg_cabecera', 'egcab');
	 *       $this->addTab ($t);
	 *       ...
	 *    } 
	 *    
	 *  El parámetro opcional $permiso_rw define el permiso que tiene que tener el usuario
	 *  para poder hacer modificaciones (si no lo tiene es read-only). Si no se especifica, es read-write para todos.  
	 *  @return TabQuery
	 */
	public static function defaultTabDr($tabla, $campoPk, $permiso_rw = NULL)
	{
		$t = new TabQuery('Doc. Resp.');
	
		$t->setSql ("SELECT doc.id_doc, doc.tipo, doc.nro, doc.ej, r.id_doc_rastro, r.txt, be.txt as be_txt, u.usuario
							FROM gral_doc_rastro r
								LEFT OUTER JOIN gral_usuario u USING (id_usuario)
								INNER JOIN gral_doc 
									LEFT OUTER JOIN gral_benef be ON be.id_benef = doc.id_benef 
								USING (id_doc)
							WHERE tabla = '$tabla' AND pk = \?
							ORDER BY id_doc_rastro DESC");
	
		$t->addCol(new ColString('Usuario'));
		$t->addCol(new ColString ('Vínculo', 'txt', 80));
		
		$t->addCol (new ColString('Tipo', 'tipo'));
		$t->addCol (new ColNumber ('Nro'));
		$t->addCol (new ColNumber ('Ej'));
		$t->addCol (new ColString ('Beneficiario', 'be_txt'));		
		
		$a = DefaultActions::alta('GralDocRastroAlta', array (new ParamCampo ('pk', $campoPk),
											 		     new ParamFijo ('tabla', $tabla)
													    ));
		$t->addAction ($a, $permiso_rw); 
		
		$t->addActionItem(DefaultActions::view('GralDocView', 'id_doc'));
		$t->addActionItem(DefaultActions::quickDelete('gral_doc_rastro', 'id_doc_rastro'), $permiso_rw);

	
		return $t; 
	}		
		
	/**
	 * Crea un objeto ViewTab y lo configura con los elementos standard (columnas, acciones, etc.). 
	 * Forma de uso: dentro de una pantalla de tipo ViewScreen, se crea un tab de este tipo y se
	 * lo agrega con addTab(). De esta forma, automáticamente, se agrega la posibilidad de ver 
	 * el historial de cambios de estado de este objeto.
	 * 
	 * Se apoya en la tabla auxiliar gral_tabla para saber cuál es la tabla que guarda los códigos y las descripciones
	 * de los estados. 
	 * 
	 * Los cambios de estado deben ir registrándose con Utils::logEstado(). 
	 */
	public static function defaultTabEstados($tabla, $campoPk)
	{
		$t = new TabQuery('Estados');
	
		$lookup = Database::simpleQuery('SELECT tabla_estado FROM gral_tabla WHERE tabla = ?', $tabla);
		
		$t->setSql ("SELECT le.fecha, le.anterior, le.nuevo, le.id_usuario, u.txt as usuario, 
							coalesce(ant.txt, le.anterior) as anterior_txt, coalesce(nue.txt, le.nuevo) as nuevo_txt
						FROM gral_log_estado le
								LEFT OUTER JOIN gral_usuario u USING (id_usuario)
								LEFT OUTER JOIN $lookup ant ON (ant.estado = le.anterior)
								LEFT OUTER JOIN $lookup nue ON (nue.estado = le.nuevo)
						WHERE tabla = '$tabla' AND pk = \?
						ORDER BY id_log_estado DESC");
	
		$t->addCol (new ColTimestamp('Fecha'));
		$c = new ColString('Anterior', 'anterior_txt');
		$c->setAlign(Column::CENTER);
		$t->addCol ($c);
		$c = new ColString('Nuevo', 'nuevo_txt');
		$c->setAlign(Column::CENTER);
		$t->addCol ($c);
		$t->addCol (new ColString('Usuario'));
		
		return $t; 		
	}
	
}