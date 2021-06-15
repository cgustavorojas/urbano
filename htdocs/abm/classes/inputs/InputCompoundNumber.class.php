<?php

/**
 * 
 * Wrapper del control InputCompound que genera un Input compuesto de solo controles tipo InputNumber. 
 * De esta manera solo hay que escribir los nombres de los campos en vez de los "new InputNumber('xxx')". 
 * Ademas permite configurar facilmente el tamaño de los inputs con el parámetro $width
 * @author Dan
 *
 */

class InputCompoundNumber extends InputCompound
{
	public function __construct($txt, $id, $inputs, $sql, $width = 5, $screenName = null, $tipo = Tipo::STRING)
	{
		$classes = array(); 

		foreach ($inputs as $input)
		{
			$i = new InputNumber($input);
				$i->setSize($width);
			$classes[] = $i; 
		}

		parent::__construct($txt, $id, $classes, $sql, $screenName, $tipo);
	}
}