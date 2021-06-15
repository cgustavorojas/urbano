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
$num_format = $pref->get('excel_num_format', 'english');
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
	
/*
 * Mando una primer línea con los títulos de las columnas.
 */
$sep = "";
foreach ($screen->getCols()->getAll() as $col)
{
	if ($col->isActive()) {
		echo $sep . '"' . utf8_decode($col->getTxt()) . '"';
		$sep = $list_sep;
	}
}
echo "\n"; 



if ($num_format == 'english') {
	$nf1 = '.'; $nf2 = ',';
} else {
	$nf1 = ','; $nf2 = '.';
}

while ($row = $data->getRow())
{
	$sep = ""; 
	foreach ($screen->getCols()->getAll() as $col)
	{
		if ($col->isActive())
		{
			$valor = $row [ $col->getCampo() ];
			
			echo $sep;
			switch ($col->getTipo()) 
			{
				case Tipo::NUMBER: 
					echo $valor; 
					break; 
					
				case Tipo::MONEY:
					echo '"' . number_format ($valor, 2, $nf1, $nf2) . '"';
					break; 
					
				case Tipo::STRING:
					echo '"' . utf8_decode($valor) . '"';
					break;
					
				case Tipo::BOOLEAN:
					if ($valor == 't' || $valor == 1 || $valor === true) { echo 'Si';}
					else {echo 'No';}	
					break;
					

				case Tipo::DATE:
					
					if(trim($valor) == '' )
					{
						echo "";	
					} 
					else 
					{
						echo '"' . date ($date_format, strtotime($valor)) . '"';
					}
					
					break; 
			}
			$sep = $list_sep;
		}	
	}	// ciclo columnas
	echo "\n";
	
} // ciclo filas

