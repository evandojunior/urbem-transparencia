<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisa', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
<thead>
	<tr>
		<td width="50px" align="center">Código</td>
		<td width="140px">Modalidade</td>
		<td width="100px">Tipo</td>
		<td width="550px">Objeto</td>
		<td width="65px" align="center">Empenhos</td>
	</tr>
</thead>
<tbody>
	
<?php foreach ($registros as $registro) { ?>
	<tr>
		<td align="center"><?php echo $registro['cod']; ?></td>
		<td><?php echo $registro['modalidade']; ?></td>
		<td><?php echo $tipo;?></td>
		<td><?php echo formatReadMore($registro['descricao_objeto'], 75);?></td>
		<td align="center" class="empenho"><a href="<?php echo url('compra_licitacao/'.$_REQUEST['secao'].'/empenho/'.$registro['cod_entidade'].','.$registro['exercicio_entidade'].','.$registro['cod'].','.trim($registro['modalidade'])); ?>" class="empenho" title="Detalhes">&nbsp;</a></td>
	</tr>
<?php } ?>

</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
