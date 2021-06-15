<?php

/**
 * Este Input mágico permite crear un input compuesto de varios inputs (actualmente solo del tipo InputTextBox o InputCombo o sus extents)
 * que componen entre si un solo dato (por ej: en objeto del gasto: inciso, ppal, pcial y subpcial). Lo particular de este control es que 
 * para cada dato ingresado en uno de los inputs que lo componen chequea contra la BD si existe un único registro compuesto por la totalidad 
 * de datos completos y si existe muestra el valor solicitado a la derecha del control. Por otro lado permite (opcionalmente) utilizar una 
 * LookupScreen para buscar los datos tambien como lo hace el InputQuery. 
 * Como último beneficio, el control autocompleta los inputs restantes si para una selección parcial de datos se encuentra un único registro. 
 * Por ejemplo: si al completar inciso, principal y parcial ya se encuentra un y solo un registro en la DB, el control
 * completará automáticamente el input restante (subparcial) con los valores que corresponden a ese registro. 
 * 
 * Ej de uso: 
 * 
 * class PruebaAlta extends AltaScreen
 * {
 *	public function init() 
 * 	{
 * 		$i = new InputCompound('Objeto', 'id_objeto', 
 * 					array (
 * 						new InputNumber('inciso'), 
 * 						new InputNumber('principal'),
 * 						new InputNumber('parcial'), 
 * 						new InputNumber('subparcial')
 * 					), 
 * 					'SELECT id_objeto, txt, #CAMPOS# FROM presu.objeto WHERE ej = 2012 AND #FILTROS#' 
 * 					, 'PreObjetoLookup');
 *
 * 		$this->addInput($i);
 *
 * 		parent::init();
 * 	}
 * 	
 * }
 * 
 * @author DAW
 *
 */
class InputCompound extends Input
{
	private $_sql; 
	private $_txt; 
	private $_screen;
	private $inputs; 

	/**
	 * Atentos a los parámetros! 
	 * @param string $txt		Como en todo input: el nombre que se muestra en el form
	 * @param string $id		El nombre del primary key del objeto en la DB . Ej: id_objeto
	 * @param array  $inputs	Array de objetos InputTextBox o InputCombo (pueden combinarse entre sí)
	 * @param string $sql		Consulta sql con el siguiente formato : 								\
	 * 								SELECT col_pk, col_txt, #CAMPOS# FROM tabla WHERE ... #FILTROS#		\
 	 * 							Aclaraciones: 															\
 	 * 								* Los campos de las columnas tienen que estar en el orden el dejemplo, primero la columna pk y luego el txt			\
 	 * 								* La columna el string #CAMPOS# solo se utiliza en caso de optar por utilizar la LookupScreen (seteando el screen)	\
 	 * 								* El string #FILTROS# solo debe insertarse si hay un WHERE delante, al igual que otros controles
	 * @param string $screen	Al igual que en el InputQuery, es el nombre del LookupScreen al que redirije la lupa
	 * @param string $tipo		Especifica el tipo de dato que es el $id. Este parámetro es opcional y no se usa para nada importante aún. 
	 *
	 * @throws Exception		
	 */
	public function __construct($txt, $id, $inputs, $sql, $screen = null, $tipo = Tipo::STRING)
	{
		$this->setSql($sql);
		$this->setTxt($txt);
		$this->setId($id);
		$this->setSelectable(true);
		$this->setScreen($screen);

		$this->inputs = new ListaInputs();
		
		if (!is_array($inputs)) throw new Exception('$inputs debe ser un array de objetos tipo InputTextBox o InputCombo');
		if (count($inputs) == 0) 	throw new Exception('$inputs no puede ser un array vacío');

		foreach ($inputs as $i) 
		{
			if ($i instanceof InputTextBox || $i instanceof InputCombo)
			{
				$this->inputs->add($i); 
			}
			else
			{
				throw new Exception('Uno de los campos no es una instancia de Input válida');
			} 
		} 
	}

	public function getSelectableId() 
	{
		foreach ($this->inputs->getAll() as $i)
		{
			if ($i->isSelectable()) return $i->getCampo();
		} 
	}

	public function getHtml()
	{

		$id = $this->getId();
		$id2 = $id . "_txt";
		$id3 = $id . "_btn1";
		$id4 = $id . "_btn2";

		$html = "<tr><td class='label'>" . $this->getTxt() . ($this->isObligatorio() ? '*' : '') . "</td><td class='input'>";

		foreach ($this->inputs->getAll() as $i)
		{
			$html = $html . $i->getHtmlInput() . "&nbsp;";
		}
		$txt	= substr($this->getValueTxt(), 0, 45); 

		if (!is_null ($this->_screen) and ($this->isEnabled()))
			$html = $html . "&nbsp;<a id='{$id3}'><img src='imagenes/lupa.gif' alt='Mostrar Lista' width='16' height='16'></a>";

		if ($this->isEnabled())	
		$html = $html . "&nbsp;<a id='{$id4}'><img src='imagenes/delete.gif' alt='Limpiar Valor' width='16' height='16'></a>";

		$error = '';

		if (! $this->isValid()) {
			$error = $this->getError(); 
		}

		$html = $html . "<b id='{$id}_error' class='error'>" . $error . "</b>";
		$html = $html . "<label style='padding-left: 2em;' id='{$id}_txt'>$txt</label>";

		$html = $html . "</td></tr>";
		return $html; 
	}

	public function getJavaScript()
	{
		$javascript = '';  

		$screen= $this->getScreen();

		if (!is_null($screen))
		{
			$id  = $this->getId();
			$id2 = $id . "_txt";
			$id3 = $id . "_btn1";
			$id4 = $id . "_btn2";
			$url1 = "query.php?screen=$screen&id1=$id&id2=$id2";

			$javascript = $javascript . "					
				Event.observe ('{$id3}', 'click', function() {
					saveValuesAndGoto('$url1');
					return false; 
				}); \n"; 
		}

		$tmp = ''; 
		foreach ($this->inputs->getAll() as $i )
		{	
			$campo = $i->getCampo(); 
			$tmp = $tmp . "\$('{$campo}').value = '';\n"; 
		}

		$javascript = $javascript . "
			Event.observe ('{$id4}', 'click', 
								function() 
								{
									{$tmp} ;
									$('{$this->getId()}_txt').update('');
									$('{$this->getId()}_error').update('');
									return false;	
								}); \n";

		$id = $this->getId();
		$url = Utils::makeAbsoluteUrl("abm/mbox_query.php");

		$tmp = array(); 
		foreach ($this->inputs->getAll() as $i)
		{
			$campo = $i->getCampo(); 
			$tmp[] = "{$campo}: \$('{$campo}').value "; 
		}
		$parameters = implode(',', $tmp);

		
		$updates = '';
		foreach($this->inputs->getAll() as $n => $i)
		{
			$campo = $i->getCampo();
			$orden = $n + 2; 	// Dado que javascript no tiene arreglos asociativos debo asignar los valores por orden. Como los  
								// 2 primeros elementos del array son para el valueTxt y para el Error sumo 2 al orden preestablecido
			$updates = $updates . "\$('$campo').value = _txt[$orden];";
		}

		$javascript = $javascript . " 
		function {$id}__update_txt()
		{
			new Ajax.Request('$url', {
					parameters:
					{
						_ctrl: '{$id}', 
						$parameters
					},
					onSuccess: function (transport) {
							var _txt = eval (transport.responseText);
							$('{$id}_txt').update(_txt[0]);
							$('{$id}_error').update(_txt[1]);
							$updates
						}
				});
		} \n";

		foreach ($this->inputs->getAll() as $i)
		{
			$campo = $i->getCampo(); 
			$javascript = $javascript . "Event.observe ('{$campo}', 'blur', function () { {$id}__update_txt () } ); \n";
			$javascript = $javascript . "Event.observe ('{$campo}', 'change', function () { {$id}__update_txt () } ); \n";
		}

		return $javascript; 
	}

	public function executeDynamicQuery($filtros = null, $params)
	{

		if (strpos($this->getSql(), '#CAMPOS#'))
		{
			$campos = array();
			foreach ($this->inputs->getAll() as $i)
			{
				$campos[] = $i->getCampo();
			}

			$sql = str_replace('#CAMPOS#', implode(', ', $campos), $this->getSql());
		}

		if ($filtros == '')
		{
			$filtros = "1 = 1"; 
		}
		
		
		if (!strpos($this->getSql(), '#FILTROS#'))
		{
			$sql = $sql . ' WHERE ' . $filtros; 
		} 
		else
		{
			$sql = str_replace('#FILTROS#', $filtros, $sql);
		}

		return Database::query($sql, $params) ;

	}

	public function setValue($value)
	{
		if (is_array($value))
		{
			return $this->setValueTxtFromComposition($value);
		}
		else 
		{
			$rs = $this->queryFromId($value);

			if (!$rs) 
			{
				$this->setValueTxt('');
				$this->setError('Error'); 
				return false;
			}

			$row = $rs->getRow(); 

			foreach ($this->inputs->getAll() as $i)
			{
				$i->setValue($row[$i->getCampo()]);
			}

			return $this->setValueTxtFromComposition($this->getValueAsArray());
		}
	}

	public function queryFromId($id)
	{
		$filtros = $this->getId() . ' = ?';
		$params = array ($id); 

		return $this->executeDynamicQuery ($filtros, $params);
	}

	public function queryFromComposition($arr)
	{
		$filtro = array () ; 
		$params = array () ; 

		$n = 0 ; 

		foreach ($this->inputs->getAll() as $i)
		{
			$campo = $i->getCampo();
			$valor = $arr[$campo]; 

			$i->setValue($valor);

			if ($i->validar()) 
			{
				$filtro[] = $campo . ' = $' . ($n+1) . ' ';
				$params[] = $valor;

				$n += 1; 
			}
		}

		$filtros = implode (' AND ', $filtro );

		$rs = $this->executeDynamicQuery ($filtros, $params);

		if (!$rs) return false;

		return $rs; 
	}

	/**
	 * setea el valueTxt buscando por el sql un registro que matchee según los parámetros dados. 
	 * Si no encuentra registros, o encuentra mas de 1 setea un código de error y blanquea el valueTxt
	 * @param array $arr
	 */
	public function setValueTxtFromComposition($arr) 
	{
		$rs = $this->queryFromComposition($arr);

		if (!$rs) 
		{
			$this->setValueTxt('');
			$this->setError('Error'); 
			return false;
		}

		if ($rs->getRowCount() == 0)
		{
			 $this->setValueTxt (''); 
			 $this->setError('Inválido');

			 return false;
		}
		else if ($rs->getRowCount() == 1)
		{ 
			$row = $rs->getRowWithIndexes();

			$this->setValueTxt($row[1]);
			$this->setError('');

			foreach ($this->inputs->getAll() as $i)
			{
				$i->setValue($row[$i->getCampo()]);
			}
			
			return true;
		}
		else if ($rs->getRowCount() > 1)
		{
			 $this->setValueTxt(''); 
			 $this->setError('Inválido');

			 return false;
		}
	}

	/**
	 * devuelve un array codificado con JSON con los valores que reciba el javascript para completar el valueTxt en pantalla 
	 * y los campos que correspondan. Este método se llama de mbox_query.php
	 * @param array $arr
	 */
	public function getValueAjax($arr)
	{
		$this->setValue($arr);

		$ajax_values[] = $this->getValueTxt();
		$ajax_values[] = $this->getError();

		foreach ($this->inputs->getAll() as $i)
		{
			$ajax_values[] = $i->getValue();
		}

		return json_encode($ajax_values);
	}

	public function parseArray($arr)
	{	
		foreach ($this->inputs->getAll() as $i)
		{
			$i->setValue(strlen(trim($i->getCampo())) > 0 ? $arr[$i->getCampo()] : null);			
		}

		$this->setValueTxtFromComposition($arr);
	}	

	public function parseRequest()
	{
		$this->parseArray($_REQUEST);
	}

	public function parseRow($row)
	{
		$this->parseArray($row);
	}

	public function validar()
	{
		$ok = parent::validar(); 

		$ok = $ok && $this->inputs->validar();

		if ($this->isObligatorio())
			$ok = $ok && $this->setValueTxtFromComposition($this->getValueAsArray());

		return $ok;  
	}

	public function fillParameter(ParamStmt $stmt)
	{
		$this->inputs->fillParameter($stmt);
	}	

	public function init()
	{
		$this->inputs->init();
		parent::init();
	}

	public function refrescar($inputs)
	{
		if (!is_null($this->getScreen()))

		$this->inputs->refrescar();
		parent::refrescar($inputs);
	}

	public function setPersistent($persistent = true)
	{
		foreach ($this->inputs->getAll() as $i) 
		{
			$i->setPersistent($persistent);
		}
	}
	
	public function setEnabled($enabled)
	{
		foreach ($this->inputs->getAll() as $i) 
		{
			$i->setEnabled($enabled);
		}
				
		parent::setEnabled($enabled);
	}

	public function getValueAsArray()
	{
		$arr =  array (); 
		foreach ($this->inputs->getAll() as $i) 
		{
			$arr[$i->getCampo()] = $i->getValue();
		}
		return $arr;
	}

	public function isChanged()
	{	return true;	}

	public function setSql($sql) 
	{ 	$this->_sql = $sql;	}

	public function getSql() 
	{	return $this->_sql; }

	public function setScreen($name)
	{	$this->_screen = $name;	}

	public function getScreen()
	{	return $this->_screen; }
}
