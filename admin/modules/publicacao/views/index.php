<script type="application/javascript">
$(document).ready(function(){
<?php
	printJsFilter(array(
					'id'	    => 'th#id', 
					'secao_id'  => 'th#secao_id',
					'descricao' => 'th#descricao', 
					'usuario'   => 'th#usuario', 
					'created'   => 'th#created', 
					'updated'   => 'th#updated'
				));
?>
});
</script>

<?php Load::snippet('header', $snippet); ?>

<form name="pesquisa" id="pesquisa" method="get" action="">
<div class="form-pesquisa">
<div class="form-element-pesquisa"><label class="form-label"><?php echo $form->getFields('q')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('q')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('q')->render(); ?></div>
</div>

<div class="form-element-pesquisa"><label class="form-label"><?php echo $form->getFields('filter')->getLabel(); ?></label>
<div class="form-error"><?php echo $form->getFields('filter')->getError(); ?></div>
<div class="form-input-text"><?php echo $form->getFields('filter')->render(); ?></div>
</div>

<div class="form-element-pesquisa">
<div class="submitFormPesquisa">
	<label class="button"><input type="submit" name="search" class="search" value="Pesquisar" /></label></div>
</div>
</div>
</form>

<div class="lista">
<table class="lista" id="lista" cellpadding="1" cellspacing="1" border="0">
	<thead>
		<tr>
			<th width="50px" id="id">ID</th>
			<th width="125px" id="secao_id">Seção</th>
			<th width="325px" id="descricao">Descrição</th>
			<th width="125px" id="usuario">Usuário</th>
			<th width="100px" id="status">Status</th>
			<th width="130px" id="created">Criado em</th>
			<th width="130px" id="updated">Editado em</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($publicacoes) == 0){
		echo '<tr><td colspan="7" style="font-size:14px;" align="center">A pesquisa não retornou nenhum resultado</td></tr>';
	} else {
		foreach($publicacoes as $publicacao){
			?>
		<tr>
			<td class="id"><?php echo $publicacao->getId(); ?></td>
			<td><?php echo $publicacao->getSecao()->getSecao(); ?></td>
			<td><?php echo $publicacao->getDescricao(); ?></td>
			<td><?php echo $publicacao->getUsuario(); ?></td>
			<td><?php echo $publicacao->getStatus() == 'p' ? 'Publicado':'Não Publicado'; ?></td>
			<td><?php echo $publicacao->getCreated(); ?></td>
			<td><?php echo $publicacao->getUpdated(); ?></td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
