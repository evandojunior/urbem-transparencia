<?php
	if(isset($_REQUEST['natureza'])) {
		echo '<div class="categoria">';
		    echo '<div class="categoria_1">Categoria:</div> <div class="categoria_2">'.$rubricaCategoria['especificacao_rubrica_despesa'].'</div>';
		    echo '<div class="categoria_1">Natureza:</div>  <div class="categoria_2">'.$rubricaNatureza['especificacao_rubrica_despesa'].'</div>';
		echo '</div>';
		echo '<br />';
	}
?>
<div class="relatorio">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="100px">Empenho</td>
			<td width="90px" align="center">Data</td>
			<td width="300px" align="center">Credor</td>
			<td width="125px" align="right">Empenhado (R$)</td>
			<td width="125px" align="right">Estornado (R$)</td>
			<td width="125px" align="right">Liquidado (R$)</td>
			<td width="125px" align="right">Pago (R$)</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($empenhos as $empenho) {	?>
		<tr id="principal">
			<td><?php echo substr($empenho['numero_empenho'], 6, 7) ?></td>
			<td align="center"><?php echo formatDateToPHP($empenho['data_empenho']); ?></td>
			<td align="left"><?php echo utf8_encode($empenho['Credor']['nome_credor']); ?></td>
			<td align="right"><?php echo number_format($empenho['valor_empenho'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['estornado'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['liquidado'], 2, ',', '.'); ?></td>
			<td align="right"><?php echo number_format($empenho['pago'], 2, ',', '.'); ?></td>
		</tr>
	<?php } ?>
	</tbody>	
</table>
</div>