
	function RestrictNumericInput ( evt ) {
		var keyCode = evt.which ? evt.which : evt.keyCode;
		return ((keyCode >= 45 && keyCode <= 57) 
				|| (keyCode == 8) 
				|| (keyCode == 9) 
				|| (keyCode == 16)); 
	}
