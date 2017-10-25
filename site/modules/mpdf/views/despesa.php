<div class="relatorio">
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
	<tr>
		<td width="400px"><b>Descrição da Conta</b></td>
		<td width="130px" align="right"><b>Dotação Inicial (R$)</b></td>
		<td width="110px" align="right"><b>Empenhado (R$)</b></td>
		<td width="110px" align="right"><b>Liquidado (R$)</b></td>
		<td width="90px" align="right"><b>Pago (R$)</b></td>
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
		<td style="padding-left: <?php echo $padding == 0 ? '1': $padding; ?>px; <?php echo $strong;?>" ><?php echo utf8_encode($despesa['descricao']) ;?></td>
		<td align="right"><?php echo number_format($despesa['dotacao_inicial'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_empenhado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_liquidado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_pago']     , 2, ',', '.');?></td>
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
		<td><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($totalOrcado    , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalEmpenhado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalLiquidado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalPago      , 2, ',', '.');?></b></td>
	</tr>
</tbody>	
</table>
</div>