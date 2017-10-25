<?php Plugin::load('jmask'); ?>

<script type="application/javascript">
$(document).ready(function(){
	$('input:text').setMask();

	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="usuarioform" method="POST" action="<?php echo url('updateConta'); ?>">

<div class="esquerda"><?php echo $form->getFields('id')->render(); ?>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('nome')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('nome')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('nome')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('email')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('email')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('email')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formSenha->getFields('senha')->getLabel(); ?></label>
<div class="form-error"><?php echo $formSenha->getFields('senha')->getError(); ?></div>
<div class="form-input-text"><?php echo $formSenha->getFields('senha')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formSenha->getFields('_senha')->getLabel(); ?></label>
<div class="form-error"><?php echo $formSenha->getFields('_senha')->getError(); ?></div>
<div class="form-input-text"><?php echo $formSenha->getFields('_senha')->render(); ?></div>
</div>
</div>

<?php Load::snippet('buttons'); ?>

</form>
