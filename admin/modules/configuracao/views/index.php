<script type="application/javascript">
$(document).ready(function(){
<?php
	printJsFilter(array(
					'id'	    => 'th#id',
					'municipio' => 'th#municipio',
					'modulo'    => 'th#modulo',
					'alias'     => 'th#alias', 
					'parametro' => 'th#parametro',
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
			<th width="75px" id="id">ID</th>
			<th width="200px" id="municipio">Município</th>
			<th width="100px" id="modulo">Módulo</th>
			<th width="150px" id="alias">Alias</th>
			<th width="200px" id="parametro">Parâmetro</th>
			<th width="130px" id="created">Criado em</th>
			<th width="130px" id="updated">Editado em</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($configuracao) == 0){
		echo '<tr><td colspan="6" style="font-size:14px;" align="center">A pesquisa não retornou nenhum resultado</td></tr>';
	} else {
		foreach($configuracao as $_configuracao){
			?>
		<tr>
			<td class="id"><?php echo $_configuracao->getId(); ?></td>
			<td><?php echo $_configuracao->getMunicipio()->getNome(); ?></td>
			<td><?php echo $_configuracao->getModulo()->getModulo(); ?></td>
			<td><?php echo $_configuracao->getAlias(); ?></td>
			<td><?php echo $_configuracao->getParametro(); ?></td>
			<td><?php echo $_configuracao->getCreated(); ?></td>
			<td><?php echo $_configuracao->getUpdated(); ?></td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>