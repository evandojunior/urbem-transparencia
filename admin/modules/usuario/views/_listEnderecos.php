<script type="application/javascript">
$(document).ready(function(){

    $("table#listaEndereco tbody tr").click(function(){
        if($("td.id", this).html() > 0){
            $.prettyPhoto.open("/transparencia/admin/usuarioEndereco/"+$("input#id").val()+"/"+$("td.id", this).html()+"?iframe=true&width=500&height=600");
        }
    });

    $("a#add-endereco").click(function(e){
        $.prettyPhoto.open("/transparencia/admin/usuarioEndereco/"+$("input#id").val()+"/new/?iframe=true&width=500&height=600");
        e.preventDefault();
    });
});
</script>

<?php Load::snippet('header', array('title' => 'Endereços')); ?>

<div class="lista" id="endereco">
<input type="hidden" id="module-lista" value="endereco" />
<table class="lista" id="listaEndereco" cellpadding="1" cellspacing="1" border="0">
	<thead>
		<tr>
			<th width="75px"  id="id">ID</th>
			<th width="350px" id="logradouro">Logradouro</th>
			<th width="200px" id="bairro">Bairro</th>
			<th width="200px" id="municipio">Municipio</th>
			<th width="150px" id="cep">CEP</th>
			<th width="150px" id="tipo">Tipo</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if(count($enderecos) == 0){
			echo '<tr><td colspan="7" style="font-size:14px;" align="center">Este usuário não possui endereços associados ao seu cadastro</td></tr>';
		} else {
			foreach($enderecos as $endereco){
	?>
		<tr>
			<td class="id"><?php echo $endereco->getId(); ?></td>
			<td><?php echo $endereco->getCEP()->getLogradouro().", ".$endereco->getNumero(); $endereco->getComplemento() != "" ? ", ".$endereco->getComplemento() : null; ?></td>
			<td><?php echo $endereco->getCEP()->getBairro(); ?></td>
			<td><?php echo $endereco->getCEP()->getMunicipio()->getNome(); ?></td>
			<td><?php echo $endereco->getCEP()->getNumeroCEP(); ?></td>
			<td><?php echo $endereco->getTipo(); ?></td>
		</tr>
	<?php }} ?>
	</tbody>
</table>
</div>

<div class="buttons">
	<a href="#" class="big" title="Adicionar endereço" id="add-endereco">Adicionar endereço</a>
</div>
