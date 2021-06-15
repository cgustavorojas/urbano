<?php
/**
 * @package abm
 */

/**
 * Clase especializada de Input que muestra un editor HTML usando la librería TinyMCE.
 * 
 * Limitación actual: sólo puede haber 1 input de este tipo por pantalla (ver funcion getJavascript()). 
 * 
 * @package abm 
 */
class InputRichText extends Input
{
	private $width; 
	private $height; 
	private $wrap = false;

	/**
	 * 
	 * @param string $txt
	 * @param string $campo
	 * @param integer $width
	 * @param integer $height
	 * @return InputRichText
	 */
	public function __construct($txt, $campo = NULL, $width = 600, $height = 400)
	{
		$this->setTxt($txt);
		$this->setCampo(is_null($campo) ? Utils::toLowerNoBlanks($txt) : $campo);

		$this->setWidth($width);
		$this->setHeight($height); 
		
		$this->setSelectable();
	}
		
	
	/**
	 * Implementación que tiene en cuenta un par de casos particulares. 
	 * En el caso de que el seteo magic_quotes_gpc esté en On en el servidor, 
	 * el escapeo de comillas puede arruinar el HTML generado por TinyMCE. 
	 */
	public function parseValue($valueAsString)
	{
		if (get_magic_quotes_gpc()) {
			$valueAsString = stripslashes($valueAsString);
		}	
		
		$this->setValue($valueAsString); 
	}
	
	public function getHtml()
	{
		if ($this->isHidden())
			return ''; 
			
		$txt      = $this->isObligatorio() ? $this->getTxt() . '*' : $this->getTxt(); 
		$cssClass = $this->isObligatorio() ? "label obligatorio" : "label";
		
		$html = "<tr><td class='$cssClass'>$txt</td><td class='input'>";

		$id   = $this->getId();
		$value = $this->getValue();
		$disabled  = $this->isEnabled() ? '' : 'disabled';
		$wrap   = $this->wrap ? 'on' : 'off';
		
		$help = '';
		
		if($this->isHelp())
		{
			$help = $this->getHtmlHelp();
		}
		
		$html = $html . "<textarea wrap='$wrap' id='$id' name='$id'$disabled class='abm-textarea'>$value</textarea> $help";
		
		if (! $this->isValid()) {
			$error = $this->getError();
			$html = $html . "<b class='error'>" . $error . "</b>"; 
		}
		
		$html = $html . "</td></tr>";
		return $html; 
	}
	

	/**
	 * Envía el código javascript de la librería TinyMCE. 
	 * La rutina getJavascript() de la clase Input espera directamente el código javascript (sin los tags script), por lo 
	 * que hace una tramoya (arranca cerrando el tag script) para poder abrir uno nuevo.
	 * Limitación: tal cual está escrito, si hubiera más de un input de este tipo en la misma pantalla, se incluiría más de una
	 * vez la librería completa TinyMCE. Hasta tanto esto no se resuelva, es mejor limitarse a un único input por pantalla.  
	 */
	public function getJavascript()
	{
		return '
		</script>
		<script language="javascript" type="text/javascript" src="/lib/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
		<script language="javascript" type="text/javascript">
		  tinyMCE.init({
		    theme : "advanced",
		    mode: "exact",
		    elements : "' . $this->getId() . '",
		    theme_advanced_toolbar_location : "top",
		    theme_advanced_toolbar_align : "left",
		    theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,bullist,numlist,outdent,indent",
		    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,link,unlink,|,undo,redo,cleanup,code,separator,sub,sup,charmap,preview,|,forecolor,backcolor",
		    theme_advanced_buttons3 : "",
		    height:"' . $this->getHeight() . 'px",
		    width:"' . $this->getWidth() . 'px"
		});
		';
				
	}
	
			    
		    
//        plugins : "spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
//
//        // Theme options
//        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
//        theme_advanced_buttons2 : ",|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
//        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
//        theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
//        theme_advanced_toolbar_location : "top",
//        theme_advanced_toolbar_align : "left",
//        theme_advanced_statusbar_location : "bottom",
//        theme_advanced_resizing : true,
//
//        // Skin options
//        skin : "o2k7",
//        skin_variant : "silver",
//
//        // Example content CSS (should be your site CSS)
//        content_css : "css/example.css",
//
//        // Drop lists for link/image/media/template dialogs
//        template_external_list_url : "js/template_list.js",
//        external_link_list_url : "js/link_list.js",
//        external_image_list_url : "js/image_list.js",
//        media_external_list_url : "js/media_list.js",
//		    
	
	//---- getters && setters ----//

	public function getWidth() {
		return $this->width; 
	}
	public function setWidth($width) {
		$this->width = $width;
	}
	public function getHeight() {
		return $this->height; 
	}
	public function setHeight($height) {
		$this->height = $height; 
	}
	
}