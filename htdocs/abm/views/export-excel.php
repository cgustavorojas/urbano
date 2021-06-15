<?php
/**
 * @package views
 * @author   
 */
?>
<?php


/*
 * Traigo las preferencias del usuario para definir temas relacionados con configuración regional 
 */
$pref = $screen->getUserPrefs(); 

$date_format = $pref->get('excel_date_format', 'Y-m-d');
$ts_format   = 'Y-m-d H:i:s';
$num_format  = $pref->get('excel_num_format', 'english');
$list_sep    = $pref->get('excel_list_sep', ',');

if ($list_sep == 't') {	
	$list_sep = "\t";	
	$file_extension = '.tab';
} else {
	$file_extension = '.csv';
}

/*
 * Seteo los encabezados para que el archivo se abra con Excel (a pesar de ser de texto) y para 
 * que no se guarde en el cache del browser.
 *  
 * Tema encoding: todo el sistema trabaja en Unicode (tanto la base como el PHP), pero archivos
 * de texto CSV en unicode son para problema, así que convierto todo a LATIN1 (ISO-8859-1).
 */
ini_set('default_charset','ISO-8859-1');
header ('Content-type: application/vnd.ms-excel; charset=ISO-8859-1');
header ('Content-disposition: attachment; filename="' . utf8_decode($screen->getTitulo()) . $file_extension . '"');
header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header ("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past



/*
 * Ejecuto el query y mando el resultado
 */
$data = $screen->executeQuery(); 


if ($num_format == 'english') {
	$nf1 = '.'; $nf2 = ',';
} else {
	$nf1 = ','; $nf2 = '.';
}

$colCount = $data->getNumFields();

$types = array();

/*
 * Mando una primer línea con los títulos de las columnas.
 */
$sep = "";
for ($i = 0 ; $i < $colCount ; $i++)
{
		$leyenda = $data->getFieldName($i);
		if ($screen->getMagicFieldNames()) {
			$leyenda = Utils::toUpperWithBlanks($leyenda);
		} 
		echo $sep . '"' . utf8_decode($leyenda) . '"';
		$sep = $list_sep;
		$types[] = $data->getFieldType($i);
}
echo "\n"; 


while ($row = $data->getRowWithIndexes())
{
	$sep = ""; 
	for ($i = 0 ; $i < $colCount ; $i++) 
	{
		$valor = $row [$i];
		$type = $types[$i];
		
		echo $sep;
		if (strpos($type, 'int') !== false) {
				echo $valor; 
		} else if (strpos($type,'float') !== false || strpos($type, 'numeric') !== false) {
				echo '"' . number_format ($valor, 2, $nf1, $nf2) . '"';
		} else if (strpos($type,'timestamp') !== false) {
				echo is_null($valor) ? '""' : '"' . date ($ts_format, strtotime($valor)) . '"';
		} else if (strpos($type,'date') !== false) {
				echo is_null($valor) ? '""' : '"' . date ($date_format, strtotime($valor)) . '"';
		} else if (strpos($type,'bool') !== false) {
				echo is_null($valor) ? '""' : ($valor == 't' ? utf8_decode('"Sí"') : '"No"');
		} else {
				echo '"' . utf8_decode($valor) . '"';
		}		

		$sep = $list_sep; 

	}	// ciclo columnas
	echo "\n";
	
} // ciclo filas

