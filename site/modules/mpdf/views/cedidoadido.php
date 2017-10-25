<div class="relatorio">
<table width="100%" cellspacing="1">
	<thead>
		<tr id="principal">
			<td width="300px">Nome</td>
			<td width="50px">Situação</td>
			<td width="100px">Ato CedÃªncia</td>
			<td width="90px">Data Inicial</td>
			<td width="100px">Tipo CedÃªncia</td>
			<td width="320px">Orgão Cedente / CessionÃ¡rio</td>
		</tr>
	</thead>
	<tbody>
	<?php foreach ($cedidosadidos as $cedidoadido) { ?>
		<tr id="principal">
			<td><?php echo utf8_encode($cedidoadido->nom_cgm); ?></a></td>
			<td><?php echo utf8_encode($cedidoadido->situacao); ?></td>
			<td><?php echo $cedidoadido->ato_cedencia;?></td>
			<td><?php echo formatDateToPHP($cedidoadido->dt_inicial);?></td>
			<td><?php echo $cedidoadido->tipo_cedencia;?></td>
			<td><?php echo $cedidoadido->orgao_cedente_cessionario;?></td>
		</tr>
	<?php } ?>
	</tbody>	
</table>
</div>