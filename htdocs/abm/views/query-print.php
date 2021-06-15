<?php
/**
 * @package views
 * @author   
 */

$data = $screen->executeQuery(); 
$pageNo = 0; 
$rowNo = 0;
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es" lang="es">
<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title><?php echo $screen->getTitulo(); ?></title>
		
<style>
.pbreak { page-break-before: always; }
</style>		
</head>
	
<body>
	
<?php

$row = $data->getRow(); 

$userPref = $screen->getUserPrefs();

while ($row)
{
	$pageNo++;
	
	if ($pageNo > 1) {
		echo "<h1 class='pbreak'>" . $screen->getTitulo() . "</h1>";
	} else {
		echo "<h1>" . $screen->getTitulo() . "</h1>";
	}
	
	echo "<table>";  
	echo "<tr>";
	
	foreach ($screen->getCols()->getAll() as $col) {
		echo "<th>" . $col->getTxt() . "</th>";
	}
	
	echo "</tr>\n";
	
	$rowNo = 0; 
	while ($row && $rowNo < $screen->getPrintPageSize())
	{
		$rowNo++;
		echo "<tr>";
		foreach ($screen->getCols()->getAll() as $col) 
		{
			echo "<td>" . $userPref->toString($row [$col->getCampo()], $col->getTipo()) . "</td>";
			
		} // foreach campo
		echo "</tr>\n";
		
		$row = $data->getRow();
		
	} // mientras haya registros y no supere el tamaño de la página 
	
	echo "</table";
	
} // mientras haya registros

?>
</body>
</html>
