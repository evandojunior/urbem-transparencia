<script type="application/javascript">
$(document).ready(function(){
	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formcategoria" id="formcategoria" method="POST" action="<?php echo url('categoria/create'); ?>">

<div class="form-element"><label class="form-label"><?php echo $form->getFields('categoria')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('categoria')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('categoria')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('alias')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('alias')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('alias')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('parent_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('parent_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('parent_id')->render(); ?></div>
</div>

<?php Load::snippet('buttons', array('acao' => 'new')); ?>

</form>
