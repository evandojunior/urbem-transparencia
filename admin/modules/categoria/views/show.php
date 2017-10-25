<script type="application/javascript">
$(document).ready(function(){
    $("input#edit").click(function(){
        window.location = "<?php echo url('categoria/edit/'.$categoria->getId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar esta categoria?")){
			window.location = "<?php echo url('categoria/delete/'.$categoria->getId()); ?>";
		}
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<div class="esquerda">
<span class="campo">ID:</span><span class="valor"><?php echo $categoria->getId(); ?></span>
<span class="campo">Categoria:</span><span class="valor"><?php echo $categoria->getCategoria(); ?></span>
<span class="campo">Alias:</span><span class="valor"><?php echo $categoria->getAlias(); ?></span>
<span class="campo">Parent:</span><span class="valor"><?php echo $categoria->getParent()->getCategoria(); ?></span>
<span class="campo">Criado em:</span><span class="valor"><?php echo $categoria->getCreated(); ?></span>
<span class="campo">Editado em:</span><span class="valor"><?php echo $categoria->getUpdated(); ?></span>
</div>

<div class="buttons">
<?php if(allowShow('categoria.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('categoria.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>
</div>

</div>
