<?php

/*

 */

class FiltroMultiSearch extends Filtro
{
    
    private $lista = array();
    
	public function __construct($txt)
	{
		$this->setTxt($txt);
	    parent::__construct();
		
	}
	
	public function add($campo)
	{
	    $this->lista[] = $campo;
	}
	 
	
	
	public function getHtml()
	{
	    	    
	    $name      = "name='" . $this->getId() . "'";
	    $txt=$this->getTxt();
	    $id        = "id='" . $this->getId() . "'";
	    $value     = "value='" . $this->getValue() . "'";
	    //$placeholder = "placeholder='" . $this->getTxt() . "'";
	    $placeholder = "placeholder=' Ingrese un criterio de búsqueda'";
	   @ $size      = $this->size > 0 ? "size='" . $this->size . "'" : "";
	    $onchange = is_null($this->getOnChange()) ? '' : 'onchange="eventHook(\'' . $this->getOnChange() .'\');"';
	   
	    $html = "<tr><td class='label'>$txt</td><td class='input'>";
	    $html = $html.  "<input class='input_ ' type='text' $placeholder $id $name  $value $onchange>";
	    
	    if (! $this->isValid()) {
	        $error = $this->getError();
	        
	        $html = $html ."<div class='alert alert-danger'><b>" . $error . "</b>";
	    }
	    
	     $html = $html . "</td></tr>";
	    return $html;
	}
	
	
	public function listar()
	{
	    foreach($this->lista as $campo){
	        
	        $campo .=" LIKE '%".$this->getValue()." %' OR ";
	        @$s .=$campo;
	    }
	    
	    $t =substr($s, 0, -3);
	    echo $t;
	}
	

	public function getWhereClause()
	
	{
	    if (! $this->isValid()) {
	        return "";
	    }
	   
	    if (! is_null($this->getValue())) {
	        
	        $v = explode(' ', $this->getValue());
	       
	        foreach($v as $dato){
	            
	                    foreach($this->lista as $campo){
	            
    	            
    	                       $valor ="lower(".$campo .=") LIKE lower('%".$dato."%') OR ";
    	                       @$s .=$valor;
    	                       
    	                   }
    	        
    	                   $t =substr($s, 0, -3);
    	               
    	                  @$where .= "AND (".$t.")";
    	               $s ="";
	        }
	         
	        return  $where;
	        
	     } else {
	           	            
	            return "";
	        }
	   
	    
	
	    
	}

}