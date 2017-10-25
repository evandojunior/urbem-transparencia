<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisa', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
<thead>
	<tr>
		<td width="400px">Descrição da Conta</td>
		<td width="130px" align="right">Dotação Inicial (R$)</td>
		<td width="110px" align="right">Empenhado (R$)</td>
		<td width="110px" align="right">Liquidado (R$)</td>
		<td width="90px" align="right">Pago (R$)</td>
		<td width="80px" align="center">Empenhos</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalEmpenhado = $totalLiquidado = $totalPago = 0;
    $padding = '';
    $strong = '';
	foreach ($despesas as $despesa) {
		if (isset($despesa->numero_nivel_conta)) {
			$padding = $despesa->numero_nivel_conta*5;
			$strong = ($despesa->numero_nivel_conta == 1) ? 'font-weight:bold;' : '';
		}
?>
	<tr>
		<td style="padding-left: <?php echo $padding;?>px; <?php echo $strong;?>" ><?php echo $despesa['descricao'];?></td>
		<td align="right"><?php echo number_format($despesa['dotacao_inicial'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_empenhado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_liquidado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_pago']     , 2, ',', '.');?></td>
		<td align="center" class="empenho"><a href="<?php echo url('despesa/'.$_REQUEST['secao'].'/empenho/'.$despesa->cod); ?>" class="empenho" title="Detalhes">&nbsp;</a></td>
	</tr>
<?php
    } 

	foreach ($totais as $total) {
		$totalOrcado    += $total->dotacao_inicial;
		$totalEmpenhado += $total->valor_empenhado;
		$totalLiquidado += $total->valor_liquidado;
		$totalPago      += $total->valor_pago;
	}
?>
	<tr>
		<td align="right"><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($totalOrcado    , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalEmpenhado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalLiquidado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalPago      , 2, ',', '.');?></b></td>
		<td align="center">&nbsp;</td>
	</tr>
</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
