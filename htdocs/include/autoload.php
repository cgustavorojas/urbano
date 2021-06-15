<?php
/**
 * Loading dinámico de clases. 
 * Este archivo debe incluirse en cada programa PHP que vaya a usar clases debajo de classes/
 * antes de usarlas. 
 * No hace falta incluir a mano los archivos de cada clase particular. 
 * 
 * @package default
 */

/**
 * Autoload
 * Función mágica de PHP que permite hacer el include de los archivos de clases en 
 * forma dinámica, en el momento en que se necesitan. Es llamado en forma automática
 * por PHP cuando hay que instanciar una clase nueva y recibe el nombre de la clase
 * a instanciar. 
 * 
 * Esta implementación busca clases en subdirectorios debajo de "classes/", permitiendo
 * que las clases se agrupen en subdirectorios. Si se da de alta un nuevo
 * subdirectorio, hay que agregarlo al arrays $dirs. 
 * 
 * @param string Nombre de la clase que hay que instanciar (pasado por PHP)
 * @return void
 */

function __autoload($class_name) 
{
	$dirs = array ('classes/', 
					'abm/classes/', 'abm/classes/inputs/', 'abm/classes/filtros/', 'abm/classes/cols/',
					'gral/classes/', 
					'cliente/classes/',
				);
	
	foreach ($dirs as $dir) {
		if (file_exists (dirname(__FILE__) . "/../" . $dir . $class_name . ".class.php")) {
			require  dirname(__FILE__) . "/../" . $dir . $class_name . '.class.php';		
			break; 
		}
	}
}

spl_autoload_register('__autoload');

