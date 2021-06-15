<?php
/**
 * @package common
 */

/**
 * Manejo de logging, tanto en archivos de texto a nivel filesystem como en tablas de 
 * la base de datos. 
 *
 * @package common
 */
class Log {
	  	
	var $archivo = "urbano.log";
	var $modo;
	var $consola = '';
	var $dbConn;

	
	function setDBConn($dbConnAux) {
		$this->dbConn = $dbConnAux;
	}
	
	function getDBConn() {
		return $this->dbConn;
	}
	
	function getDate() {
				return date("Y-m-d H:i:s");
	}
			
	function getBaseName() {
				global $PHP_SELF;
				return basename($PHP_SELF);
	}

		
	function __construct($modo_log = 'C',$nombre_archivo = null) 
	{
			global $dbConn; 
			
			if (is_null($nombre_archivo))
				$nombre_archivo = 'urbano.log';
			$this->archivo = dirname (__FILE__) . '/../../log/' . $nombre_archivo;
			$this->modo = $modo_log;
			$this->setDbConn($dbConn);
	}
	
	// -----------------------------------------------------------------
	
	function mensaje_para_archivo($datos) {
	
			$archivo = fopen($this->archivo,"a+");
			fwrite($archivo , $datos.chr(10));
			return (fclose($archivo ));


	}
	
	function mensaje_para_consola($datos) {
	
			$this->consola = $this->consola.'<tr><td>'. $datos . " </td></tr>";
			return true;
	
	}
	
	function mensaje_para_base($datos) {
		
		$dbConnAux = $this->getDBConn();
		
		$dbConnAux->query($datos );
	
	}
	
	// -----------------------------------------------------------------	
	
	function mensaje($datos = '') {
		
		$usuarioId = Seguridad::getCurrentUserId();
		
		$datos ="[" . $this->getDate() ."] [".$_SERVER['REMOTE_ADDR']."] [".$usuarioId."] [". $_SERVER['REQUEST_URI'] ."]".$datos;
		
		$aux = $this->modo;
		
		$i = 0;
		$buffer = '';
		while( $i < strlen($aux)) {
		
				  
				//echo "<h2>--> $i" .$aux[$i]."</h2>";
				
				if ($aux[$i] == 'B') {	
						$datos_query = "insert into presupuesto3.log_presupuesto ( fecha ,ip ,id_usuario ,pagina ,operacion ,datos ) values ( '".$this->getDate()."' ,'".$_SERVER['REMOTE_ADDR']."' ,'$usuarioId' ,'".$_SERVER['REQUEST_URI']."' ,'log' ,'".str_replace("'","",$datos)."' )";
						$this->mensaje_para_base($datos_query);
				}
				 
				if ($aux[$i] == 'A' ) {
						 $buffer = $this->mensaje_para_archivo($datos);
				}
				
				if ($aux[$i] == 'C' ) {
						$buffer =  $this->mensaje_para_consola($datos);
				}
				
			
				
				$i = $i + 1;
		}
		
		return $buffer;
		
	}
	
	function toHTML() {
		return "<table>".$this->consola."</table>";
	}
	
	// -----------------------------------------------------------------

}



?>
