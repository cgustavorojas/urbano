<?php
/**
 * Pantalla de login general al sistema. Utiliza un template particular. 
 * 
 * Incluída desde ../login.php
 * 
 * @package gral
 */
?>

<?php $t = new Template('login'); ?>

<?php $t->sendHeader();   
						
				
?>

			
		<form id="formLogin" name="login" method="post" action="login.php">
			<input type="hidden" name="cmd" value="login">
			<table>
				<tr>
					<td class="leyenda">Usuario</td>
					<td class="input"><input class="itext" id="usuario" name="usuario" type="text"></td>
				</tr>
				<tr>
					<td class="leyenda">Contraseña</td>
					<td class="input"><input class="itext" id="password" name="passwd" type="password"></td>
				</tr>
				<tr>
					<td colspan="2" align="right">
						<input class="boton" type="submit" value="Iniciar Sesión">
					</td>
				</tr>				
			</table>	
		</form>
	
		<?php if (isset($error)) : ?>
			<div id="error">
				<?php echo $error; ?>
			</div>
		<?php endif; ?>
	

		<script type="text/javascript">
		  document.getElementById('usuario').focus();
		</script>

<?php $t->sendFooter(); ?>
