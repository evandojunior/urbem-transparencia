<script type="application/javascript">
$(document).ready(function(){
	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formnews" id="formnews" method="POST" action="<?php echo url('grupo/update/'.$grupo->getId()); ?>">

<?php echo $form->getFields('id')->render(); ?>
<div class="form-element"><label class="form-label"><?php echo $form->getFields('grupo')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('grupo')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('grupo')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('alias')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('alias')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('alias')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('acao')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('acao')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('acao')->render(); ?></div>
</div>

<?php Load::snippet('buttons', array('acao' => 'edit')); ?>

</form>
