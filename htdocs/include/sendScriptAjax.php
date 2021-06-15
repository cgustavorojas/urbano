<?php
	function sendScriptAjax($action,$target,$formName,$funcionRefresco='') {
	
			?>
			<script language='javascript'>	
			function enviarFormularioAjax<?php echo $formName; ?>(funcionRefresco) {
			
				var $valor = Form.serialize(document.getElementById('<?php echo $formName; ?>'));
				
				envioAjax('<?php echo $action ?>','<?php echo $target; ?>', $valor , "post" ,funcionRefresco);
				
				//envioAjax('<?php echo $action ?>','<?php echo '_SELF' ?>', $valor , "<?php echo $action ?>" );
				
				// document.getElementById('<?php echo $formName; ?>').submit();				
				
			}
			</script>
			<?php
	
	}
?>