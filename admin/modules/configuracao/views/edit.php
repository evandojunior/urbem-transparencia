<script type="application/javascript">
$(document).ready(function(){
	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formconfiguracao" id="formconfiguracao" method="POST" action="<?php echo url('configuracao/update/'.$configuracao->getId()); ?>">

<?php echo $form->getFields('id')->render(); ?>
<div class="form-element"><label class="form-label"><?php echo $form->getFields('municipio_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('municipio_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('municipio_id')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('modulo_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('modulo_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('modulo_id')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('parametro')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('parametro')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('parametro')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('alias')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('alias')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('alias')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('valor')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('valor')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('valor')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('descricao')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('descricao')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('descricao')->render(); ?></div>
</div>

<?php Load::snippet('buttons', array('acao' => 'edit')); ?>

</form>
