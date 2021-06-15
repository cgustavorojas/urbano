function imprimirDivImpresion(div_imprimir) {

	var bName = navigator.appName;
	var bVer = parseFloat(navigator.appVersion);
	
	var contenido = document.getElementById(div_imprimir).innerHTML;
	ventana=window.open("","ventana"," width=800, height=600 ");
	ventana.document.open();
	ventana.document.write('<html><head><title>Urbano</title><link rel="stylesheet" type="text/css" href="../css/css.css"><\/head><body style=\"background-color: #FFFFFF\">');
	
	ventana.document.write(contenido);
	ventana.document.close();
	ventana.print();
	ventana.close();

}
