<?php Plugin::load('jmask'); ?>

<script type="application/javascript">
$(document).ready(function(){
	$("input:text").setMask();

   	$("input#cancel").click(function(){
		window.location = '<?php echo $form->cancel(); ?>';
	});

	$("a#editStatus").click(function(){
		if(confirm("Deseja realmente alterar o status deste usuário?")){
			window.location = "<?php echo url('usuario/editStatus/'.$usuario->getPessoaId()); ?>";
		}
	});		
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formusuario" id="formusuario" method="POST" action="<?php echo url('usuario/update/'.$usuario->getPessoaId()); ?>">

<?php echo $form->getFields('id')->render(); ?>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('grupo_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('grupo_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('grupo_id')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('municipio_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('municipio_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('municipio_id')->render(); ?></div>
</div>

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

<div class="form-element"><label class="form-label"><?php echo $form->getFields('status')->getLabel(); ?></label>
<div class="form-input-text"><?php echo $form->getFields('status')->render(); ?></div>
<div>
    <?php if(allowShow('usuario.editStatus')){ ?> 
	<a class="big" id="editStatus" href="#">Clique aqui para alterar status do usuário</a>
    <?php }?>
</div>
</div>

<?php Load::snippet('buttons', array('acao' => 'edit')); ?>

</form>
