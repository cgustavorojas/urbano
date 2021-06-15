// JavaScript Document

function muestra() {
	alert('hola2');
}

 function envioAjax(url,capa,valores,metodo,nombreFuncion) {
	
			var funcionResponse = function(transport){
				      	var response = transport.responseText || "no response text";
  
      				   	var capaContenedora = document.getElementById(capa);

						capaContenedora.innerHTML = response;
						// Cuando vuelve la ejecuta
						eval(nombreFuncion+'()');
						
						//nombreFuncion();
						//muestra();
					 //	  refrescarConversacion();
			};

			a = new Ajax.Request(url,
			 {
				    method:metodo,
  				    parameters: valores,
				    onSuccess: funcionResponse,
			    onFailure: function(){ alert('Error...') }
			  });
		
	}
	
