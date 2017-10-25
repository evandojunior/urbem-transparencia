<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisa', $snippet);
?>

<div class="categoria">
<?php
	switch($_REQUEST['nivel']){
		case 'natureza':
			echo '<div class="categoria_1">Categoria:</div> <div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
		break;

		case 'elemento':
			echo '<div class="categoria_1">Categoria:</div><div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
			echo '<div class="categoria_1">Natureza:</div><div class="categoria_2">'.$rubricaNatureza['especificacao_rubrica_despesa'].'</div>';
		break;
	}
?>	
</div>

<div class="lista_dados">
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
		<td width="80px" align="center">Detalhes</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalEmpenhado = $totalLiquidado = $totalPago = 0;

	foreach ($despesas as $despesa) {
?>
	<tr>
		<td align="left"><?php echo $despesa['descricao'];?></td>
		<td align="right"><?php echo number_format($despesa['dotacao_inicial'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_empenhado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_liquidado'], 2, ',', '.');?></td>
		<td align="right"><?php echo number_format($despesa['valor_pago']     , 2, ',', '.');?></td>
		<?php
			switch($_REQUEST['nivel']){
				case 'categoria':
					echo '<td align="center" class="empenho"><a href="'.url('despesa/'.$_REQUEST['secao'].'/'.$despesa['cod_categoria'].'/natureza'.getQueryStringPadrao()).'" class="empenho" title="Detalhes">&nbsp;</a></td>';
				break;
	
				case 'natureza':
					echo '<td align="center" class="empenho"><a href="'.url('despesa/'.$_REQUEST['secao'].'/'.$despesa['cod_categoria'].'/natureza/'.$despesa['cod_natureza'].'/elemento'.getQueryStringPadrao()).'" class="empenho" title="Detalhes">&nbsp;</a></td>';
				break;
			    
				case 'elemento':
					echo '<td align="center" class="empenho"><a href="'.url('despesa/'.$_REQUEST['secao'].'/'.$despesa['cod_categoria'].'/natureza/'.$despesa['cod_natureza'].'/elemento/'.$despesa['cod_categoria'].$despesa['cod_natureza'].$despesa['cod_elemento'].'/empenho'.getQueryStringPadrao()).'" class="empenho" title="Detalhes">&nbsp;</a></td>';
				break;			
			}
		?>
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
		<td align="center">&nbsp;</td>
	</tr>
</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
