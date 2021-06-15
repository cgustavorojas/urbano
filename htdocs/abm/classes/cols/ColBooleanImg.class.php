<?php

class ColBooleanImg extends ColBoolean
{
	public function __construct ($txt, $campo = NULL) 
	{
		parent::__construct($txt, $campo); 
		$this->setMsgFalse('');
		$this->setImgTrue('/abm/imagenes/seleccionar.gif');	
	}
}