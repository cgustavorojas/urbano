<?php
/**
 * @package abm
 */

/**
 * Tipo específico de PdfReport para imprimir una pantalla tipo QueryScreen. 
 *
 * @package abm
 */
class QueryReport extends PdfReport
{
	private $screen;
	private $userPref; 				// preferencia de usuario (formato de número, de fechas, etc.) 
	private $cols; 					
	private $anchos; 				// array con los anchos de las columnas
	private $headers; 				// array con los encabezados de las columnas
	private $aligns; 				// alineación de las columnas
	private $headerLines = 1; 		// cantidad de líneas que ocupa el encabezado (dependiendo del encabezado más largo y el ancho disponible
	
	private $ignorarMaxLength = false; // Si es TRUE, las columnas se imprimen en su totalidad, sin importar el máximo definido (es una preferencia del usuario). 
	
	private $logo = 'imagenes/logo.png';
	
	private	$font = 'helvetica';	// nombre del font a utilizar para todo el reporte
	private $tituloSize = 12; 		// tamaño de letra del título
	private $filtrosSize = 8; 		// tamaño de letra para los filtros
	private $footerSize = 6;		// tamaño de letra para el pie de página
	private $footerY = -15;			// posición del pie de página
	
	private $rowSize = 6; 			// tamaño de letra para las filas de datos y encabezados
	private $rowH = 4;				// alto de una fila de datos  
	
	private $headersH = 4;			// alto de la fila de encabezados (de cada línea y tuviera más de una)

	private $par = 0; 

	/**
	 * Imprime el encabezado del reporte, formato por el logo, el título de la pantalla,
	 * la lista de filtros actualmente aplicados y los encabezados de las columnas. 
	 */
	public function header()
	{
		//$this->Image($this->logo, 10, 10, 50);
		$this->Ln(25);
		$this->setFontSize($this->tituloSize);
		$this->Cell(0, 6, $this->screen->getTitulo(), '', 1, 'C');
		$this->Ln();

		//----------------------- Filtros ------------------------------
		
		$this->setFontSize($this->filtrosSize);
		$filtros = $this->screen->getFiltros()->getPrintableValues();
	    
		$this->Cell(30, 5, 'Fecha:', '', 0, 'R');
		date_default_timezone_set("America/Argentina/Buenos_Aires");
		
		$this->Cell(0,5, date('d/m/Y H:i:s'));
		$this->Ln();
		
		if (count($filtros) > 0)
		{
		    foreach ($filtros as $txt => $value) {
		    	$this->Cell(30, 5, $txt . ": ", '', 0, 'R');
		    	$this->Cell(0, 5, $value);
		    	$this->Ln();
		    }
		}
		
		$prefs = UserPref::load();
		
		foreach ($this->screen->getTotales() as $total) 
		{
		    	$this->Cell(30, 5, $total['txt'] . ": ", '', 0, 'R');
		    	$this->Cell(0, 5, $prefs->toString($total['valor'], Tipo::MONEY));
		    	$this->Ln();
		}		
				
		$this->Ln();
		
		//---------------- Encabezados de columnas ----------------------
		
		$anchos = $this->anchos; 

		$this->setFillColor(125, 125, 125);
		
		for ($i = 0 ; $i < count($this->headers) ; $i++) {
			$this->Cell($this->anchos[$i], $this->headersH * $this->headerLines, '', 1, 0, '', true);
		}
		$this->setX($this->lMargin);
		
		$this->setFont($this->font, 'b', $this->rowSize);
		for ($i = 0 ; $i < count($this->headers) ; $i++)
		{
				$x = $this->getX();
				$y = $this->getY(); 
				$this->MultiCell($anchos[$i], $this->headersH, $this->headers[$i], 0, 'C');
				$this->setY($y);
				$this->setX($x + $anchos[$i]);
		}
		$this->Ln($this->headerLines * $this->headersH);
		//$this->Ln($this->rowH); // una línea en blanco entre encabezados y la primer línea de datos
		
		$this->par = 0; 	
	}
	
	public function footer()
	{
		$this->setFontSize($this->footerSize);
		$this->setY ($this->footerY);  
		$this->Cell(0,10,$this->PageNo(), 0, 0, 'C');
	}
	
	
	/**
	 * Calcula el ancho que le corresponde a cada columna. 
	 * Tiene en cuenta el ancho máximo de la página, la cantidad de columnas y el ancho "ideal" de c/u dado por $col->getPrintWidth().

	 * @return array Un array de números, donde cada uno es el ancho de una columna. 
	 */
	public function calcularAnchos($cols)
	{
		//
		// Paso 1: el ancho de cada columna es el ancho exacto que necesita para mostrar los títulos
		$anchos = array(); 
		foreach ($cols as $col) {
				$anchos[] = $this->GetStringWidth($col->getPrintTxt()) + 2 * $this->cMargin;
		}


		//
		// Paso 2: hago una recorrida de todos los registros para sacar el valor más largo de cada columna 
		//
		$this->setFontSize($this->rowSize);
		$data = $this->screen->executeQuery();
		$row = $data->getRow();
		while ($row)
		{
			for ($i = 0 ; $i < count($this->cols) ; $i++) {
				$s = $this->formatCol($this->cols[$i], $row);
				$anchos[$i] = max($anchos[$i], $this->GetStringWidth($s) + 2 * $this->cMargin);	// calculo ancho del texto más márgen de la celda (a izq. y der.)
				//echo $s . " - " . $this->getStringWidth($s) . "\n<br>";
			}
			$row = $data->getRow();
		}
		
		$total = array_sum($anchos);	// sumo el ancho de todas las columnas 
				
		$disponible = $this->w - $this->rMargin - $this->lMargin;

		//
		// Paso 3: tengo 2 casos: o me sobra espacio, o me falta 
		//
		
		if ($disponible > $total) {		//  me sobra espacio, expando proporcionalmente todas las columnas para ocupar el ancho completo
			$correccion = $disponible / $total; 
			
			for ($i = 0 ; $i < count($anchos) ; $i++) {
				$anchos[$i] = $anchos[$i] * $correccion;
			}
		} else {	// me falta espacio. No quiero reducir proporcionalmente porque perjudico a las columnas más angostas
					// Trato de achicar las columnas más anchas y dejar sin modificar las más angostas
					// Defino como "ancha" aquella columna que ocupa más que lo que le tocaría si se dividiera el espacio por igual
			
			$justo = $disponible / count($this->cols); 
			
			$total_cols_anchas = 0; 
			for ($i = 0 ; $i < count($anchos) ; $i++)
			{
				if ($anchos[$i] > $justo)
					$total_cols_anchas = $total_cols_anchas + $anchos[$i]; 
			}
			$total_cols_angostas = $total - $total_cols_anchas; 
			
			$correccion = ($disponible - $total_cols_angostas) / $total_cols_anchas; 
			
			for ($i = 0 ; $i < count($anchos) ; $i++)
			{
				if ($anchos[$i] > $justo)
					$anchos[$i] = $anchos[$i] * $correccion; 
			}
			
		}
		
		return $anchos; 
	}

	/**
	 * Dada una columna y un registro, devuelve el valor de la columna ya formateado según
	 * las preferencias del usuario respecto de formato de fechas, números, etc.
	 * @param Column $col Columna (definición)
	 * @param array  $row Registro actual
	 * @return string
	 */
	public function formatCol($col, $row)
	{
		$maxLength = $col->getMaxLength();
		$ret = trim($this->userPref->toString($row[$col->getCampo()], $col->getTipo()));
		if ( ! $this->ignorarMaxLength && ! is_null($maxLength) && ($maxLength > 0) && (strlen($ret) > $maxLength)) {
			$ret = substr($ret, 0, $maxLength - 3) . '...';
		}	
		return $ret;	
	}
	
	public function run($screen)
	{
		$this->screen = $screen;
		$this->userPref = $screen->getUserPrefs();

		$this->setFont($this->font);
			
		$pageSize = $this->userPref->get('q_print_page_size');

		$pageSizeArr = explode('x', $pageSize);	// q_print_page_size viene del estilo "21.50x33.00" y necesito un array(21.50, 33.00)
		$pageSizeArr[0] = $pageSizeArr[0] * 10;	// la medida viene en centímetros y fpdf la espera en milímetros
		//$pageSizeArr[1] = $pageSizeArr[1] * 10; // la medida viene en centímetros y fpdf la espera en milímetros   
		
		$this->setPageSize($pageSizeArr[0],40*10);//$pageSizeArr[1]
		
		$this->ignorarMaxLength = ($this->userPref->get('q_print_ignore_maxlength') == 1);
		
		/*
		 * Hago una primer pasada por las columnas para cálculos auxiliares:
		 * 	  1. Me quedo sólo con las columnas activas
		 *    2. Calculo el ancho de las columnas 
		 * 	  3. Calculo si la línea de encabezados tendrá 1 líneas o más de alto
		 */
		$this->cols = array();
		$this->headers = array(); 
		foreach ($screen->getCols()->getAll() as $col) {
			if ($col->isActive()) {
				$this->cols[] = $col; 
				$this->headers[] = $col->getPrintTxt();
				switch ($col->getAlign()) {
					case Column::LEFT   : $this->aligns[] = 'L'; break;
					case Column::CENTER : $this->aligns[] = 'C'; break;
					case Column::RIGHT  : $this->aligns[] = 'R'; break;
				} 
			}
		}
		
		$this->anchos = $this->calcularAnchos($this->cols);
		
		$cols   = $this->cols; 
		$anchos = $this->anchos;
		
		$this->setFontSize($this->rowSize);	// pongo el tamaño de letra del encabezado para poder calcular bien los anchos
		for ($i = 0 ; $i < count($this->headers) ; $i++) {
			$this->headerLines = max ($this->headerLines, $this->getNoLines($this->headers[$i], $this->anchos[$i]));
		}
		
		$this->AddPage();	  
		
		$screen->countRows();	// además de contar registros, calcula los totales. 
		$data = $screen->executeQuery(); 
		$row = $data->getRow();
		
		$this->SetFontSize($this->rowSize);
		
		//************************ CICLO PRINCIPAL POR LOS REGISTROS ******************************
		$par = 0; 
		while ($row)
		{		
			//
			// calculo cuántas líneas va a ocupar este registro
			//
			$lineas = 1;
			$textos = array(); 
			for ($i = 0 ; $i < count($cols) ; $i++) 
			{
				$textos[$i] = $this->formatCol($cols[$i], $row);
				//$textos[$i] = $textos[$i] . "\n" . $this->getStringWidth($textos[$i]);
				//echo $textos[$i] . " - " . $this->getStringWidth($textos[$i]) . "\n<br>";		
				$lineas = max($lineas, $this->getNoLines($textos[$i], $anchos[$i]));
			}

			
			if ($this->getY() + $this->bMargin + $lineas * $this->rowH > $this->h) {
				$this->AddPage();
			}
			
			//
			// Sombreado fila par/impar
			// 
			if ($this->par) {
				$this->SetFillColor(230, 230, 230);
				$this->Cell(0, $this->rowH * $lineas, '', 0, 0, 0, true);
				$this->setX($this->lMargin);	
			}
			$this->par = (! $this->par);
			 
			//
			// Imprimo cada celda
			//
			for ($i  = 0 ; $i < count($cols) ; $i++)
			{
				$x = $this->getX();
				$y = $this->getY(); 
				
				$this->MultiCell($anchos[$i],$this->rowH,$textos[$i],'',$this->aligns[$i]);
				$this->setY($y); 
				$this->setX($x + $anchos[$i]);
			}
			$this->Ln($lineas * $this->rowH);
			
			$row = $data->getRow();
		}
		
		
	}
		
}