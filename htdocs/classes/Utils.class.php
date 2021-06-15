<?php
/**
 * @package common
 * @author 
 */

/**
 * Rutinas genéricas de varios tipos, fundamentalmente para manejo de strings, tipos y formatos.
 * Las funciones son estáticas para no tener que instanciar la clase, que no guarda ningún estado.  
 * 
 * @package common
 */
class Utils
{
	
	/**
	 * Devuelve una representación en texto de $valor, escapeada de acuerdo a su tipo para ser mandada a la base de datos. 
	 * Ejemplo: si es un string, encierra entre comilla simple y escapea comillas que pudiera tener adentro. 
	 * 
	 * @param $value El valor a representar
	 * @param $tipo El tipo de datos del campo 
	 * @return string Valor listo para ser usado en una sentencia SQL 
	 */
	public static function sqlEscape($valor, $tipo)
	{
		if (is_null($valor))
			return "NULL";
			
		switch ($tipo)
		{
			case Tipo::STRING:
				return "'" . pg_escape_string($valor) . "'";
				break;
			case Tipo::NUMBER:
				return $valor;
				break;
			case Tipo::MONEY:
				return $valor;
				break; 
			case Tipo::DATE:
				return "'" . $valor . "'"; 
				break;
			case Tipo::BOOLEAN:
				if ($valor == 't' || $valor === true)
					return "'t'";
				else
					return "'f'"; 
				break;
		}
	}
	
	/**
	 * Devuelve una representación en texto de $valor, reemplazando los caracteres que HTML considera especiales (signo mayor,
	 * signo menor, etc.) por su representación HTML.  
	 * Ejemplo: Si viene ">", lo reemplaza por "&gt;" 
	 * 
	 * @param $value El valor a representar
	 * @return string Valor listo para ser usado dentro de HTML 
	 */
	public static function htmlEscape($value)
	{
		return str_replace(array('<', '>', '&'), array('&lt;', '&gt;', '&amp;'), $value);
	}
	
	/**
	 * Convierte a minúsculas y reemplaza blancos, puntos y demás por guiones bajos. 
	 * Usado primariamente para convertir una etiqueta tal como se mostraría a un usuario (ej: "ID Usuario")
	 * en un identificador válido para HTML o para ser nombre de un campo de una tabla (ej: "id_usuario")
	 *   
	 * @param $txt Etiqueta. Ejemplo: "ID Usuario"
	 * @return string Ejemplo: "id_usuario"
	 */
	public static function toLowerNoBlanks($txt)
	{
		return str_replace(array(".", " "), "_", strtolower($txt));
	}

	/**
	 * Una especie de caso inverso de la rutina toLowerNoBlanks(). Convierte un nombre de campo
	 * como "fecha_alta" en "Fecha Alta", reemplazando guiones bajos por espacios y pasando
	 * la primer letra de cada palabra a mayúsculas. Maneja en forma particular algunos casos
	 * que por su frecuencia ameritan hacerles algún manejo más avanzado como el caso de
	 * la palabra "ID" o "Descripción", que se encuentran en gran cantidad de nombres de campos. 
	 * 
	 * @param string $txt el nombre del campo o texto a pasar a mayúsculas
	 * @return string
	 */
	public static function toUpperWithBlanks($txt)
	{
		$s = str_replace(array("_"), " ", $txt);
		$s = ucwords($s); 
		$s = str_replace('Id ', 'ID ', $s);
		$s = str_replace('Descripcion', 'Descripción', $s);
		$s = str_replace('Observacion', 'Observación', $s);
		
		return $s;
	}
	
	
	/**
	 * Muchas veces hay strings que vienen como parámetros que pueden ser una cadena vacía o
	 * una cadena de blancos, y en realidad lo que queremos es, o un valor válido, o NULL.
	 * 
	 * @param $txt Un texto cualquiera
	 * @return string NULL si venía NULL o una cadena vacía o sólo blancos. Si no, la cadena original.
	 */
	public static function nullIfBlank($txt)
	{
		if (is_null($txt))
			return null; 
			
		if (strlen(trim($txt)) == 0)
			return null; 
			
		return $txt; 
	}
	
	/**
	 * Parecido a is_null() pero también devuelve TRUE si es una cadena vacía.
	 *  
	 * @param string $txt
	 * @return bool TRUE si es null o cadena vacía o sólo blancos.
	 */
	public static function isNullOrBlank($txt)
	{
		return is_null(Utils::nullIfBlank($txt));
	}
	
	/**
	 * Rellena con blancos a izquierda y derecha para devolver un string de exactamente $length caracteres.
	 * A diferencia de str_pad(), si el string original es más largo, lo trunca.  
	 *  
	 * @param $str String a centrar
	 * @return string El string original rellenado con blancos a izquierda y derecha
	 */
	public static function padCenter($str, $length)
	{
		if (strlen($str) == $length)
			return $str; 
		if (strlen($str) > $length)
			return substr($str, 0, $length);
		return str_pad ($str, $length, ' ', STR_PAD_BOTH);
	}	
	
	/**
	 * Rellena con blancos a derecha devolver un string de exactamente $length caracteres.
	 * A diferencia de str_pad(), si el string original es más largo, lo trunca.  
	 *  
	 * @param $str String a rellenar
	 * @return string El string original rellenado con blancos a izquierda y derecha
	 */
	public static function padLeft($str, $length)
	{
		if (strlen($str) == $length)
			return $str; 
		if (strlen($str) > $length)
			return substr($str, 0, $length);
		return str_pad ($str, $length, ' ', STR_PAD_RIGHT);
	}	
	
	
	/**
	 * Rellena con blancos a izquierda devolver un string de exactamente $length caracteres.
	 * A diferencia de str_pad(), si el string original es más largo, lo trunca.  
	 *  
	 * @param $str String a rellenar
	 * @return string El string original rellenado con blancos a izquierda y derecha
	 */
	public static function padRight($str, $length)
	{
		if (strlen($str) == $length)
			return $str; 
		if (strlen($str) > $length)
			return substr($str, 0, $length);
		return str_pad ($str, $length, ' ', STR_PAD_LEFT);
	}	
	
	/**
	 * Devuelve la fecha actual o, si se especifica el parámetro $dias, una fecha tantos días para adelante
	 * o atrás ($dias puede ser positivo o negativo). 
	 * El formato ISO es propicio tanto para inputs, filtros como para ser interpretado directamente por postgres.  
	 * @return string La fecha actual en formato ISO.  
	 */	
	public static function today($dias = 0)
	{
		date_default_timezone_set("America/Argentina/Buenos_Aires");
		
return date ('Y-m-d', time() + ($dias * 86400));
	}

	public static function now()
	{
		return date ('Y-m-d H:i:s', time());
	}
	
	/**
	 * Devuelve la fecha de mañana
	 * @return string Fecha en forma ISO (Y-m-d). 
	 */
	public static function tomorrow()
	{
		return date ('Y-m-d', time()+86400);
	}
	
	/**
	 * Devuelve la fecha de ayer. 
	 * @return string Fecha en forma ISO (Y-m-d).
	 */
	public static function yesterday()
	{
		return date ('Y-m-d', time()-86400);
	}
	
	/**
	 * Compara las fechas y se asegura que la segunda fecha es posterior o igual a la primera. 
	 * En el caso de algún NULL, devuelve true. 
	 * 
	 * @param date $fecha1
	 * @param date $fecha2
	 * @return boolean
	 */
	public static function isValidDateRange($fecha1, $fecha2)
	{
		if (is_null($fecha1) or is_null($fecha2))
			return true; 
			
		return (strtotime($fecha2) >= strtotime($fecha1));
	}
	
	
	/**
	 * Rutina utilitaria para generar una serie de elementos OPTION a partir de un array y un elemento seleccionado.
	 * Itera sobre el array y genera los elementos OPTIONS, marcando como SELECTED aquél que coincida con el segundo parámetro. 
	 * El array debe ser asociativo, siendo la clave la leyenda a mostrar y el valor el value a devolver en el FORM. 
	 * @param array $array
	 * @param string $selected
	 * @return string Código HTML de todos los elementos OPTION (no incluye el SELECT)
	 */
	public static function htmlOptions($array, $selected)
	{
		$html = ''; 
		
		foreach ($array as $key => $value) {
			if ($value == $selected) { 
				$html = $html . "<OPTION SELECTED value='$value'>$key</OPTION>";
			} else {
				$html = $html . "<OPTION value='$value'>$key</OPTION>";
			}
		}
		return $html; 
	}
	
	/**
	 * Genera una entrada en el log general de la base de datos (tabla gral_log).
	 * 
	 * Una entrada de log está determinada por un código de evento, que es un texto libre, corto, que por 
	 * convención usamos sistema.evento (ej: gral_login) y puede estar, opcionalmente, asociado a un 
	 * registro de una tabla. Ej: si el evento es pre.form.delete para indicar que se borró un formulario de presupuesto,
	 * se puede indicar cuál fue el formulario eliminado con los atributos tabla y pk (ej: presupuesto.formulario y 4536). 
	 * El campo descripcion es opcional y es un texto libre con más información. No usar con información redundante 
	 * (ejemplo: si el evento es "login", no hace falta en la descripción poner "el usuario se logueó").  
	 * 
	 * @param string $evento Código del evento a registrar (no deben repetirse). Utilizar el módulo.evento (ej: alq.xxx ó pre.xxx)
	 * @param string $tabla Si el evento aplica a un registro en particular (ej: registro que se borró una ítem), el nombre de la tabla, incluído schema. 
	 * @param integer $pk Si se especifica tabla, el ID del registro involucrado
	 * @param string $data Texto libre de descripción del evento, sólo en los casos en que haga falta (que haya que agregar algo más que no queda explícito con el código de evento)
	 */
	public static function log($evento, $tabla = NULL, $pk = NULL, $data = NULL)
	{
		$id_usuario = Seguridad::getCurrentUserId();
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$stmt = new InsertStmt ('gral_log');
		$stmt->add ('id_usuario', $id_usuario, Tipo::NUMBER);
		$stmt->add ('ip', $ip, Tipo::STRING);
		$stmt->add ('evento', strtolower($evento), Tipo::STRING);
		$stmt->add ('tabla', $tabla, Tipo::STRING);
		$stmt->add ('pk', $pk, Tipo::NUMBER);
		$stmt->add ('data', $data, Tipo::STRING);
		$stmt->execute(); 
		
		
		//$sql = " insert into gral_log(id_usuario,ip,evento,tabla,pk,data) values(:id_usuario,:ip,:evento,:tabla,:pk,:data)";
		//Database::query($sql,array($id_usuario,$ip,strtolower($evento),$tabla,$pk,$data),array(':id_usuario',':ip',':evento',':tabla',':pk',':data'));
		

		
		
	}
	
	/**
	 * Genera una entrada en el log de estados, que lleva un registro de los cambios de estado de diversos objetos en la base. 
	 * 
	 * Cada cambio de estado está determinado por la tabla y la clave primaria del registro que sufre el cambio de estado y 
	 * dos campos de estado: el anterior (opcional) y el nuevo estado. 
	 * 
	 * @param $tabla Nombre de la tabla (ej: "liquidacion.factura")
	 * @param $pk ID del registro dentro de la tabla (ej: 1435)
	 * @param $nuevo Nuevo estado (ej: "C")
	 * @param $anterior Estado anterior (ej: "T")
	 */
	public static function logEstado($tabla, $pk, $nuevo, $anterior = null)
	{
		$id_usuario = Seguridad::getCurrentUserId();
		
		$stmt = new InsertStmt ('gral_log_estado');
		$stmt->add ('tabla', $tabla, Tipo::STRING);
		$stmt->add ('pk', $pk, Tipo::NUMBER);
		$stmt->add ('nuevo', $nuevo, Tipo::STRING);
		$stmt->add ('anterior', $anterior, Tipo::STRING);
		$stmt->add ('id_usuario', $id_usuario, Tipo::NUMBER); 
		$stmt->execute(); 
	}

	/**
	 * Convierte una ruta relativa (al raíz de la aplicación), en una ruta absoluta. 
	 * Soporta el caso de que la aplicación esté instalada en el directorio raíz o en el subdirectorio "urbano". 
	 * 
	 * @param string $relativeUrl Ruta relativa (al raíz de la aplicación) 
	 * @return string Url absoluta, comenzando con http:// 
	 */
	public static function makeAbsoluteUrl($relativeUrl)
	{
		$self = $_SERVER['PHP_SELF'];
		if (strtolower((substr($self,0,8)) == '/urbano/')) {
			$rootpath = '/urbano/';
		} else {
			$rootpath = '/';
		}
		return 'http://' . $_SERVER['HTTP_HOST'] . ":" . $_SERVER['SERVER_PORT'] . $rootpath . $relativeUrl; 
	}
	
	
	/**
	 * Envía un header "Location:" para redireccionar al browser a otra página. 
	 * Se encarga de que la url enviada al browser sea siempre absoluta (así lo pide el protocolo 
	 * HTTP para el caso de los redirects). 
	 * 
	 * @param string  $url
	 */
	public static function redirect($url, $relative = false)
	{
		if (strtolower((substr($url,0,4))) != 'http' && ! $relative) {
			$url = Utils::makeAbsoluteUrl($url);
		}
		header ('Location: ' . $url);
	}
	
	/**
	 * Si el valor 1 no es null, devuelve valor 1.
	 * Si el valor 2 es null, devuelve valor 2. 
	 */
	public static function coalesce ($valor1, $valor2)
	{
		return is_null($valor1) ? $valor2 : $valor1; 
	}
	
	public static function mesLetras($mes){
		
		switch ($mes){
			
			case 1: return 'enero';
			case 2: return 'febrero';
			case 3: return 'marzo';
			case 4: return 'abril';
			case 5: return 'mayo';
			case 6: return 'junio';
			case 7: return 'julio';
			case 8: return 'agosto';
			case 9: return 'septiembre';
			case 10: return 'octubre';
			case 11: return 'noviembre';
			case 12: return 'diciembre';
			
		}
		
	}
	
	/*
	 * Requiere un dia de la semana en ingles y lo pasa a español
	 */
	public static function diaDeLaSemanaEs($dia_en){
		
		switch ($dia_en){
			
			case 'Mon': return 'lunes';
			case 'Tue': return 'martes';
			case 'Wen': return 'miércoles';
			case 'Thu': return 'jueves';
			case 'Fri': return 'viernes';
			case 'Sat': return 'sábado';
			case 'Sun': return 'Domingo';
			
		}
		
	}
	
	
	
	/**
	
	* letras($n)
	
	* devuelve el valor de $n en letras es español
	
	* $n es un entero
	
	*
	
	* Copyleft 2011 Arnoldo Briceño
	
	*/ 
	
	public function letras($n){
	
	
	$cent=array(
	
	1=>'ciento', 'doscientos', 'trescientos', 'cuatrocientos', 
	
	'quinientos', 'seiscientos', 'setecientos', 'ochocientos', 
	
	'novecientos');
	
	$dec=array( '', '', '', 'treinta', 'cuarenta', 'cincuenta', 
	
	'sesenta', 'setenta', 'ochenta', 'noventa' );
	
	$uni=array( '', ' y un', ' y dos', ' y tres', ' y cuatro', 
	
	' y cinco', ' y seis', ' y siete', ' y ocho', ' y nueve');
	
	
	
	for ($i=0; $i<100;$i++){
	
	$d=(int)($i/10);
	
	$u=$i%10;
	
	$num[$i]=$dec[$d].$uni[$u];
	
	}
	
	
	
	$num[0] = '';
	
	$num[1] = 'un';
	
	$num[2] = 'dos';
	
	$num[3] = 'tres';
	
	$num[4] = 'cuatro';
	
	$num[5] = 'cinco';
	
	$num[6] = 'seis';
	
	$num[7] = 'siete';
	
	$num[8] = 'ocho';
	
	$num[9] = 'nueve';
	
	$num[10] = 'diez';
	
	$num[11] = 'once';
	
	$num[12] = 'doce';
	
	$num[13] = 'trece';
	
	$num[14] = 'catorce';
	
	$num[15] = 'quince';
	
	$num[16] = 'dieciseis';
	
	$num[17] = 'diecisiete';
	
	$num[18] = 'dieciocho';
	
	$num[19] = 'diecinueve';
	
	$num[20] = 'veinte';
	
	$num[21] = 'veintiun';
	
	$num[22] = 'veintidos';
	
	$num[23] = 'veintitres';
	
	$num[24] = 'veinticuatro';
	
	$num[25] = 'veinticinco';
	
	$num[26] = 'veintiseis';
	
	$num[27] = 'veintisiete';
	
	$num[28] = 'veintiocho';
	
	$num[29] = 'veintinueve';
	
	$num[30] = 'treinta';
	
	$num[40] = 'cuarenta';
	
	$num[50] = 'cincuenta';
	
	$num[60] = 'sesenta';
	
	$num[70] = 'setenta';
	
	$num[80] = 'ochenta';
	
	$num[90] = 'noventa';
	
	$num[100] = 'cien';
	
	
	
	if ($n<=100){
	
	return $num[$n];
	
	}else if($n<1000){
	
	$c=(int)($n/100);
	
	return ($cent[$c]." ".letras($n%100));
	
	}else if ($n<1000000){
	
	$c=(int)($n/1000);
	
	$p=letras($c);
	
	return("$p mil " .letras($n%1000));
	
	}else{
	
	$c=(int)($n/1000000);
	
	$p=letras($c);
	
	$q=($p=='un')?'millón':'millones';
	
	return("$p $q " .letras($n%1000000));
	
	}
	
	}
	
	
	
	/**
	
	* letras2($n)
	
	* devuelve el valor de $n en letras es español
	
	* $n es un número decimal con dos decimales separados
	
	* por un punto (.)
	
	*
	
	* Ejemplo
	
	* letras2(32.25)= treinta y dos con 25/100
	
	* Copyleft 2011 Arnoldo Briceño
	
	*/ 
	
	function letras2($monto){
	
	$cant=explode('.',$monto);
	
	$v1=($cant[0]==0)?'cero':letras($cant[0]);
	
	$v2='0.'.$cant[1];
	
	$v2=substr('0'.round($v2*100,0),-2);
	
	return $v1.' con '.$v2.'/100';
	
	}
	
	
	
	/**
	 * FunciÃ³n que ayuda en el debug. Es similar a la funciÃ³n nativa var_dump() de PHP pero en lugar de enviar la salida
	 * al browser, la guarda en un string. HabrÃ­a que mejorarla para que muestre el resultado "mÃ¡s lindo". 
	 */
	public static function var_dump($variable)
	{
		ob_start();
		print_r($variable);
		$s = ob_get_contents(); 
		ob_end_clean(); 
		return $s; 
	}
	
	/**
	 * FunciÃ³n que determina si un cuit es valido o no,
	 * retorna true si es valido, y false en caso contrario
	 * el parametro no contiene guiones, es un numero entero de 11 digitos
	 *
	 * @param $cuit (un numero entero de 11 digitos)
	 * @return boolean
	 */
	public static function cuitValido($cuit)
	{
	
		/**
		 * aca verificar si el $cuit es numerico o no
		 */
		  if(is_numeric($cuit)!=true)
		  {
		  	return false;
		  }
		$coeficiente[0]=5;
		$coeficiente[1]=4;
		$coeficiente[2]=3;
		$coeficiente[3]=2;
		$coeficiente[4]=7;
		$coeficiente[5]=6;
		$coeficiente[6]=5;
		$coeficiente[7]=4;
		$coeficiente[8]=3;
		$coeficiente[9]=2;
	
		$resultado=1;
	
		$sumador = 0;
		$verificador = substr($cuit, 10, 1); //tomo el digito verificador
	
		for ($i=0; $i <=9; $i=$i+1) {
			$sumador = $sumador + (substr($cuit, $i, 1)) * $coeficiente[$i];//separo cada digito y lo multiplico por el coeficiente
		}
	
		$resultado = $sumador % 11;
		$resultado = 11 - $resultado;  //saco el digito verificador
		if ($resultado==11)
		{
			$resultado=0; //caso unico que se puede dar cuando el $sumador es multiplo de 11, en ese caso la misma afip le pone como digito verificador el numero 0
		}
		$veri_nro = intval($verificador);
	
		if ($veri_nro <> $resultado) {
			return false;
		} else {
			return true;
		}
	
	}
	
	
}