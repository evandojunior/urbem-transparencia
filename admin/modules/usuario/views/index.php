<script type="application/javascript">
$(document).ready(function(){
<?php
	printJsFilter(array(
					'id'     => 'th#id', 
					'nome'   => 'th#nome', 
					'status' => 'th#status',
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
			<th width="75px"  id="id">ID</th>
			<th width="250px" id="nome">Nome</th>
			<th width="250px" id="email">E-mail</th>
			<th width="100px" id="grupo">Grupo</th>
			<th width="150px" id="municipio"><b>Município</b></th>
			<th width="75px"  id="status">Status</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($usuarios) == 0){
		echo '<tr><td colspan="6" style="font-size:14px;" align="center">A pesquisa não retornou nenhum resultado</td></tr>';
	} else {
		foreach($usuarios as $usuario){
	?>
		<tr>
			<td class="id"><?php echo $usuario->getPessoa()->getId(); ?></td>
			<td><?php echo $usuario->getPessoa()->getNome(); ?></td>
			<td><?php echo $usuario->getPessoa()->getEmail(); ?></td>
			<td><?php echo $usuario->getGrupo()->getGrupo(); ?></td>
			<td><?php echo $usuario->getMunicipio()->getNome(); ?></td>
			<td><?php echo $usuario->getStatus() ? 'Ativo' : 'Inativo'; ?></td>
		</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
