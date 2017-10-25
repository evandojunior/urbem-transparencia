<?php Load::snippet('header_reduzido_municipio'); ?>

<div class="lista_dados">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="300px">Nome</td>
			<td width="50px">Situação</td>
			<td width="100px">Ato Cedência</td>
			<td width="90px">Data Inicial</td>
			<td width="100px">Tipo Cedência</td>
			<td width="320px">Orgão Cedente / Cessionário</td>
		</tr>
	</thead>
	<tbody>
	<?php
	
	$i=0;
	foreach ($cedidosadidos as $cedidoadido) { ?>
		<tr id="principal">
			<td><a href="#" class="more" title="more" rel="row_<?php echo $i ?>"><img src="<?php echo url("templates/default/img/read_more.png?20170801")?>" width="15"/>&nbsp;<?php echo $cedidoadido->nom_cgm;?></a></td>
			<td><?php echo $cedidoadido->situacao;?></td>
			<td><?php echo $cedidoadido->ato_cedencia;?></td>
			<td><?php echo formatDateToPHP($cedidoadido->dt_inicial);?></td>
			<td><?php echo $cedidoadido->tipo_cedencia;?></td>
			<td><?php echo $cedidoadido->orgao_cedente_cessionario;?></td>
		</tr>
		<tr id="row_<?php echo $i ?>" name="detail" style="display:none; border: 0px;">
			<td colspan="6">
				<ul class="detail">
					<li><b>DATA FINAL:</b> <?php echo formatDateToPHP($cedidoadido->dt_final); ?></li>
					<li><b>ÔNUS PARA PAGTO:</b> <?php echo $cedidoadido->indicativo_onus; ?></li>
					<li><b>CONVENIO:</b> <?php echo $cedidoadido->num_convenio; ?></li>
					<li><b>LOCAL:</b> <?php echo $cedidoadido->local; ?></li>
				</ul>
			</td>
		</tr>
	<?php $i++; } ?>
	</tbody>	
</table>
</div>

<?php Load::snippet('pager', $snippet); ?>
