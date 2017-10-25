<script type="application/javascript">
$(document).ready(function(){
<?php
	printJsFilter(array(
					'id'	    => 'th#id', 
					'categoria' => 'th#categoria',
					'alias'     => 'th#alias', 
					'parent'    => 'th#parent', 
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
			<th width="150px" id="categoria">Categoria</th>
			<th width="250px" id="alias">Alias</th>
			<th width="250px" id="parent">Parent</th>
			<th width="130px" id="created">Criado em</th>
			<th width="130px" id="updated">Editado em</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($categorias) == 0){
		echo '<tr><td colspan="6" style="font-size:14px;" align="center">A pesquisa não retornou nenhum resultado</td></tr>';
	} else {
		foreach($categorias as $categoria){
			?>
		<tr>
			<td class="id"><?php echo $_categoria->getId(); ?></td>
			<td><?php echo $categoria->getCategoria(); ?></td>
			<td><?php echo $categoria->getAlias(); ?></td>
			<td><?php echo $categoria->getParent()->getCategoria(); ?></td>
			<td><?php echo $categoria->getCreated(); ?></td>
			<td><?php echo $categoria->getUpdated(); ?></td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
