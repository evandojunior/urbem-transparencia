<div class="relatorio">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="200px" align="left">Nome</td>
			<td width="30px"  align="center">MÃªs / Ano</td>
			<td width="30px"  align="center">MatrÃ­cula</td>
			<td width="50px"  align="right">Remuneração Bruta (R$)</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($remuneracoes as $remuneracao) { ?>
		<tr id="principal">
			<td><?php echo utf8_encode($remuneracao['nome']); ?></a></td>
			<td align="center"><?php echo $remuneracao['mes_ano'];?></td>
			<td align="center"><?php echo $remuneracao['matricula'];?></td>
			<td align="right"><?php echo number_format($remuneracao['remuneracao_bruta'], 2, ',', '.');?></td>
		</tr>
		<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px;">
			<td colspan="4" style="padding: 0px; background-color: #FFF;">
				<table cellspacing="1" width="100%">
					<tr><td align="right" width="180px"><b>Remuneração Teto: </b></td><td><?php echo $remuneracao['remuneracao_teto'];?>			  </td>
					<tr><td align="right"><b>Remuneração Eventual Natalina:  </b></td><td><?php echo $remuneracao['remuneracao_eventual_natalina'];?></td>
					<tr><td align="right"><b>Remuneração Eventual Ferias:    </b></td><td><?php echo $remuneracao['remuneracao_eventual_ferias']; ?> </td>
					<tr><td align="right"><b>Remuneração Eventual Outras:    </b></td><td><?php echo $remuneracao['remuneracao_eventual_outras']; ?> </td>
					<tr><td align="right"><b>DeduçÃµes Obrigatorias Irrf:     </b></td><td><?php echo $remuneracao['deducoes_obrigatorias_irrf']; ?>  </td>
					<tr><td align="right"><b>DeduçÃµes Obrigatorias Prev:     </b></td><td><?php echo $remuneracao['deducoes_obrigatorias_prev']; ?>  </td>
					<tr><td align="right"><b>Demais Deducoes:			     </b></td><td><?php echo $remuneracao['demais_deducoes']; ?>             </td>
					<tr><td align="right"><b>Remuneração ApÃ³s Deducoes:      </b></td><td><?php echo $remuneracao['remuneracao_apos_deducoes']; ?>   </td>
					<tr><td align="right"><b>Verbas SalÃ¡rio FamÃ­lia:         </b></td><td><?php echo $remuneracao['verbas_salario_familia']; ?>      </td>
					<tr><td align="right"><b>Verbas Jetons:                  </b></td><td><?php echo $remuneracao['verbas_jetons']; ?>               </td>
					<tr><td align="right"><b>Demais Verbas:                  </b></td><td><?php echo $remuneracao['demais_verbas']; ?>               </td>
				</table>
			</td>
		</tr>		
	<?php } ?>
	</tbody>	
</table>
</div>