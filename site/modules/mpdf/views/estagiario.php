<div class="relatorio">
<table width="100%" cellspacing="1">
	<thead>
		<tr>
			<td align="left" width="300px">Nome</td>
			<td align="center" width="100px">InÃ­cio</td>
			<td align="center" width="100px">Data Fim</td>
			<td align="center" width="100px">Renovação</td>
			<td align="left" width="180px">Lotação</td>
			<td align="left" width="180px">Local</td>
		</tr>
	</thead>
	<tbody>
	<?php
	
	foreach ($estagiarios as $estagiario) { ?>
		<tr>
			<td><?php echo utf8_encode($estagiario->nome);?></td>
			<td align="center"><?php echo $estagiario->data_inicio;?></td>
			<td align="center"><?php echo $estagiario->data_fim;?></td>
			<td align="center"><?php echo $estagiario->data_renovacao;?></td>
			<td><?php echo utf8_encode($estagiario->descricao_lotacao); ?></td>
			<td><?php echo utf8_encode($estagiario->descricao_local); ?></td>
		</tr>
	<?php } ?>
	</tbody>	
</table>
</div>