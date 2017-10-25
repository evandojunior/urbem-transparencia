<script type="application/javascript">
$(document).ready(function(){
    $("input#edit").click(function(){
        window.location = "<?php echo url('configuracao/edit/'.$configuracao->getId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar este parâmetro de configuração?")){
			window.location = "<?php echo url('configuracao/delete/'.$configuracao->getId()); ?>";
		}
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<div class="esquerda">
<span class="campo">ID:</span><span class="valor"><?php echo $configuracao->getId(); ?></span>
<span class="campo">Município:</span><span class="valor"><?php echo $configuracao->getMunicipio()->getNome(); ?></span>
<span class="campo">Módulo:</span><span class="valor"><?php echo $configuracao->getModulo()->getModulo(); ?></span>
<span class="campo">Parâmetro:</span><span class="valor"><?php echo $configuracao->getParametro(); ?></span>
<span class="campo">Alias:</span><span class="valor"><?php echo $configuracao->getAlias(); ?></span>
<span class="campo">Valor:</span><span class="valor"><?php echo $configuracao->getValor(); ?></span>
<span class="campo">Descrição:</span><span class="valor"><?php echo $configuracao->getDescricao(); ?></span>
<span class="campo">Criado em:</span><span class="valor"><?php echo $configuracao->getCreated(); ?></span>
<span class="campo">Editado em:</span><span class="valor"><?php echo $configuracao->getUpdated(); ?></span>
</div>

<div class="buttons">
<?php if(allowShow('configuracao.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('configuracao.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>
</div>

</div>
