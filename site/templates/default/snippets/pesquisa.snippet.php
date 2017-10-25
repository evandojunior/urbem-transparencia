<script type="application/javascript">
$(document).ready(function(){
	$("form#pesquisa").submit(function(){
		var cod_entidade = $("select#id_cod_entidade").val();
		var exercicio    = $("select#id_exercicio").val();
		var mes    		 = $("select#id_mes").val();
		mensagem 		 = "";
		
		url = "?exercicio="+exercicio;
		
		if (mes != undefined){
			url+="&mes="+mes;
		}
		
		if (cod_entidade != ""){
			url+= "&cod_entidade="+cod_entidade;
		}
		
		window.location  = url;
		return false;
	});
});	
</script>

<form name="pesquisa" id="pesquisa" method="get" action="">
	<div class="form-pesquisa">
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('cod_entidade')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('cod_entidade')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('exercicio')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('exercicio')->render(); ?></div>
	</div>

	<?php if($formPesquisa->getFields('mes') != null){ ?>
	
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('mes')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('mes')->render(); ?></div>
	</div>
	
	<?php } ?>
	
	<div class="form-element-pesquisa">
	<div class="submitFormPesquisa">
		<label class="button"><input type="submit" name="pesquisa" id="pesquisa" value="pesquisa"/></label></div>
	</div>
	</div>
</form>