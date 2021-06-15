<?php
/**
 * @package abm
 */

/**
 * Tipo específico de InputString para ingresar nros de CUIT, con su respectiva validación. 
 * @package abm
 */
class InputCuit extends InputString
{
	
	public function __construct($txt, $campo = NULL, $obligatorio = false)
	{
		parent::__construct ($txt, $campo, 11, 13, $obligatorio);
	}

	/**
	 * Modifica levemente el comportamiento standard de manera que si el usuario ingresa el valor con guiones, 
	 * lo toma, pero le saca los guiones, de manera que el cuit siempre se guarda como puros números sin guiones. 
	 */
	public function parseRequest()
	{
		parent::parseRequest(); 
		
		if (! is_null($this->getValue())) { 
			$this->setValue(str_replace('-', '', $this->getValue()));
		}
	}
	
	public function validar()
	{
		$ok = parent::validar();  
		
		if ($ok) {
			 
			$this->setError(NULL);
			
			if (strlen(str_replace('-', '', $this->getValue())) != 11) {
				$this->setError("Debe ingresar los 11 caracteres del CUIT.");
				return false; 
			}
			
			return $this->validaCuit(str_replace('-', '', $this->getValue()));
			 
		}
		
		return $ok; 
	}
	
	public function validaCuit($cuit)
	{
	
		$coeficiente[0]=5;
		$coeficiente[1]=4;
		$coeficiente[2]=3;
		$coeficiente[3]=2;
		$coeficiente[4]=7;
		$coeficiente[5]=6;
		$coeficiente[6]=5;
		$coeficiente[7]=4;
		$coeficiente[8]=3;
		$coeficiente[9]=2;
	
		$resultado=1;
	
		$sumador = 0;
		$verificador = substr($cuit, 10, 1); //tomo el digito verificador

		for ($i=0; $i <=9; $i=$i+1) { 
			$sumador = $sumador + (substr($cuit, $i, 1)) * $coeficiente[$i];//separo cada digito y lo multiplico por el coeficiente
		}

		$resultado = $sumador % 11;
		$resultado = 11 - $resultado;  //saco el digito verificador
		if ($resultado==11)
		{
			$resultado=0; //caso unico que se puede dar cuando el $sumador es multiplo de 11, en ese caso la misma afip le pone como digito verificador el numero 0
		}
		$veri_nro = intval($verificador);

		if ($veri_nro <> $resultado) {
			$this->setError("El número de CUIT no es válido.");
			return false; 
		} else { 
			return true; 
		}
		
	}
}