<?php
	Plugin::load('tinymce', 'textarea#id_detalhamento');
	Load::snippet('header', $snippet);
?>

<form name="form" id="form" method="POST" action="<?php echo url('publicacao/update/'.$publicacao->getId()); ?>" enctype="multipart/form-data">

<?php echo $form->getFields('id')->render(); ?>
<div class="form-element"><label class="form-label"><?php echo $form->getFields('usuario')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('usuario')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('usuario')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('secao_id')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('secao_id')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('secao_id')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('descricao')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('descricao')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('descricao')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('detalhamento')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('detalhamento')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('detalhamento')->render(); ?></div>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('arquivo')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('arquivo')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('arquivo')->render(); ?></div>
</div>

<div class="publicacao_arquivo">
	<a href="<?php echo url('../media/img/publicacao/'.$publicacao->getArquivo()); ?>" target="_blank" id="publicacao_arquivo">
	<img src="<?php echo url('templates/default/img/boxdownload32.png'); ?>" alt="Download">
	Visualizar arquivo</a>
</div>

<div class="form-element"><label class="form-label"><?php echo $form->getFields('status')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('status')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('status')->render(); ?></div>
</div>

<?php Load::snippet('buttons', array('acao' => 'new')); ?>

</form>
