<?php
	Load::snippet('header_reduzido_municipio');
	Load::snippet('pesquisaServidor', $snippet);
?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="280px">Nome</td>
			<td width="80px" align="center">Admissão</td>
			<td width="200px">Função/Espec.</td>
			<td width="60px" align="center">C.H. Mês</td>
			<td width="150px">Padrão</td>
			<td width="80px">Situação</td>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 0;
		foreach ($servidores as $servidor) {
			$especialidade = trim($servidor['descricao_especialidade_funcao']);
			$funcaoEspecialidade = !empty($especialidade) ? $servidor['descricao_funcao']." - ".$especialidade : $servidor['descricao_funcao'];
	?>
		<tr id="principal">
			<td align="left"><a href="#" class="more" title="more" rel="row_<?php echo $i ?>"><img src="<?php echo url("templates/default/img/read_more.png?20170801")?>" width="15"/>&nbsp;<?php echo $servidor['nome'];?></a></td>
			<td align="center"><?php echo formatDateToPHP($servidor['dt_admissao']);?></td>
			<td align="left"><?php echo $funcaoEspecialidade;?></td>
			<td align="center"><?php echo $servidor['horas_mensais'];?></td>
			<td align="left"><?php echo $servidor['descricao_padrao'];?></td>
			<td align="left"><?php echo $servidor['situacao'];?></td>
		</tr>
		<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px;">
			<td colspan="6" style="padding: 0px; background-color: #FFF;">
				<table cellspacing="1" width="100%">
					<tr><td align="right" width="150px" style="background:#E4E4E4;"><b>Rescisão/Exoneração:</b></td><td style="background:#F4F4F4;"><?php echo$servidor['dt_rescisao'];?></td></tr>
					<tr><td align="right" width="150px" style="background:#E4E4E4;"><b>Regime/Sub.Div. Trabalho:</b></td><td style="background:#F4F4F4;"><?php echo$servidor['descricao_regime_subdivisao_funcao'];?></td></tr>
					<tr><td align="right" width="150px" style="background:#E4E4E4;"><b>Lotação:</b></td><td style="background:#F4F4F4;"><?php echo$servidor['descricao_lotacao'];?></td></tr>
					<tr><td align="right" width="150px" style="background:#E4E4E4;"><b>Local Trabalho:</b></td><td style="background:#F4F4F4;"><?php echo$servidor['descricao_local'];?></td></tr>
				</table>
			</td>
		</tr>		
	<?php $i++; } ?>
	</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
