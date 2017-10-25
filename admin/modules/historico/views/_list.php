<br /><br />
<script type="application/javascript">
$(document).ready(function(){
	$("input#inserirPosicao").click(function(){
		var id = $("input#id").val();
		var module_historico = $("input#module").val();
		$.prettyPhoto.open("<?php echo $GLOBALS['BASE_URL'] ?>/admin/historico/new/"+module_historico+"/"+id+"?iframe=true&width=500&height=350");
	});	
});
</script>

<?php Load::snippet('header', $snippet); ?>

<input type="hidden" id="moduleLista" value="historico" />

<div class="lista">
<table class="lista" id="listaHistorico" cellpadding="1" cellspacing="1" border="0">
	<thead>
		<tr>
			<th width="75px"  id="id">ID</th>
			<th width="200px" id="usuario_nome">Usuário</th>
			<th width="575px" id="descricao">Descrição</th>
			<th width="130px" id="created_at">Criado em</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if(count($historicos) == 0){
		echo '<tr><td colspan="4" style="font-size:14px;" align="center">Não existem posições lançadas no histórico</td></tr>';
	} else {
		foreach($historicos as $historico){
	?>
		<tr>
			<td class="id"><?php echo $historico->getId(); ?></td>
			<td><?php echo $historico->getPessoa()->getNome(); ?></td>
			<td><?php echo $historico->getDescricao(); ?></td>
			<td><?php echo $historico->getCreated(); ?></td>
		</tr>
		<?php
		}
	}
	?>
	</tbody>
</table>
</div>

<div class="buttons">
<label class="button"><input type="button" id="inserirPosicao" value="Inserir posição" /></label>
</div>
