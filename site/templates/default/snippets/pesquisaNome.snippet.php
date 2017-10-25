<script type="application/javascript">
$(document).ready(function(){
	$("form#pesquisa").submit(function(){
		var nome      = $("input#id_nome").val();
		var exercicio = $("select#id_exercicio").val();
		mensagem 	  = "";
		
		url = "?exercicio="+exercicio;
		
		if (nome != ""){
			url+= "&nome="+nome;
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
	<div class="form-error"><?php echo $formPesquisa->getFields('exercicio')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('exercicio')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="submitFormPesquisa">
		<label class="button"><input type="submit" name="pesquisa" id="pesquisa" value="pesquisa"/></label></div>
	</div>
	</div>
</form>