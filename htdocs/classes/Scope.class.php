<?php
/**
 * @package abm
 * @author 
 */

/**
 * Enumeración de constantes y una rutina básica para definir el Scope de una variable o parámetro. 
 *
 * @package abm 
 */
class Scope
{
	const ALL = "all";
	const GET = "get";
	const POST = "post"; 
	const SESSION = "session"; 
	
	/**
	 * Dado un nombre de variable y un scope, devuelve el valor de esa variable en el 
	 * primer scope que la encuentra. 
	 * 
	 * @return El valor encontrado o NULL.
	 */
	public static function get($variable, $scope)
	{
		if ($scope == Scope::ALL || $scope = Scope::SESSION) {
			if (isset($_SESSION[$variable]) && ! Utils::isNullOrBlank ($_SESSION[$variable])) {
				return($_SESSION[$variable]);
			}
		}
		
		if ($scope == Scope::ALL || $scope = Scope::POST) {
			if (isset($_POST[$variable]) && ! Utils::isNullOrBlank($_POST[$variable])) {
				return ($_POST[$variable]);
			}
		}
		
		if ($scope == Scope::ALL || $scope = Scope::GET) {
			if (isset($_GET[$variable]) && ! Utils::isNullOrBlank($_GET[$variable])) {
				return ($_GET[$variable]);
			}
		}
		return NULL; 
	}
	
}