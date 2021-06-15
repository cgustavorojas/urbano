<?php
/**
 * @package default
 * 
 */
/**
 * Maneja la interacción con el webservice de la plataforma de seguimiento
 * @package default
 */
class ServiceSeguimiento 
{
	private $url;
	private $sc;  
	
	/*
	 * Crea un nuevo objeto ServiceComDoc.
	 * Crea un nuevo objeto SoapClient para uso interno de la clase, inicializándolo con la URL 
	 * del parámetro general de configuración gral_comdoc.wsdl.
	 */
	public function __construct()
	{
		$this->url = Config::getInstance()->get('gral_comdoc.wsdl');

		if (is_null($this->url)) { 
			throw new Exception ('Error fatal: no se especificó el parámetro de configuración gral_comdoc.wsdl');
		}
		$this->sc = new SoapClient($this->url);
	}
	
	/**
	 * Ejecuta la llamada a getDatosGenerales() del webservice y devuelte su resultado. 
	 *  
	 * @param string $id Código del expediente (ej: EXPE-MRE:0042463/2010)
	 * @return array Data tal cual la devuelve el webservice
	 */
	public function getDatosGenerales($id)
	{
		return $this->sc->getDatosGenerales($id);
	}

	/**
	 * Ejecuta la llamada a getDatosSeguimiento() del webservice y devuelte su resultado. 
	 *  
	 * @param string $id Código del expediente (ej: EXPE-MRE:0042463/2010)
	 * @return array Data tal cual la devuelve el webservice
	 */
	public function getDatosSeguimiento($id)
	{
		return $this->sc->getDatosSeguimiento($id);
	}
	
	/**
	 * Trae la información del expediente mediante llamadas al webservice y con el resultado
	 * actualiza las tablas gral_expediente y gral_expediente_seg.
	 * No es muy inteligente a la hora de actualizar: si ya lo había traído antes, borra todo lo que tenía
	 * y lo trae de vuelta.  
	 * 
	 * @param string $id Código del expediente (ej: EXPE-MRE:0042463/2010)
	 */
	public function updateLocalTables ($id)
	{
		$id = trim($id);
		
		$data = $this->getDatosGenerales($id); 

		if (is_null($data)) {
			throw new Exception ("COMDOC no devolvió información para el expediente $id");	
		}
		
		$seg = $this->getDatosSeguimiento($id);
		
		// Si el expediente ya estaba cargado, lo borro. Si no estaba, la sentencia es inocua. 
		// Borra en cascada por FK lo que pueda haber en gral_expediente_seg. 
		Database::execute ('DELETE FROM gral_expediente WHERE id_expediente = ?', $id);
		
		$stmt =  new InsertStmt ('gral_expediente');
		$stmt->add ('id_expediente', $id, Tipo::STRING);
		$stmt->add ('titulo', substr($data->titulo, 0, 100), Tipo::STRING);
		$stmt->add ('vencimiento', substr($data->vencimiento, 0, 30), Tipo::STRING);
		$stmt->add ('situacion', substr($data->situacion, 0, 30), Tipo::STRING);
		$stmt->add ('area_actual', substr($data->area_actual, 0, 30), Tipo::STRING);
		$stmt->add ('fecha', $data->fecha, Tipo::DATE);
		$stmt->add ('causante', substr($data->causante, 0, 120), Tipo::STRING);
		$stmt->add ('responsable', substr($data->responsable,0,30), Tipo::STRING);
		$stmt->execute(); 

		if (! is_null($seg))
		{
			foreach ($seg as $s)
			{
				$stmt = new InsertStmt ('gral_expediente_seg');
				$stmt->add ('id_expediente', $id, Tipo::STRING);
				$stmt->add ('area_alta', substr($s->area_alta,0,30), Tipo::STRING);
				$stmt->add ('area_destino', substr($s->area_destino,0,30), Tipo::STRING);
				$stmt->add ('fecha_emision', $this->convert_date($s->fecha_emision), Tipo::TIMESTAMP);
				$stmt->add ('fecha_cierre', $this->convert_date($s->fecha_cierre), Tipo::TIMESTAMP);
				$stmt->add ('codigo', substr($s->codigo,0,30), Tipo::STRING);
				$stmt->add ('estado', substr($s->estado,0,30), Tipo::STRING);
				$stmt->execute();	
			}
		}
	}
	
	/**
	 * El webservice de COMDOC devuelve las fechas como un string en el formato YYYYMMDD HH:mm:ss. 
	 * Esta función lo convierte a formato ISO (YYYY-MM-DD HH:mm:ss) para poder ser guardado en la
	 * base como tipo timestamp. 
	 * @param string $string Fecha en formato YYYYMMDD HH:mm:ss
	 * @return string Fecha en formato YYYY-MM-DD HH:mm:ss
	 */
	private function convert_date($string)
	{
		if (is_null($string))
			return null; 
		
		return substr($string, 0, 4) . "-" . substr($string, 4, 2) . "-" . substr($string, 6, 11);
	}
	
}