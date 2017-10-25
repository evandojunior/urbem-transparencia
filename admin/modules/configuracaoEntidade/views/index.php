<script type="application/javascript">
$(document).ready(function(){
	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formconfigurarEntidade" id="formconfigurarEntidade" method="POST" action="<?php echo url('configuracaoEntidade/update'); ?>">

<div class="form-element">
<div class="form-error"><?php echo $form->getFields('entidade_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('entidade_id')->render(); ?></div>
</div>

<?php Load::snippet('buttons', array('acao' => 'edit')); ?>
</form>