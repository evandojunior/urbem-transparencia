<script type="application/javascript">
$(document).ready(function(){
	$("form#pesquisa").submit(function(){
		var nome        = $("input#id_nome").val();
		var situacao    = $("select#id_situacao").val();
		var competencia = $("select#id_competencia").val();
		mensagem 	    = "";
		
		url = "?a=a";
		
		if (nome != ""){
			url+= "&nome="+nome;
		}
		
		if (situacao != ""){
			url+= "&situacao="+situacao;
		}
		
		if (competencia != ""){
			url+= "&competencia="+competencia;
		}
		
		window.location  = url;
		return false;
	});
});	
</script>

<form name="pesquisa" id="pesquisa" method="get" action="">
	<div class="form-pesquisa">
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('nome')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('nome')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('competencia')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('competencia')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('situacao')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('situacao')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="submitFormPesquisa">
		<label class="button"><input type="submit" name="pesquisa" id="pesquisa" value="pesquisa"/></label></div>
	</div>	
	
	</div>
</form>