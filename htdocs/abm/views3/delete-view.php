<?php
/**
 * @package views
 * @author MA AMD
 */
 
sendHeader($screen->getTitulo()); ?>

<style>
</style>


<h2><?php echo $screen->getTitulo(); ?></h2>

<form name='abm-form' id='abm-form' method='POST' action='delete.php?cmd=save'>
	<input type="hidden" name="screenid" value="<?php echo $screen->getId(); ?>">


<?php if ($screen->isConfirmable() || ! $screen->isValid()) : ?>
	<div class="alert alert-success" role="alert"">
	<?php echo $screen->getMsgConfirmar(); ?>
	</div>
	
	<br>
	<input class="btn btn-primary btn-xs h btn-aceptar" type="submit" value="Aceptar">&nbsp; 
	<input class="btn btn-secondary btn-xs h btn-cancelar" type="submit" value="Cancelar" onclick="$('abm-form').action='delete.php?cmd=close';">
	
<?php endif; ?>
	
<?php if (! $screen->isConfirmable() && $screen->isValid()) : ?>
	Eliminando registro ... 
	<script type="text/javascript">
		$('abm-form').submit();
	</script>
<?php endif; ?>
		
</form>

<?php if (!$screen->isValid()) :?>
	     <!-- <div class="alert-danger ">-->
           <?php if (strlen($screen->getError())>0) :?>
                <?php //echo strlen($screen->getError())."----"; ?>
              <!-- </div> -->
           <?php echo '<script>'. MSG_STYLE.' toastr.error("'.$screen->getError().'") </script>';?>
         <?php endif; ?> 	
<?php endif; ?>

<?php sendFooter(); ?>


