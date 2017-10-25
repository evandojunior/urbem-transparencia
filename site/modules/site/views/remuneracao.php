<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisaCompetencia', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="200px" align="left">Nome</td>
			<td width="30px"  align="center">Mês / Ano</td>
			<td width="30px"  align="center">Matrícula</td>
			<td width="50px"  align="right">Remuneração Bruta (R$)</td>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 0;
		foreach ($remuneracoes as $remuneracao) {
	?>
		<tr id="principal">
			<td><a href="#" class="more" title="more" rel="row_<?php echo $i ?>"><img src="<?php echo url("templates/default/img/read_more.png?20170801")?>" width="15" />&nbsp;<?php echo $remuneracao['nome'];?></a></td>
			<td align="center"><?php echo $remuneracao['mes_ano'];?></td>
			<td align="center"><?php echo $remuneracao['matricula'];?></td>
			<td align="right"><?php echo number_format($remuneracao['remuneracao_bruta'], 2, ',', '.');?></td>
		</tr>
		<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px;">
			<td colspan="4" style="padding: 0px; background-color: #FFF;">
				<table cellspacing="1" width="100%">
					<tr><td align="right" style="background:#E4E4E4;" width="180px"><b>Remuneração Teto:			   </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['remuneracao_teto'];?>			    </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Remuneração Eventual Natalina:</b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['remuneracao_eventual_natalina'];?></td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Remuneração Eventual Ferias:  </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['remuneracao_eventual_ferias']; ?> </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Remuneração Eventual Outras:  </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['remuneracao_eventual_outras']; ?> </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Deduções Obrigatorias Irrf:   </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['deducoes_obrigatorias_irrf']; ?>  </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Deduções Obrigatorias Prev:   </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['deducoes_obrigatorias_prev']; ?>  </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Demais Deducoes:			   </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['demais_deducoes']; ?>             </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Remuneração Após Deducoes:    </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['remuneracao_apos_deducoes']; ?>   </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Verbas Salário Família:       </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['verbas_salario_familia']; ?>      </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Verbas Jetons:                </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['verbas_jetons']; ?>               </td>
					<tr><td align="right" style="background:#E4E4E4;"><b>Demais Verbas:                </b></td><td style="background:#F4F4F4;"><?php echo $remuneracao['demais_verbas']; ?>               </td>
				</table>
			</td>
		</tr>		
	<?php $i++; } ?>
	</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>