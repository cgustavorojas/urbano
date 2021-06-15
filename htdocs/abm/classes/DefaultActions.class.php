<?php
/**
 * @package abm
 */


/**
 * Conjunto de métodos estáticos que crean objetos Actions pasándoles
 * los parámetros más comunes. 
 * Es una forma de escribir menos. No se hace nada que no se podría
 * hacer creando los objetos Action "a mano". 
 * @package abm
 */
class DefaultActions
{
	const IMG_VIEW			= 'lupa.gif';
	const IMG_EDIT			= 'edit.gif';
	const IMG_SELECCIONAR	= 'seleccionar.gif';
	const IMG_ALTA			= 'alta.gif';
	const IMG_DELETE 		= 'delete.gif';
	const IMG_DOWNLOAD		= 'download.gif';
	const IMG_EXPORT		= 'excel.gif';
	const IMG_QUERY			= 'query.png';
	const IMG_IMPORT		= ''; 
	const IMG_PDF			= 'pdf.png';
	
	const CSS_ALTA			= 'abm-btn-alta';
	const CSS_ALTA_CUSTOM	= 'abm-btn-alta-custom';
	const CSS_MSELECT		= 'abm-btn-mselect';
	const CSS_IMPORT		= '';
	const CSS_PDF			= 'abm-btn-pdf';
	
	const TXT_ALTA			= 'Agregar'; 
	const TXT_EDIT			= 'Modificar';
	const TXT_VIEW			= 'Detalles';
	const TXT_DELETE		= 'Eliminar';
	const TXT_EXPORT		= 'Exportar';
	const TXT_QUERY			= 'Ver ítems';
	const TXT_SELECCIONAR	= 'Seleccionar';
	const TXT_IMPORT		= 'Importar';
	const TXT_PDF			= 'Imprimir';
	
	
	
	/**
	 * Crea una Action "detalles", que llama a "view.php", pasándole el nombre de la
	 * pantalla a mostrar y el valor de un campo (numérico) como parámetro. 
	 * @param string $screenName Nombre de la pantalla a llamar
	 * @param string $campo Nombre del campo que hay que pasar como parámetro
	 * @return Action Objeto Action creado
	 */
	public static function view ($screenName, $campo)
	{
		return new Action (DefaultActions::TXT_VIEW, "view.php?screen=$screenName", DefaultActions::IMG_VIEW, 
			array (new ParamCampo ('id', $campo)));
	}

	/**
	 * Crea una Action "Modificar", que llama a "edit.php", pasándole el nombre de la
	 * pantalla a mostrar y el valor de un campo (numérico) como parámetro. 
	 * @param string $screenName Nombre de la pantalla a llamar
	 * @param string $campo Nombre del campo que hay que pasar como parámetro
	 * @return Action Objeto Action creado
	 */
	public static function edit ($screenName, $campo)
	{
		return new Action (DefaultActions::TXT_EDIT, "edit.php?screen=$screenName", DefaultActions::IMG_EDIT, 
			array (new ParamCampo ('id', $campo)));
	}

	/**
	 * Crea una Action "Exportar", que llama a "export.php", pasándole el nombre de la
	 * pantalla a mostrar y el valor de un campo (numérico) como parámetro. 
	 * @param string $screenName Nombre de la pantalla a llamar
	 * @param string $campo Nombre del campo que hay que pasar como parámetro
	 * @return Action Objeto Action creado
	 */
	public static function export ($screenName, $campo, $silent = true)
	{
		$cmd = ''; 
		if ($silent) { 
			$cmd = 'cmd=silent';
		}
		
		$params = array();
		if (! is_null($campo)) { 
			$params = array (new ParamCampo ('id', $campo));
		}
		
		return new Action (DefaultActions::TXT_EXPORT, "export.php?screen=$screenName&$cmd", DefaultActions::IMG_EXPORT, $params);
	}	
	
	/**
	 * Crea una Action "Eliminar", que llama a "delete.php", pasándole el nombre de la
	 * pantalla a mostrar y el valor de un campo (numérico) como parámetro. 
	 * @param string $screenName Nombre de la pantalla a llamar
	 * @param string $campo Nombre del campo que hay que pasar como parámetro
	 * @return Action Objeto Action creado
	 */
	public static function delete ($screenName, $campo)
	{
		return new Action (DefaultActions::TXT_DELETE, "delete.php?screen=$screenName", DefaultActions::IMG_DELETE, 
			array (new ParamCampo ('id', $campo)));
	}
	
	/**
	 * 
	 * @param string $tabla Tabla (asume schema ejecucion) 
	 * @param $pk PK del registro 
	 * @param bool $confirmar Si mostrar o no un mensaje de confirmación antes de eliminar el registro (default: sí)
	 * @param string $logEvento código del evento a guardar en gral_log (si es NULL, no se guarda log)
	 * @param string $tipo  Tipo de datos de la clave primaria (default: NUMBER)
	 * @param bool $logicalInactive Si es TRUE, en lugar de borrar el registro, se hace un update del campo "activo" para ponerlo en false (baja lógica) 
	 * @return Action
	 */
	public static function quickDelete ($tabla, $pk, $confirmar = true, $logEvento = null, $tipo = Tipo::NUMBER, $bajaLogica = false)
	{
		$a = new Action (DefaultActions::TXT_DELETE, 'delete.php?screen=QuickDelete', DefaultActions::IMG_DELETE, 
			array (	new ParamCampo ('id', $pk),
					new ParamFijo('tabla', $tabla),
					new ParamFijo('logEvento', $logEvento),
					new ParamFijo('tipo', $tipo),
					new ParamFijo('bajaLogica', $bajaLogica),
					new ParamFijo ('pk', $pk)));
					
		if ($confirmar) {
			$a->setMsgConfirm('¿Confirma la operación de borrado?');
		}
		return $a; 
	}
	
	/**
	 * Crea una Action "Agregar", que llama a "alta.php", pasándole el nombre
	 * de la pantalla a mostrar y una lista opcional de parámetros. 
	 * 
	 * @param $screenName Nombre de la pantalla a mostrar
	 * @param array $params Array de objetos Parametro
	 * @return Action Objeto Action creado
	 */
	public static function alta ($screenName, $params = array())
	{
		$a = new Action (DefaultActions::TXT_ALTA, "alta.php?screen=$screenName", DefaultActions::IMG_ALTA, $params);
		$a->setCssClass (DefaultActions::CSS_ALTA);
		return $a;  
	}
	
	/**
	 * Crea una acción genérica que va a una pantalla de opciones. 
	 * @return Action
	 */
	public static function option ($screenName, $txt, $img = null, $params = array())
	{
		$a = new Action ($txt, "option.php?screen=$screenName", $img, $params);
		return $a;  
	}
	
	/**
	 * Crea una acción que apunta a una pantalla de Alta, pero que no es el alta estándar
	 * de un registro, sino que se usa una pantalla de alta para otra cosa. 
	 * 
	 * @param $screenName Nombre de la pantalla a mostrar
	 * @param $txt	Leyenda a mostrar
	 * @param array $params Array de objetos Parametro
	 * @return Action Objeto Action creado
	 */
	public static function altaCustom ($screenName, $txt, $params = array())
	{
		$a = new Action ($txt, "alta.php?screen=$screenName", null, $params);
		$a->setCssClass (DefaultActions::CSS_ALTA_CUSTOM);
		return $a;  
	}

	/**
	 * Crea una acción que apunta a una función dentro de la pantalla actual. 
	 * 
	 * @param $functionName Nombre de la función a invocar
	 * @param $txt	Leyenda a mostrar
	 * @param array $params Array de objetos Parametro
	 * @return Action Objeto Action creado
	 */
	public static function funcion ($functionName, $txt, $params = array(), $img = null)
	{
		$a = new Action ($txt, "funcion.php?_fx=$functionName", null, $params);
		$a->setImg($img);
		return $a;  
	}
		
	/**
	 * Crea una acción que apunta a una pantalla de selección múltiple de registros. 
	 * 
	 * @param string $screenName Nombre de la pantalla a mostrar
	 * @param string $txt Leyenda a mostrar
	 * @param array $params Array de objetos Parametro
	 * @return Action 
	 */
	public static function mselect ($screenName, $txt, $params = array())
	{
		$a = new Action ($txt, "mselect.php?screen=$screenName", null, $params);
		$a->setCssClass (DefaultActions::CSS_MSELECT);
		return $a;  
	}

	/**
	 * Crea una Action "ver ítems", que llama a una pantalla de query, pasándole
	 * el nombre de la pantalla a mostrar y el valor de un campo del registro seleccionado. 
	 * 
	 * @param string $screenName Nombre de la pantalla a llamar (debe extender QueryScreen)
	 * @param string $campo Nombre del campo 
	 * @return Action
	 */
	public static function query($screenName, $campo = null)
	{
		$params = array(); 
		
		if (!is_null($campo)) { 
			$params = array (new ParamCampo ('id', $campo)); 
		}
		
		return new Action (DefaultActions::TXT_QUERY, "query.php?screen=$screenName", DefaultActions::IMG_QUERY, $params);
	}
	
	/**
	 * Crea una acción que selecciona el registro actual, pasándole el valor un de campo
	 * a un INPUT de la pantalla inmediatamente inferior. 
	 * Llama a la "seleccionar.php", que hace la asignación del INPUT y cierra la ventana actual. 
	 * 
	 * @param $inputId ID del INPUT (de la pantalla inferior) al que hay que setearle el valor
	 * @param $campo Nombre del campo (de la pantalla actual) de dónde tomar el valor a setear
	 */
	public static function seleccionar ($campo, $inputId = null)
	{
		if (is_null($inputId))
			$inputId = $_REQUEST['id1']; 		// id1 es un parámetro que pone InputQuery en la URL que arma cuando llama a una pantalla Lookup
			
		$a = new Action (DefaultActions::TXT_SELECCIONAR, 'seleccionar.php', DefaultActions::IMG_SELECCIONAR);
		$a->addParam (new ParamCampo ('inputValue', $campo));
		$a->addParam (new ParamFijo ('inputId', $inputId));
		return $a; 
	}
	

	/**
	 * Crea una acción que importa un archivo de texto. El parámetro define la pantalal de importación
	 * (que a su vez define la clase y las características de la importación). 
	 * 
	 * @param string $screenName
	 * @param array  $params
	 * @return Action
	 */
	public static function import($screenName, $params = array())
	{
		$a = new Action (DefaultActions::TXT_IMPORT, "import.php?screen=$screenName", DefaultActions::IMG_IMPORT, $params);
		$a->setCssClass (DefaultActions::CSS_IMPORT);
		return $a;   	
	}

	/**
	 * Acción para llamar a un reporte en PDF (derivados de PdfReport). 
	 * 
	 * @param string $reportName
	 * @param array $params
	 * @return Action
	 */
	public static function pdf($reportName, $params = array())
	{
		$a = new Action (DefaultActions::TXT_PDF, "/abm/pdf.php?report=$reportName", DefaultActions::IMG_PDF, $params);
		$a->setCssClass (DefaultActions::CSS_PDF);
		return $a;   	
	}
}
