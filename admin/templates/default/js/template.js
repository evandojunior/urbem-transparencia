$(document).ready(function(){
	
	var BASE_URL = "http://172.16.30.181/transparencia/admin";
	
	$("div.form-error").each(function(){							
		if($(this).html() != ""){
			$(this).fadeIn("slow");		
		}
	});	
	
	$("table.lista tbody tr:even").attr({"class" : "even"});
	$("table.lista tbody tr:odd").attr({"class" : "odd"});
    
    $("select#selecionarPagina").change(function(){
    	var q = $("input#id_q").val();
    	var filter = $("select#id_filter").val();
    	var order = "id";
    	var direction = "asc";
    	var page = $(this).val();
    	
    	window.location = "?q="+q+"&filter="+filter+"&order="+order+"&direction="+direction+"&page="+page;
    });
    
	$("form#pesquisa").submit(function(){
		var q = $("input#id_q").val();
		var filter = $("select#id_filter").val();
		window.location = "?q="+q+"&filter="+filter;
		
		return false;
	});
    
	$("table.lista tbody tr").mouseover(function(){
		css_class = $(this).attr('class');
		$(this).attr({'class': 'mousehover_tr'});
	}).mouseout(function(){
		$(this).attr({'class': css_class});
	});	  
	
	$("table#lista tbody tr").click(function(){
		module = $("input#module").val();
		if($("input#module_alias").val()){
			module = $("input#module_alias").val();
		}
		
		if($("td.id", this).html() > 0){
			window.location = "/transparencia/admin/"+module+"/"+$("td.id", this).html();
		}
	});	  
	
	$("table#_lista tbody tr").click(function(){
		module = $(this).parent().parent().parent().find("input#module-lista").val();
		
		if($("td.id", this).html() > 0){
			window.location = "/transparencia/admin/"+module+"/"+$("td.id", this).html();
		}
	});
	
    $("#id_uf").change(function(){
        preencheComboDinamico("select#id_uf", "select#id_municipio", BASE_URL+"/endereco/municipio/", "Selecione");
    });
	
    $("#id_categoria").change(function(){
        preencheComboDinamico("select#id_categoria", "select#id_subcategoria", BASE_URL+"/produto/subcategoria/", "Selecione");
    });
    
	$("#menu ul.menu li.menu").hover(function(){	
  			$(this).find("ul.submenu").show();
		}, 
		function() {
			$("ul.submenu").hide('fast');
	});

	if($("div.buttons").html() == ""){
		$("div.buttons").hide();
	}
	
});

function preencheComboDinamico(idPai, idFilho, url, defaultMessage){
    if ($(idPai).val() != '') {
        $.ajax({
            type: "GET",
            url: url + $(idPai).val(),
            dataType: "json",
            beforeSend:function(){
            	$(idFilho).children().remove().end().append("<option value=''>Carregando...</option>");
            },
            success: function(data){
            	$(idFilho).children().remove().end().append("<option value=''>"+defaultMessage+"</option>");
                $.each(data, function(i, item){
                        $(idFilho).append("<option value='" + item.id + "'>" + item.nome + "</option>");
                });
            }
        });
    }else{
            $(idFilho).children().remove().end().append("<option value=''>"+defaultMessage+"</option>");
    }
}	

function animateAnchor(el){
    var pos = $(el).position().top;
   	$('html, body').animate({scrollTop: pos}, 'slow');
}

function retiraParenteses(el){
	$(el).each(function(){							
		if($(this).html() == "() "){
			$(this).html("");		
		}
	});	
}
