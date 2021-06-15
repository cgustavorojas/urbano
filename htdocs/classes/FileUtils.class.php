<?php
/**
 * @package default
 */

/**
 * Funciones varias para manejo de archivos, directorios y la configuración
 * asociada a los mismos. 
 *
 * @package default
 */
class FileUtils
{
	const RELATIVE_PATH_TMP  = '../../tmp';
	const RELATIVE_PATH_DATA = '../../datos';
	
	/**
	 * Dado un nombre de archivo, devuelve una ruta completa, absoluta, a ese
	 * archivo dentro del directorio temporal.
	 * Si se especifica $subdir, la rutina asegura que subdirectorio exista dentro
	 * del directorio temporal. 
	 * 
	 * @param string $archivo Nombre del archivo (sin ruta)
	 * @param string $subdir Si se especifica, la ruta se arma a un subdirectorio del temporal
	 * @return string Ruta completa al archivo
	 */
	public static function mkTmpPath ($archivo, $subdir = null)
	{
		$dir = realpath (dirname (__FILE__) . '/' . FileUtils::RELATIVE_PATH_TMP);
		
		if (! is_null($subdir)) {
			if (! is_dir ("$dir/$subdir")) {
				mkdir ("$dir/$subdir");
			}
			return "$dir/$subdir/$archivo";
		}
		return "$dir/$archivo";			
	}	
	
	/**
	 * Dado un nombre de archivo, devuelve una ruta completa, absoluta, a ese
	 * archivo dentro del directorio de archivos permanentes.
	 * Si se especifica $subdir, la rutina asegura que subdirectorio exista dentro
	 * del directorio. 
	 * 
	 * @param string $archivo Nombre del archivo (sin ruta)
	 * @param string $subdir Si se especifica, la ruta se arma a un subdirectorio del directorio de archivos
	 * @param string Ruta completa al archivo
	 */
	public static function mkDataPath ($archivo, $subdir = null)
	{
		$dir = realpath (dirname (__FILE__) . '/' . FileUtils::RELATIVE_PATH_DATA);
		
		if (! is_null($subdir)) {
			if (! is_dir ("$dir/$subdir")) {
				mkdir ("$dir/$subdir");
			}
			return "$dir/$subdir/$archivo";
		}
		return "$dir/$archivo";			
	}		
}