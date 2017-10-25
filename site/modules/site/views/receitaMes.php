<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisa', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
<thead>
	<tr id="principal">
		<td width="580px">Descrição da Conta</td>
		<td width="80px" align="right">Orçado</td>
		<td width="150px" align="right">Realizado (<?php echo getMeses($mes) ?>)</td>
		<td width="80px" align="right">Realizado</td>
		<td width="80px" align="right">Percentual</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalRealizado = 0;
	$i = 0;
	foreach ($receitas as $receita) {
		$receitaMes = 'receita_'.$mes;
		$padding    = $receita['numero_nivel']*5;
		$percent    = ($receita['receita_orcada'] > 0) ? ($receita['receita_realizado'] / $receita['receita_orcada']) * 100 : 0;
		$strong     = ($receita['tipo_nivel'] == 'S')  ? 'font-weight:bold;' : '';
?>
	<tr style="<?php echo $strong;?>" id="principal">
		<td width="560px" style="padding-left: <?php echo $padding;?>px"><?php echo $receita['cod_conta']." - ".$receita['especificacao_conta'];?></td>
		<td width="100px" align="right"><?php echo number_format($receita['receita_orcada'], 2, ',', '.');?></td>
		<td width="100px" align="right"><?php echo number_format($receita[$receitaMes], 2, ',', '.');?></td>
		<td width="100px" align="right"><?php echo number_format($receita['receita_realizado'], 2, ',', '.');?></td>
		<td width="100px" align="right"><?php echo number_format($percent , 2, ',', '.');?> %</td>
	</tr>
<?php
	} 

	$totalOrcado       = $total['receita_orcada'];
	$totalRealizado    = $total['receita_realizado'];
	$totalRealizadoMes = $total['receita_realizado_mes'];
?>
	<tr>
		<td align="right"><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($totalOrcado, 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalRealizadoMes, 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalRealizado, 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo ($totalRealizado > 0) ? number_format(($totalOrcado/$totalRealizado)*100, 2, ',', '.') : 0;?> %</b></td>
	</tr>
</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
