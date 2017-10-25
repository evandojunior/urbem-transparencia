<div class="relatorio">
<table width="100%" cellspacing="0" cellpadding="0">
<thead>
	<tr>
		<td width="100px">CPF / CNPJ</td>
		<td width="475px">Credor</td>
		<td width="115px" align="right">Empenhado (R$)</td>
		<td width="100px" align="right">Liquidado (R$)</td>
		<td width="95px" align="right">Pago (R$)</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalEmpenhado = $totalLiquidado = $totalPago = 0;

	foreach ($despesas as $despesa) {
		if (isset($despesa->numero_nivel_conta)) {
			$padding = $despesa->numero_nivel_conta*5;
			$strong = ($despesa->numero_nivel_conta == 1) ? 'font-weight:bold;' : '';
		}
?>
	<tr>
		<td align="left"><?php echo formatCPFCNPJ($despesa->cnpj_cpf_credor);?></td>
		<td align="left"><?php echo utf8_encode($despesa->descricao);?></td>
		<td align="right"><?php echo number_format($despesa->valor_empenhado, 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa->valor_liquidado, 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa->valor_pago     , 2, ',', '.');?></td>
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
		<td>&nbsp;</td>
		<td align="right" colspan="1"><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($totalEmpenhado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalLiquidado , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalPago      , 2, ',', '.');?></b></td>
	</tr>
</tbody>	
</table>
</div>