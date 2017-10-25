<div class="logo_index">
	<img src="<?php echo url('templates/default/img/logo.png') ?>" alt="Logo CNM" width="297" height="83"/>
</div>

<p class="lista_titulo">Escolha o estado</p>
<div class="lista">
	<div class="lista_municipio">
		<ul></ul>
	</div>	
	<div class="lista_uf">
		<?php
			$i     = 0;
			$col   = 3;
			$count = ceil($listaUf->count()/$col);
			
			foreach ($listaUf as $uf) {
				if(($i%$count)==0){
					echo '<div class="lista_uf_1">';
				}
				
				if($uf->count > 0){
					echo '<div class="uf"><a href="#" class="uf" alt="'.$uf->getNome().'" id="'.$uf->getId().'">'.$uf->getSigla().'</a></div>'; 
				} else {
					echo '<div class="uf_alpha">'.$uf->getSigla().'</div>'; 
				}
				
				$i++;
				
				if(($i%$count)==0){
					echo '</div>';
				}				
			}
			
		?>
	</div>
	</div>
</div>

<div class="uf_selecionado"></div>
<div class="escolha_outro">
		<a class="escolha_outro" href="#">Escolha outro estado</a>
</div>


<script type="application/javascript">
$(document).ready(function(){
	
	$("a.uf").click(function(){
		var uf_id   = $(this).attr("id");
		var uf_nome = $(this).attr("alt");
		
		$.ajax({
			type: "GET",
			url: "buscaMunicipio/?uf_id="+uf_id,
			dataType: "json",
			success: function(data){
				$("div.lista_uf_1").fadeOut();

				$("div.lista").animate({ height: '-=320px', duration: "fast" }, 
									   { complete: function() {
									   	   $("div.lista_municipio").fadeIn("slow");
									   	   $("div.uf_selecionado").html("Você está em <b>"+uf_nome+"</b>");
									   	   $("div.uf_selecionado").fadeIn("slow");
									   	   $("div.escolha_outro").fadeIn("slow");
				}});

				$("p.lista_titulo").text("Escolha o município");
				
            	for (i = 0; i < data.municipios.length; i++) {
            		if(data.municipios[i].nome != null){
                		$("div.lista_municipio ul").append("<li><a class='' href='"+data.municipios[i].uf_sigla+"/"+data.municipios[i].alias+"/' rel=''>"+data.municipios[i].nome+"</a></li>");
            		}
        		}				
			}
		});			
	});
	
	$("a.escolha_outro").click(function(){
		$("div.lista_municipio").hide();
		
		$("div.lista").animate({ height: '+=320px', duration: "fast" }, 
							   { complete: function() {
								   $("div.lista_uf").show();
								   $("div.lista_uf_1").show();
								   $("div.lista_municipio ul").html("");
								   $("div.uf_selecionado").hide();
								   $("div.uf_selecionado").hide();
								   $("div.escolha_outro").hide();
		}});
		
		$("p.lista_titulo").text("Escolha o estado");
	});
});
</script>