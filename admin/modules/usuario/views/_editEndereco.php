<?php Plugin::load('jmask'); ?>

<script type="application/javascript">
$(document).ready(function(){
    $("input:text").setMask();

	<?php if($closeFrame){ ?>
		parent.window.location.reload();
		parent.$.prettyPhoto.close();
	<?php } ?>

	$("input#cancel").click(function(){
		parent.$.prettyPhoto.close();
	});
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="formusuarioendereco" id="formusuarioendereco" method="POST" action="<?php echo url('usuarioEndereco/'.$_REQUEST['usuarioId'].'/update/'.$_REQUEST['id']); ?>">

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('id')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('id')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('id')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('uf')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('uf')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('uf')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('municipio')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('municipio')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('municipio')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('bairro')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('bairro')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('bairro')->render(); ?></div>
</div>

<div class="form-multi-element">
<div class="form-multi-element-1"><label class="form-label"><?php echo $formEndereco->getFields('logradouro')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('logradouro')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('logradouro')->render(); ?></div>
</div>

<div class="form-multi-element-2"><label class="form-label"><?php echo $formEndereco->getFields('numero')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('numero')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('numero')->render(); ?></div>
</div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('complemento')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('complemento')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('complemento')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('tipo')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('tipo')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('tipo')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $formEndereco->getFields('cep')->getLabel(); ?></label>
<div class="form-error"><?php echo $formEndereco->getFields('cep')->getError(); ?></div>
<div class="form-input-text"><?php echo $formEndereco->getFields('cep')->render(); ?></div>
</div>

<?php Load::snippet('buttons'); ?>

</form>
