<?php
/**
 * Crea objetos globales que estarán disponibles en cualquier página. 
 * 
 * Llamado desde includes_general.php, que a su vez es llamado en todas las páginas de 
 * todos los módulos. 
 * 
 * @package default
 */

/** Conexión con la base de datos */
$dbConn = new Base();

/** Objeto global para logging */
$_log = new Log('BA');

/** No se usa para nada, pero hay muchas referencias que hay que eliminar para que no de error de variable no existente */
$menu_gral = null;

