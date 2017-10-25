<script type="application/javascript">
$(document).ready(function(){
    $("input#edit").click(function(){
        window.location = "<?php echo url('usuario/edit/'.$usuario->getPessoaId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar este usuário?")){
			window.location = "<?php echo url('usuario/delete/'.$usuario->getPessoaId()); ?>";
		}
	});
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<div class="esquerda">
    <span class="campo">ID:</span> <span class="valor"><?php echo $usuario->getPessoaId(); ?></span>
    <span class="campo">Nome:</span> <span class="valor"><?php echo $usuario->getPessoa()->getNome(); ?></span>
    <span class="campo">E-mail:</span><span class="valor"><?php echo $usuario->getPessoa()->getEmail(); ?></span>
    <span class="campo">Grupo:</span><span class="valor"><?php echo $usuario->getGrupo()->getGrupo(); ?></span>
	<span class="campo">Município:</span><span class="valor"><?php echo $usuario->getMunicipio()->getNome(); ?></span>
    <span class="campo">Status:</span><span class="valor"><?php echo $usuario->getStatus() == 1 ? "Ativo" : "Inativo"; ?></span>
</div>

<div class="direita">
	<div class="menuDireita">
		<ul class="menuDireita">
			<li></li>
		</ul>
	</div>
</div>


<div class="buttons">
<?php if(allowShow('usuario.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('usuario.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>	
</div>

<?php Load::module('historico', '_list'); ?>
</div>
