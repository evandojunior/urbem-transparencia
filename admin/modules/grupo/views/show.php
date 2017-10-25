<script type="application/javascript">
$(document).ready(function(){
    $("input#edit").click(function(){
        window.location = "<?php echo url('grupo/edit/'.$grupo->getId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar este contato?")){
			window.location = "<?php echo url('grupo/delete/'.$grupo->getId()); ?>";
		}
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<span class="campo">ID:</span><span class="valor"><?php echo $grupo->getId(); ?></span>
<span class="campo">Grupo:</span><span class="valor"><?php echo $grupo->getGrupo(); ?></span>
<span class="campo">Alias:</span><span class="valor"><?php echo $grupo->getAlias(); ?></span>
<span class="campo">Criado em:</span><span class="valor"><?php echo $grupo->getCreated(); ?></span>
<span class="campo">Editado em:</span><span class="valor"><?php echo $grupo->getUpdated(); ?></span>

<?php Load::snippet('header', array('title' => 'Permissões')); ?>

<ul class="simplelist">
<?php foreach($grupoAcao as $_grupoAcao){ ?>
	<li><?php echo $_grupoAcao->getAcao()->getAlias() ?></li>
<?php } ?>	
</ul>

<div class="buttons">
<?php if(allowShow('grupo.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('grupo.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>
</div>

</div>
