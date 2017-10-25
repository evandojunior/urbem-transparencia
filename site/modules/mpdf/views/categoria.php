<div class="categoria">
<?php
	switch($_REQUEST['nivel']){
		case 'natureza':
			echo '<div class="categoria_1">Categoria:</div> <div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
		break;

		case 'elemento':
			echo '<div class="categoria_1">Categoria:</div> <div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
			echo '<div class="categoria_1">Natureza:</div>  <div class="categoria_2">'.$rubricaNatureza['especificacao_rubrica_despesa'].'</div>';
		break;
	}
?>	
</div>

<div class="relatorio">
<table width="100%" cellspacing="1">
<thead>
	<tr>
		<?php
			switch($_REQUEST['nivel']){
				case 'categoria':
					echo '<td width="400px">Categoria</td>';
				break;
	
				case 'natureza':
					echo '<td width="400px">Natureza</td>';
				break;
			    
				case 'elemento':
					echo '<td width="400px">Elemento</td>';
				break;			
			}
		?>
		<td width="130px" align="right">Dotação Inicial (R$)</td>
		<td width="110px" align="right">Empenhado (R$)</td>
		<td width="110px" align="right">Liquidado (R$)</td>
		<td width="90px" align="right">Pago (R$)</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalEmpenhado = $totalLiquidado = $totalPago = 0;

	foreach ($despesas as $despesa) {
?>
	<tr>
		<td align="left"><?php echo utf8_encode($despesa['descricao']);?></td>
		<td align="right"><?php echo number_format($despesa['dotacao_inicial'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_empenhado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_liquidado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_pago']     , 2, ',', '.');?></td>
	</tr>
<?php
    } 
?>
	<tr>
		<td align="right"><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($total['dotacao_inicial'] , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($total['valor_empenhado'] , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($total['valor_liquidado'] , 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($total['valor_pago']      , 2, ',', '.');?></b></td>
	</tr>
</tbody>	
</table>
</div>