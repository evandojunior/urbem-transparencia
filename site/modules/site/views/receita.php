<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisa', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
<thead>
	<tr id="principal">
		<td width="660px">Descrição da Conta</td>
		<td width="100px" align="right">Orçado</td>
		<td width="100px" align="right">Realizado</td>
		<td width="100px" align="right">Percentual</td>
	</tr>
</thead>
<tbody>
<?php
	$totalOrcado = $totalRealizado = 0;
	$i = 0;
	
	foreach ($receitas as $receita) {
		$padding = $receita['numero_nivel']*5;
		$percent = ($receita['receita_orcada'] > 0) ? ($receita['receita_realizado'] / $receita['receita_orcada']) * 100 : 0;
		$strong  = ($receita['tipo_nivel'] == 'S')  ? 'font-weight:bold;' : '';
?>
	<tr style="<?php echo $strong;?>" id="principal">
		<td width="660px" style="padding-left: <?php echo $padding;?>px"><a href="#" class="more" title="more" rel="row_<?php echo $i ?>"><img src="<?php echo url("templates/default/img/read_more.png?20170801")?>" width="15"><?php echo $receita['cod_conta']." - ".$receita['especificacao_conta'];?></a></td>
		<td width="100px" align="right"><?php echo number_format($receita['receita_orcada']    , 2, ',', '.');?></td>
		<td width="100px" align="right"><?php echo number_format($receita['receita_realizado'] , 2, ',', '.');?></td>
		<td width="100px" align="right"><?php echo number_format($percent , 2, ',', '.');?> %</td>
	</tr>
	<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px;">
		<td colspan="4" style="padding: 0px; background-color: #FFF;">
			<div class="tab-pane tabs-1-0 ui-tabs-panel ui-widget-content ui-corner-bottom">
			<table cellspacing="1" width="100%">
				<tr><td align="right" width="733px"><b>Janeiro:  </b></td><td><?php echo number_format($receita['receita_janeiro'], 2, ',', '.'); ?>   </td></tr>
				<tr><td align="right"><b>Fevereiro:</b></td><td><?php echo number_format($receita['receita_fevereiro'], 2, ',', '.'); ?> </td></tr>
				<tr><td align="right"><b>Março:    </b></td><td><?php echo number_format($receita['receita_marco'], 2, ',', '.'); ?>     </td></tr>
				<tr><td align="right"><b>Abril:    </b></td><td><?php echo number_format($receita['receita_abril'], 2, ',', '.'); ?>     </td></tr>
				<tr><td align="right"><b>Maio:     </b></td><td><?php echo number_format($receita['receita_maio'], 2, ',', '.'); ?> 	    </td></tr>
				<tr><td align="right"><b>Junho:    </b></td><td><?php echo number_format($receita['receita_junho'], 2, ',', '.'); ?>	    </td></tr>
				<tr><td align="right"><b>Julho:    </b></td><td><?php echo number_format($receita['receita_julho'], 2, ',', '.'); ?>	    </td></tr>
				<tr><td align="right"><b>Agosto:   </b></td><td><?php echo number_format($receita['receita_agosto'], 2, ',', '.'); ?>    </td></tr>
				<tr><td align="right"><b>Setembro: </b></td><td><?php echo number_format($receita['receita_setembro'], 2, ',', '.'); ?>  </td></tr>
				<tr><td align="right"><b>Outubro:  </b></td><td><?php echo number_format($receita['receita_outubro'], 2, ',', '.'); ?>   </td></tr>
				<tr><td align="right"><b>Novembro: </b></td><td><?php echo number_format($receita['receita_novembro'], 2, ',', '.'); ?>  </td></tr>
				<tr><td align="right"><b>Dezembro: </b></td><td><?php echo number_format($receita['receita_dezembro'], 2, ',', '.'); ?>  </td></tr>
			</table>
			</div>
		</td>                    
	</tr>
<?php
		$i++;
	} 

	$totalOrcado    = $total['receita_orcada'];
	$totalRealizado = $total['receita_realizado'];
?>
	<tr>
		<td align="right"><b>Total Geral</b></td>
		<td align="right"><b><?php echo number_format($totalOrcado, 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo number_format($totalRealizado, 2, ',', '.');?></b></td>
		<td align="right"><b><?php echo ($totalRealizado > 0) ? number_format(($totalOrcado/$totalRealizado)*100, 2, ',', '.') : 0;?> %</b></td>
	</tr>
</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>