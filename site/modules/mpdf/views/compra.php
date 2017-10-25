<div class="relatorio">
<table width="100%" cellspacing="1">
<thead>
	<tr>
		<td width="50px" align="center">CÃ³digo</td>
		<td width="140px">Modalidade</td>
		<td width="100px">Tipo</td>
		<td width="550px">Objeto</td>
	</tr>
</thead>
<tbody>
	
<?php foreach ($registros as $registro) { ?>
	<tr>
		<td align="center"><?php echo $registro['cod']; ?></td>
		<td><?php echo utf8_encode($registro['modalidade']); ?></td>
		<td><?php echo $tipo;?></td>
		<td><?php echo utf8_encode($registro['descricao_objeto']);?></td>
	</tr>
<?php } ?>

</tbody>	
</table>
</div>