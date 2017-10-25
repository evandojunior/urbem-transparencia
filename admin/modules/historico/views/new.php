<script type="application/javascript">
$(document).ready(function(){
	<?php if($closeFrame){ ?>
		parent.window.location.reload();
		parent.$.prettyPhoto.close();
	<?php } ?>
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formhistorico" id="formhistorico" method="POST" action="<?php echo url('historico/create/'.$_REQUEST['moduleHistorico'].'/'.$_REQUEST['entidade_id']); ?>">

<div class="form-element"><label class="form-label"><?php echo $form->getFields('usuario')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('usuario')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('usuario')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('descricao')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('descricao')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('descricao')->render(); ?></div>
</div>

<div class="buttons">
	<label class="button"><input type="submit" name="save" id="submit" value="Salvar" /></label> 
</div>
</form>
