<div class="relatorio">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="280px">Nome</td>
			<td width="80px" align="center">Admissão</td>
			<td width="200px">Função/Espec.</td>
			<td width="60px" align="center">C.H. MÃªs</td>
			<td width="150px">Padrão</td>
			<td width="80px">Situação</td>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ($servidores as $servidor) {
			$especialidade = trim($servidor['descricao_especialidade_funcao']);
			$funcaoEspecialidade = !empty($especialidade) ? $servidor['descricao_funcao']." - ".$especialidade : $servidor['descricao_funcao'];
	?>
		<tr id="principal">
			<td align="left"><?php echo utf8_encode($servidor['nome']); ?></td>
			<td align="center"><?php echo formatDateToPHP($servidor['dt_admissao']);?></td>
			<td align="left"><?php echo utf8_encode($funcaoEspecialidade);?></td>
			<td align="center"><?php echo $servidor['horas_mensais'] ;?></td>
			<td align="left"><?php echo utf8_encode($servidor['descricao_padrao']); ?></td>
			<td align="left"><?php echo utf8_encode($servidor['situacao']); ?></td>
		</tr>
	<?php $i++; } ?>
	</tbody>	
</table>
</div>