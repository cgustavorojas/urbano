<?php
class InputSuggest extends Input
{
	private $minChars = 2; 
	private $sql = null;
	private $params = null;
	
	public function __construct($txt, $campo, $sql, $tipo = Tipo::NUMBER)
	{
		$this->setCampo ($campo); 
		$this->setTxt ($txt);
		$this->setTipo ($tipo); 
		$this->setSql($sql);
	}
	
	public function getHtml()
	{
		$id = $this->getId();
		echo "<input type='hidden' name='$id' id='$id' />";
		echo "<input type='text' name='". $id ."_search' id='". $id ."_search' size='' />";
		
	}
	
	public function getJavascript()
	{
		$id = $this->getId();
		$minChars = $this->getMinChars();
		
		$url = Utils::makeAbsoluteUrl("abm/suggest_query.php?_ctrl=$id");
		echo "
		new Autocomplete('". $id ."_search', 
			{ 
				serviceUrl:'". $url ."',
				minChars: $minChars,
				onSelect: function(value, data) {
					\$('$id').value = data;
				}
			});
		";
	}
	
	public function filtrar($search)
	{
		$ret = array ('query' => $search);  
		$suggestions = $data = array();

		$search = strtolower($search). '%';
		$rs = Database::query($this->getSql(), array($search));
		
		while ($row = $rs->getRowWithIndexes())
		{
			array_push($data, $row[0]);
			array_push($suggestions, $row[1]);
		} 

		$ret['suggestions'] = $suggestions;
		$ret['data']   = $data;  

		return $ret;
	}
	
	/** Setters y getters **/

	public function getSql()
	{ return $this->sql; }

	public function setSql($value)
	{ $this->sql = $value ; }
	
	
	public function getMinChars()
	{ return $this->minChars; }
	
	public function setMinChars($value)
	{ $this->minChars = $value ; }
}