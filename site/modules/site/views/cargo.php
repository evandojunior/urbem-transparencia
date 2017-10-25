<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisaCompetencia', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
	<thead>
		<tr>
			<td width="15px" align="center">Código</td>
			<td width="120px">Descrição</td>
			<td width="100px">Tipo</td>
			<td width="100px">Regime de Trabalho</td>
			<td width="65px" align="center">Fund. Legal</td>
			<td width="100px">Padrão - Valor (R$)</td>
			<td width="30px" align="center">Vigência</td>
			<td width="15px" align="center">Cria.</td>
			<td width="15px" align="center">Ocup.</td>
			<td width="15px" align="center">Disp.</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($cargos as $cargo) { ?>
		<tr>
			<td align="center"><?php echo $cargo->codigo;?></td>
			<td><?php echo $cargo->descricao_cargo;?></td>
			<td><?php echo $cargo->tipo_cargo;?></td>
			<td><?php echo $cargo->regime_subdivisao;?></td>
			<td align="center"><?php echo $cargo->lei;?></td>
			<td><?php echo $cargo->descricao_padrao." - ".number_format($cargo->valor, 2, ',', '.');?></td>
			<td align="center"><?php echo $cargo->vigencia;?></td>
			<td align="center"><?php echo $cargo->vagas_criadas;?></td>
			<td align="center"><?php echo $cargo->vagas_ocupadas;?></td>
			<td align="center"><?php echo $cargo->vagas_disponiveis;?></td>
		</tr>
	<?php } ?>
	</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
