$(document).ready(function(){
	
	$("div.messages ul").each(function(){							
		if($(this).html() != ""){
			$(this).parent().fadeIn("slow");	
		}
	});
	
	$("div.form-error").each(function(){							
		if($(this).html() != ""){
			$(this).css("display", "table");		
		}
	});
	
	// Utilizado em tabelas gerais
	$("div.lista_dados table tr:even, div.lista_publicacao table tr:even").attr({"class" : "even"});
	$("div.lista_dados table tr:odd, div.lista_publicacao table tr:odd").attr({"class" : "odd"});
	
	// Utilizado em tabelas com '+' detalhes
	$("div.lista_dados table tr#principal:even").attr({"class" : "even"});
	$("div.lista_dados table tr#principal:odd").attr({"class" : "odd"});
	
	// Zebrado para o detail da tabela
	$("div.lista_dados table tr[name=detail]:even").attr({"class" : "odd"});
	$("div.lista_dados table tr[name=detail]:odd").attr({"class" : "even"});
	
	//$("table#receita tr td").attr({"class" : ""});
	
	$("div.lista_dados table tbody tr, div.lista_publicacao table tbody tr").mouseover(function(){
		css_class = $(this).attr('class');
		// $(this).attr({'class': 'mousehover_tr'});
	}).mouseout(function(){
		// $(this).attr({'class': css_class});
	});
	
	$("a.more").click(function(e){
		if($(this).attr("title") == "more"){
			var src = $(this).find("img").attr("src").replace("more","less");
			$(this).find("img").attr("src", src);
			$(this).attr("title", "less");

		} else {
			var src = $(this).find("img").attr("src").replace("less","more");
			$(this).find("img").attr("src", src);
			$(this).attr("title", "more");
		}
		
		// utilizado para abrir as divs e spans
		$(this).siblings("div.hide, span.hide").toggle();
		
		// utilizado para abrir as linhas escondidas nas tabelas
		var rel = $(this).attr("rel");
		if (rel != '') {
			$("#"+rel).toggle();
			console.log($("#"+rel));
		}

		e.preventDefault();
	});
	
    /* $("select#selecionarPagina").change(function(){
    	var q = $("input#id_q").val();
    	var filter = $("select#id_filter").val();
    	var order = "id";
    	var direction = "asc";
    	var page = $(this).val();
    	
		if(window.location.href.indexOf("?") >= 0){
			url = window.location.href.split("&page");
			window.location = url[0]+"&page="+page;
		} else {
			url = window.location.href.split("&page");
			window.location = url[0]+"?page="+page;
		}
    }); */
	
    $("div#paginacao a").click(function(){
    	var q = $("input#id_q").val();
    	var filter = $("select#id_filter").val();
    	var order = "id";
    	var direction = "asc";
    	var page = $(this).attr("title");
		
		if(window.location.href.indexOf("?") >= 0){
			url = window.location.href.split("&page");
			window.location = url[0]+"&page="+page;
		} else {
			url = window.location.href.split("&page");
			window.location = url[0]+"?page="+page;
		}
		
		return false;
    });	
});

Shadowbox.init({
    overlayOpacity: 0.7,
    initialHeight: 500,
    initialWidth: 500,
});

/*** FUNÇÕES ***/

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

function labelInField(id, texto){
	$(id).blur(function(){
		if($(this).val() == ""){
			$(this).val(texto);
		}
	});	
	
	$(id).focus(function(){
		if($(this).val() == texto){
			$(this).val("");
		}
	});	
}