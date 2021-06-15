<?php
/**
 * @package default
 */

/**
 * Define la constante _CONEXION_BASE con el string de conexión que espera pg_connect();
 * Opcionalmente, si se define una constante _CONEXION_DEBUG con cualquier valor, todos los queries
 * se mandan a un archivo de log "base.log"
 */
include dirname(__FILE__) . '/../../config/conexion.php';

/**
 * En objetos_globales.php se crea un objeto $dbConn de esta clase para usar en forma global. 
 *
 * @package default
 */
class Base 
{
	var $connector = null;
	var $log; 
	
	function __construct($connectionString = null) 
	{
		$this->connector = new PDO('mysql:host='._CONEXION_HOST.';dbname='._CONEXION_DB, _CONEXION_USER, _CONEXION_PASS);
		$this->log = new Log('A', 'base.log');
	}
	
	function getConnection() 
	{
	 	return $this->connector;
	}
	
	function query($query) 
	{
		$connection = $this->getConnection();

		if (defined('_CONEXION_DEBUG')) 
			$this->log->mensaje($query);
		
		return pg_query($connection,$query);
	}
	
	function getRow($query) 
	{
		return pg_fetch_assoc($query);
	}
	
	function getRowAssoc($query) 
	{
		return pg_fetch_assoc($query);
	}
	
	function getNumRows($query) 
	{
		return pg_num_rows($query);	
	}
	
	function getNumFields($query) 
	{
		return pg_num_fields($query);
	}
	
	function getFieldName($query,$numeroCampo) 
	{
		return pg_field_name($query, $numeroCampo); 
	}

	function getFieldType($query,$numeroCampo) 
	{
		return pg_field_type($query, $numeroCampo); 
	}
}

