function esNumerico (str) {
    var re_date = /^(\d+)$/;
    if (!re_date.exec(str)) {
    	return 0;
    } 
    else {
        return 1;
    }
}
function uncheckRadios(sform,selement){
	for (i = 0 ; i < document.forms[sform].elements[selement].length ; ++i ) {
		document.forms[sform].elements[selement][i].checked = false;
	}
}
function habDesHabXCheck(sform, selement, delement) {
	if (document.forms[sform].elements[selement].checked) {
		document.forms[sform].elements[delement].disabled=false;
	}
	else {
		document.forms[sform].elements[delement].disabled=true;
	}
}
function habDesHabXRadio(sform, selement, svalue, delement) {
	if (document.forms[sform].elements[selement][svalue].checked) {
		document.forms[sform].elements[delement].disabled=false;
	}
	else {
		document.forms[sform].elements[delement].disabled=true;
	}
}
function abrir(accion,nombre,width,height) {
    window.open(accion, nombre,"width=" + width + " ,height=" + height + " ,status=no,resizable=0,top=10 ,left=10,scrollbars=1");
}
function validaHora (str_time) {
    var re_time = /^(\d+)\:(\d+)\:(\d+)$/;
    var re_time2 = /^(\d+)\:(\d+)$/;
    str_time = trim(str_time);
    if ((!re_time.exec(str_time)) && (!re_time2.exec(str_time))) {
	return 0;
    } 
    else {
	var min = RegExp.$2;
	var hor = RegExp.$1;
        if (check_hora(hor,min) == 0) {
	    return 0;
	}
	else {
	    return 1;
	}
    }
}
function validaFecha (str_date) {
    var re_date = /^(\d+)\-(\d+)\-(\d+)$/;
    str_date = trim(str_date);
    if (!re_date.exec(str_date)) {
	return 0;
    }
    else {
	var ano = RegExp.$3;
	var mes = RegExp.$2;
	var dia = RegExp.$1;
		if (ano < 1900) { return 0; }
		if (check_fecha(ano,mes,dia) == 0) {
	    	return 0;
		}
		else {
			return 1;
		}
    }
}
function check_fecha(ano,mes,dia) {

    var todok=1;
	if (dia>31 || dia<1) { todok = 0};
	if ( ((mes == 4) || (mes == 6) || (mes == 9) || (mes == 11)) && (dia >30) ) { todok=0; }
	if ( (ano%4) == 0) {diasfeb = 29}
	else {diasfeb = 28}
	if ( (mes == 2) && (dia > diasfeb) ) { todok=0; }
	if ( (mes <1) || (mes >12) || (mes == "") ) { todok=0; }
	return (todok);
}
function check_hora(hor,min) {

        var todok=1;
	
	if ((hor < 0) || (hor >24)) { todok=0; }
	if ((min < 0) || (min >59)) { todok=0; }

	return (todok);
}
function compruebaAno(fecha,vano) {
	var re_date = /^(\d+)\-(\d+)\-(\d+)$/;
	str_date = trim(fecha);
	if (!re_date.exec(fecha)) {
		return 0;
	}
	else {
		var ano = RegExp.$3;
		var mes = RegExp.$2;
		var dia = RegExp.$1;
		if (ano > vano) {
			return 0;
		} else {
			return 1;
		}
	}

}
function trim(inputString) {
    if (typeof inputString != "string") { 
	return inputString; 
    }
    var retValue = inputString;
    var ch = retValue.substring(0, 1);
    while (ch == " ") { 
	retValue = retValue.substring(1, retValue.length);
	ch = retValue.substring(0, 1);
    }
    ch = retValue.substring(retValue.length-1, retValue.length);
    while (ch == " ") { 
	retValue = retValue.substring(0, retValue.length-1);
        ch = retValue.substring(retValue.length-1, retValue.length);
    }
    while (retValue.indexOf("  ") != -1) { 
	retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); 
    }
    return retValue; 
} 
function keyCheck(eventObj, obj)
{
    var keyCode
    if (document.all){ 
	keyCode=eventObj.keyCode
    }
    else{
	keyCode=eventObj.which
    }	
    var str=obj.value

    if((keyCode<48 || keyCode >57) && (keyCode != 8) && (keyCode<28 || keyCode >31) && (keyCode != 9)){ 
	return false
    }
    return true
    
}

function huboCambios(direccion) {
	if (document.forms[0].cambios.value == 1) {
		if (confirm ("Se realizaron cambios en el formulario, realmente desea salir sin guardar?")) {
			document.location.href=direccion;
			return ;
		}
		else {
			return ;
		}
	} else {
		document.location.href=direccion;
		return ;
	}
}
function deshabFormXInput(sform,nombreCampo,valor) {
	var tof; 
	if (document.forms[sform].elements[nombreCampo].value == valor) {
		tof = true;
	} else {
		tof = false; 
	}

	for (var a = 0  ; a < document.forms[sform].length ; a++) {
		document.forms[sform].elements[a].disabled = tof;
	}
	document.forms[sform].elements[nombreCampo].disabled = false;
}

function deshabFormXRadio(sform,nombreCampo,selected) {
	var tof; 
	if (document.forms[sform].elements[nombreCampo][selected].checked) {
		tof = true;
	} else {
		tof = false; 
	}

	for (var a = 0  ; a < document.forms[sform].length ; a++) {
		document.forms[sform].elements[a].disabled = tof;
	}
	for (var b = 0 ; b < document.forms[sform].length ; b++ ) {
		document.forms[sform].elements[nombreCampo][b].disabled = false;
	}
}
function deshabCampoXInput(sform,scampo,valor,dcampo) {

	var tof; 
	switch (document.forms[sform].elements[scampo].type) {
		case "text": 
			if (trim(document.forms[sform].elements[scampo].value) == valor) {
				tof = true; //Deshabilitado
			} else {
				tof = false; // Habilitado
			}
		break;
		case "checkbox":
			if (document.forms[sform].elements[scampo].checked) {
				tof = true;
			} else {
				tof = false;
			}
		break;
		default:
			if (document.forms[sform].elements[scampo][valor].checked) {
				tof = true;
			} else {
				tof = false;
			}
		break;
	}
	switch (document.forms[sform].elements[dcampo].type) {
		case "text":
		case "textarea":
		case "checkbox": 
			document.forms[sform].elements[dcampo].disabled = tof;
		break;
		default:
			for (var a = 0 ; a < document.forms[sform].elements[dcampo].length ; a++) {
				document.forms[sform].elements[dcampo][a].disabled=tof;
			}
		break;
	}

}
