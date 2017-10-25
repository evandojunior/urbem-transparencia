<script type="application/javascript">
$(document).ready(function(){
	$("form#pesquisa").submit(function(){
		var competencia = $("select#id_competencia").val();
		mensagem 	    = "";
		url				= "";
		
		if (competencia != ""){
			url+= "?competencia="+competencia;
		}
		
		window.location  = url;
		return false;
	});
});	
</script>

<form name="pesquisa" id="pesquisa" method="get" action="">
	<div class="form-element-pesquisa">
	<div class="form-error"><?php echo $formPesquisa->getFields('competencia')->getError(); ?></div>
	<div class="form-input-text"><?php echo $formPesquisa->getFields('competencia')->render(); ?></div>
	</div>
	
	<div class="form-element-pesquisa">
	<div class="submitFormPesquisa">
		<label class="button"><input type="submit" name="pesquisa" id="pesquisa" value="pesquisa"/></label>
	</div>
	</div>
</form>