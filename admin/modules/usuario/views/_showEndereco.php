<script type="application/javascript">
$(document).ready(function(){
	<?php if($closeFrame){ ?>
		parent.window.location.reload();
		parent.$.prettyPhoto.close();
	<?php } ?>

	$("input#edit").click(function(){
        window.location = "<?php echo url('usuarioEndereco/'.$_REQUEST['usuarioId'].'/edit/'.$endereco->getId()); ?>";
    });

	$("input#delete").click(function(){
		if(confirm("Deseja realmente deletar este endereço?")){
			$.get("<?php echo url('usuarioEndereco/'.$_REQUEST['usuarioId'].'/delete/'.$endereco->getId()) ?>", function(){
				parent.window.location.reload();
				parent.$.prettyPhoto.close();
			});
		}
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<div class="show">
<span class="campo">ID:</span><span class="valor"><?php echo $endereco->getId(); ?></span>
<span class="campo">Logradouro:</span> <span class="valor"><?php echo $endereco->getCEP()->getLogradouro(); ?>, n° <?php echo $endereco->getNumero(); ?></span>
<span class="campo">Complemento:</span> <span class="valor"><?php echo $endereco->getComplemento(); ?></span>
<span class="campo">CEP:</span> <span class="valor"><?php echo $endereco->getCEP()->getNumeroCEP(); ?></span>
<span class="campo">Bairro:</span> <span class="valor"><?php echo $endereco->getCEP()->getBairro(); ?></span>
<span class="campo">Municipio:</span> <span class="valor"><?php echo $endereco->getCEP()->getMunicipio()->getNome(); ?>/<?php echo $endereco->getCEP()->getMunicipio()->getUF()->getSigla(); ?></span>
<span class="campo">Tipo:</span> <span class="valor"><?php echo $endereco->getTipo(); ?></span>

<div class="buttons">
<?php if(allowShow('usuario_endereco.update')){ ?>
	<label class="button"><input type="button" name="edit" id="edit" value="Editar" /></label>
<?php } if(allowShow('usuario_endereco.delete')){ ?>	
	<label class="button"><input type="button" name="delete" id="delete" value="Deletar" /></label>
<?php } ?>
</div>

</div>
