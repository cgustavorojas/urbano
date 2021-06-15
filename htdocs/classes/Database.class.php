<?php
/**
 * @package common
 */

/**
 * Define la constante _CONEXION_BASE
 */
require_once '../../config/conexion.php';

/**
 * Clase de conexión a la base de datos. 
 * Diferencias con la clase Base() que se usa en algunos módulos:
 * 
 *   - Maneja queries con parámetros en un array() independiente del query 
 *   - Usa el mecanismo de exception handling para informar de errores. 
 *   - Devuelve objetos de tipo DatabaseResult, para manejo 100% OO de los resultados
 * 
 * @package common
 */
class Database
{
	private static $conn;
	
	/**
	 * Obtiene una conexión a la base de datos.
	 * La primera vez la guarda en un atributo de la clase (static). Las próximas
	 * llamadas, reusa la misma conexión.  
	 * @return 
	 */
	private static function getConnection() 
	{
		if (is_null(Database::$conn)) {
			Database::$conn =  new PDO('mysql:host='._CONEXION_HOST.';dbname='._CONEXION_DB, _CONEXION_USER, _CONEXION_PASS);
		}
		return Database::$conn; 
	}
	
	/**
	 * Ejecuta un query y devuelve la primer columna de su primer fila. 
	 * 
	 * @param string $sql SQL de consulta a ejecutar.
	 * @param mixed $params Parámetros. Puede ser NULL, 1 valor o un array con valores. 
	 * @return mixed La primer columna de la primer fila o NULL si no hubo registros
	 */
	
	
	
	public static function simpleQuery ($sql, $params = NULL)
	{
		$conn = Database::getConnection(); 
		
		try {
		
			if (is_null($params)) {
				$rs = $conn->query($sql);
			} else {
				if (! is_array($params)) {
					$params = array($params);
				} 
				 $rs = $conn->prepare($sql);
				 $rs->execute($params);
				
			} 
		
		}
		catch(PDOException  $e ){
			echo "Error: ".$e;
			}
		
		if (!$rs){ return null;}	
		
		if ($rs->rowCount() <= 0) 
			return NULL;

		$row = $rs->fetch(PDO::FETCH_LAZY);
		return $row[0];
	}
	
	
	
	/**
	 * Ejecuta un query que no devuelve resultados (insert, update, etc).
	 * En caso de error, lanza una exception con el mensaje de PostgreSQL.
	 * Aunque hoy en día pg_query maneja exactamente igual el caso que se devuelva o no un valor, es mejor
	 * tener separados los casos y por ahora manejarlos igual para en un futuro poder tomar decisiones más precisas. 
	 * 
	 * @param string $sql SQL de update o insert a ejecutar. 
	 * @param mixed $params Parámetros. Puede ser NULL, 1 valor o un array con valores. 
	 * @return void
	 */
	public static function execute ($sql, $params = NULL,$campos = NULL,$tipo=NULL)
	{
		 echo "<h1>sql".var_dump($sql)."</h1>";
				 echo "<h1>datos".var_dump($params)."</h1>";
				 echo "<h1>campos".var_dump($campos)."</h1>";
				 echo "<h1>tipo".var_dump($tipo)."</h1>";
		//die;
		$conn = Database::getConnection(); 

		if (($campos != NULL) and ($tipo =='I')){
			
			$campos = explode(',', $campos);
		}
		
		
		
		// echo "<h1>campos2".var_dump($campos)."</h1>";
			if (is_null($params)) {
				$rs = $conn->query($sql);
			} else {
				if (! is_array($params)) {
					$params = array($params);
				} 
				
					$rs = $conn->prepare($sql);
					
					$i = 1;
					
					
					foreach ($params as $value){
						//var_dump($campos[$i-1]);
						switch ($i){
							
							case 1 : $value1 = $value;$rs->bindParam($campos[$i-1],$value1,PDO::PARAM_STR);break;
							case 2 : $value2 = $value;$rs->bindParam($campos[$i-1],$value2,PDO::PARAM_STR);break;
							case 3 : $value3 = $value;$rs->bindParam($campos[$i-1],$value3,PDO::PARAM_STR);break;
							case 4 : $value4 = $value;$rs->bindParam($campos[$i-1],$value4,PDO::PARAM_STR);break;
							case 5 : $value5 = $value;$rs->bindParam($campos[$i-1],$value5,PDO::PARAM_STR);break;
							case 6 : $value6 = $value;$rs->bindParam($campos[$i-1],$value6,PDO::PARAM_STR);break;
							case 7 : $value7 = $value;$rs->bindParam($campos[$i-1],$value7,PDO::PARAM_STR);break;
							case 8 : $value8 = $value;$rs->bindParam($campos[$i-1],$value8,PDO::PARAM_STR);break;
							case 9 : $value9 = $value;$rs->bindParam($campos[$i-1],$value9,PDO::PARAM_STR);break;
							case 10 : $value10 = $value;$rs->bindParam($campos[$i-1],$value10,PDO::PARAM_STR);break;
							case 11 : $value11 = $value;$rs->bindParam($campos[$i-1],$value11,PDO::PARAM_STR);break;
							case 12 : $value12 = $value;$rs->bindParam($campos[$i-1],$value12,PDO::PARAM_STR);break;
							case 13 : $value13 = $value;$rs->bindParam($campos[$i-1],$value13,PDO::PARAM_STR);break;
							case 14 : $value14 = $value;$rs->bindParam($campos[$i-1],$value14,PDO::PARAM_STR);break;
							case 15 : $value15 = $value;$rs->bindParam($campos[$i-1],$value15,PDO::PARAM_STR);break;
							case 16 : $value16 = $value;$rs->bindParam($campos[$i-1],$value16,PDO::PARAM_STR);break;
							case 17 : $value17 = $value;$rs->bindParam($campos[$i-1],$value17,PDO::PARAM_STR);break;
							case 18 : $value18 = $value;$rs->bindParam($campos[$i-1],$value18,PDO::PARAM_STR);break;
							case 19 : $value19 = $value;$rs->bindParam($campos[$i-1],$value19,PDO::PARAM_STR);break;
							case 20 : $value20 = $value;$rs->bindParam($campos[$i-1],$value20,PDO::PARAM_STR);break;
							case 21 : $value21 = $value;$rs->bindParam($campos[$i-1],$value21,PDO::PARAM_STR);break;
							case 22 : $value22 = $value;$rs->bindParam($campos[$i-1],$value22,PDO::PARAM_STR);break;
							case 23 : $value23 = $value;$rs->bindParam($campos[$i-1],$value23,PDO::PARAM_STR);break;
							case 24 : $value24 = $value;$rs->bindParam($campos[$i-1],$value24,PDO::PARAM_STR);break;
							case 25 : $value25 = $value;$rs->bindParam($campos[$i-1],$value25,PDO::PARAM_STR);break;
							case 26 : $value26 = $value;$rs->bindParam($campos[$i-1],$value26,PDO::PARAM_STR);break;
							case 27 : $value27 = $value;$rs->bindParam($campos[$i-1],$value27,PDO::PARAM_STR);break;
							case 28 : $value28 = $value;$rs->bindParam($campos[$i-1],$value28,PDO::PARAM_STR);break;
							case 29 : $value29 = $value;$rs->bindParam($campos[$i-1],$value29,PDO::PARAM_STR);break;
							case 30 : $value30 = $value;$rs->bindParam($campos[$i-1],$value30,PDO::PARAM_STR);break;
							
							
						}
						
						
					$i++;
						
					}
				//die;	
				 //var_dump($rs);die;
				 $rs->execute();
			}	
		
	}
	
	public static function getSerialId(){
		
		$conn = Database::getConnection();
		return $conn->lastInsertId();
		
	}
	
	
	
	/**
	 * Ejecuta un stored procedures.
	 * En caso de error, lanza una exception con el mensaje de PostgreSQL.  
	 * 
	 * @param string $sp Nombre del stored procedure (con o sin schema, como corresponda)
	 * @param mixed $params Array con los valores para cada parámetro. Si es uno solo, puede no ser un array.   
	 * @return void
	 */
	public static function executeSp($sp, $params = NULL)
	{
		$sql = "SELECT $sp (";
		
		if (! is_null ($params)) {
			if (! is_array($params)) {
				$params = array($params);
			}
			// con el FOR armo un sql de la forma SELECT spname (?, $2, $3, ... $n)
			// de acuerdo a la cantidad de parámetros que tengo que pasar. 
			for ($i = 1 ; $i <= count($params) ; $i++) {
				if ($i > 1)
					$sql = $sql . ",";
					
				$sql = $sql . '$' . $i;
			}
		}
		$sql = $sql . ")";
		
		$conn = Database::getConnection(); 
		
		if (is_null($params))
			$rs = @pg_query ($conn, $sql);
		else
			$rs = @pg_query_params ($conn, $sql, $params);
		
		if (!$rs) 
			throw new Exception (pg_last_error($conn));
		
		$row = pg_fetch_row ($rs);
		return $row[0];
	}
	
	/**
	 * Ejecuta un query que devuelve registros. 
	 * En caso de error, lanza una exception.  
	 * 
	 * @param string $sql SQL de SELECT. 
	 * @param mixed $params Parámetros. Puede ser NULL, 1 valor o un array con valores.* 
	 * @return DatabaseResult resultado de la consulta
	 */
	public static function query ($sql, $params = NULL)
	{
		$conn = Database::getConnection(); 

		try {
		
			if (is_null($params)) {
				$rs = $conn->query($sql);
			} else {
				if (! is_array($params)) {
					$params = array($params);
				} 
				 $rs = $conn->prepare($sql);
				 
				 $rs->execute($params);
			} 
		
		}
		catch(PDOException  $e ){
			echo "Error: ".$e;
			}
		
	
		
		
		
		
		/*if (! $rs) {
			throw new Exception (pg_last_error ($conn));
		}*/
		
		return new DatabaseResult($rs); 
	}
	
	/**
	 * Ejecuta un query que debe devolver 1 fila (o ninguno) y devuelve la fila
	 * (resultado de $rs->getRow()). Si se encuentra con más de un registro, lanza una exception.  
	 * @param string $sql
	 * @param array $params
	 * @return array
	 */
	public static function getRow($sql, $params = null)
	{
		$rs = Database::query ($sql, $params); 

		$count = $rs->getRowCount();
		if ($count > 1) { 
			throw new Exception ("La consulta debía devolver a lo sumo 1 registro y devolvió $count. SQL = $sql.");		
		}
		
		if ($count == 0) {
			return null; 
		}
		
		return $rs->getRow();	
	}
	
}