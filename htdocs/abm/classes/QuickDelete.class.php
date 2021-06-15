<?php
/**
 * @package abm
 */

/**
 * Clase genérica para eliminación de un registro básico con un mensaje de confirmación genérico. 
 * Sirve para no tener que derivar una clase de DeleteScreen por cada tabla de la que se quiere eliminar un registro. 
 * Esta clase cubre el caso básico, recibiendo por parámetros los datos que necesita.
 * 
 * Además, tiene la inteligencia para borrar los registros en las tablas "gral_nota" y "gral_doc_rastro" que no están alcanzados
 * por los ON DELETE CASCADE de las claves foráneas. 
 * 
 * Debe recibir los siguiente parámetros: 
 * 
 *   - Tabla 		: nombre de la tabla, incluyendo schema
 *   - Pk    		: nombre del campo que es clave primaria
 *   - LogEvento	: código del evento a guardar en gral_log (si es null, no se guarda log)
 *   - Tipo			: tipo de datos de la clave primaria (default: Tipo::NUMBER)
 *   - BajaLogica	: Si es TRUE, en lugar de borrar el registro, se cambia el valor del campo "activo" de true a false. 
 *  
 * @package abm
 */
class QuickDelete extends DeleteScreen
{
	public function init()
	{
		$tabla 		= $_REQUEST['tabla'];
		$pk    		= $_REQUEST['pk'];
		$tipo  		= $_REQUEST['tipo'];
		$bajaLogica = $_REQUEST['bajaLogica'];
		
		$confirmar = isset($_REQUEST['confirmar']) && ($_REQUEST['confirmar']); 
		
		$this->setTitulo ('Eliminar registro');
		
		$this->setTabla($tabla);
		$this->setCampo($pk);
		$this->setTipo($tipo);
		$this->setBajaLogica($bajaLogica); 
			
		$this->addErrorMsg('foreign key', 'El registro no pudo ser eliminado porque está siendo usado en otra parte del sistema.');
		$this->addErrorMsg('fk_', 'El registro no pudo ser eliminado porque está siendo usado en otra parte del sistema.');
		
		if (isset ($_REQUEST['logEvento'])) {
			$this->setLogEvento(Utils::nullIfBlank($_REQUEST['logEvento']));
		}
		 
		if ($tipo == Tipo::NUMBER)	// si la PK no es numérica, no puede tener registros asociados en nota y doc_rastro 
		{
			$this->addActionPost("DELETE FROM gral_nota       WHERE tabla = '$tabla' and pk = \?");
			//$this->addActionPost("DELETE FROM gral_doc_rastro WHERE tabla = '$tabla' and pk = \?");
		}
		
		parent::init(); 
	}


	/**
	 * Reviso que no queden archivos adjuntos al elemento eliminado
	 */
	public function afterGuardar()
	{
		if ($this->getTipo() == Tipo::NUMBER) // si la PK no es numérica, no puede tener archivos asociados en gral_archivo
		{ 
			$rs = Database::query ('SELECT id_archivo FROM gral_archivo WHERE tabla = ? and pk = $2', array ($this->getTabla(), $this->getPkValue()));
	
			while ($row = $rs->getRow())
			{
				$file = FileUtils::mkDataPath(sprintf('%08d', $row['id_archivo']), 'gral_archivo');
				if (is_file ($file)) { 
					unlink ($file);
				}		
			}
			Database::execute ('DELETE FROM gral_archivo WHERE tabla = ? and pk = $2', array ($this->getTabla(), $this->getPkValue()));
		}
	}
	
}