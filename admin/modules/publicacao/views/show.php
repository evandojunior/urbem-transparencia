<script type="application/javascript">
$(document).ready(function(){
    $("input#edit").click(function(){
        window.location = "<?php echo url('publicacao/edit/'.$publicacao->getId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar esta publicação?")){
			window.location = "<?php echo url('publicacao/delete/'.$publicacao->getId()); ?>";
		}
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<div class="esquerda">
<span class="campo">ID:</span><span class="valor"><?php echo $publicacao->getId(); ?></span>
<span class="campo">Usuário:</span><span class="valor"><?php echo $publicacao->getUsuario(); ?></span>
<span class="campo">Seção:</span><span class="valor"><?php echo $publicacao->getSecao()->getSecao(); ?></span>
<span class="campo">Descrição:</span><span class="valor"><?php echo $publicacao->getDescricao(); ?></span>

<div class="detalhamento">
	<span class="campo">Detalhamento:</span><span class="valor"><?php echo $publicacao->getDetalhamento(); ?></span>
</div>

<span class="campo">Status:</span><span class="valor"><?php echo $publicacao->getStatus() == 'p'? 'Publicado':'Não Publicado'; ?></span>
<div class="publicacao_arquivo">
	<a href="<?php echo url('../media/img/publicacao/'.$publicacao->getArquivo()); ?>" target="_blank" id="publicacao_arquivo">
	<img src="<?php echo url('templates/default/img/boxdownload32.png'); ?>" alt="Download">
	Visualizar arquivo</a>
</div>
<span class="campo">Criado em:</span><span class="valor"><?php echo $publicacao->getCreated(); ?></span>
<span class="campo">Editado em:</span><span class="valor"><?php echo $publicacao->getUpdated(); ?></span>
</div>

<div class="buttons">
<?php if(allowShow('publicacao.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('publicacao.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>
</div>

</div>
