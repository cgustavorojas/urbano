<script language='javascript'>

var calendarWindow = null;
var calendarColors = new Array();
calendarColors['bgColor'] = '#D1E4FA';
calendarColors['borderColor'] = '#333366';
calendarColors['headerBgColor'] = '#143464';
calendarColors['headerColor'] = '#FFFFFF';
calendarColors['dateBgColor'] = '#7C9BBA';
calendarColors['dateColor'] = '#004080';
calendarColors['dateHoverBgColor'] = '#FFFFFF';
calendarColors['dateHoverColor'] = '#004080';
var calendarMonths = new Array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
var calendarWeekdays = new Array('Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do');
var calendarUseToday = false;
var calendarFormat = 'd/m/y';
var dateString = '1/1/2001';
var calendarStartMonday = true;

// }}}
// {{{ getCalendar()

function getCalendar(in_dateField,in_dateField_dia,in_dateField_mes,in_dateField_anio) 
{
    if (calendarWindow && !calendarWindow.closed) {
        alert('Calendar window already open.  Attempting focus...');
        try {
            calendarWindow.focus();
        }
        catch(e) {}
        
        return false;
    }

    var cal_width = 340;
    var cal_height = 330;

    // IE needs less space to make this thing
    if ((document.all) && (navigator.userAgent.indexOf("Konqueror") == -1)) {
        cal_width = 340;
    }

    calendarTarget = in_dateField;
    calendarTarget_dia = in_dateField_dia;
    calendarTarget_mes = in_dateField_mes;
    calendarTarget_anio = in_dateField_anio;

    
    calendarWindow = window.open('<?php echo $directorio_relativo; ?>include/calendario/calendar.html', 'dateSelectorPopup','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=0,dependent=no,width='+cal_width+',height='+cal_height);

    return false;
}

// }}}
// {{{ killCalendar()

function killCalendar() 
{
    if (calendarWindow && !calendarWindow.closed) {
        calendarWindow.close();
    }
}


</script>