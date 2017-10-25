<?php Load::snippet('header_municipio', $snippet); ?>

<div class="menu_home_esquerda">
    <ul>
        <li id="menu_despesa">Despesas</li>
        <li id="submenu_despesa" class="submenu">
            <div class="submenu">
                <div><a href="<?php echo url('despesa/orgao'); ?>">Despesa por Órgao</a></div>
                <div><a href="<?php echo url('despesa/funcao'); ?>">Despesa por Função/Subfunção</a></div>
                <div><a href="<?php echo url('despesa/programa'); ?>">Despesa por Programa</a></div>
                <div><a href="<?php echo url('despesa/projeto'); ?>">Despesa por Projeto/Atividade</a></div>
                <div><a href="<?php echo url('despesa/categoria'); ?>">Despesa por Categoria</a></div>
                <div><a href="<?php echo url('despesa/recurso'); ?>">Despesa por Recurso</a></div>
                <div><a href="<?php echo url('despesa/credor'); ?>">Despesa por Credor</a></div>
                <div><a href="<?php echo url('publicacao/despesa'); ?>" rel="shadowbox">Publicações</a></div>
            </div>
        </li>
        <li id="menu_receita">Receitas</li>
        <li id="submenu_receita" class="submenu">
            <div class="submenu">
                <div><a href="<?php echo url('receita/conta'); ?>">Receita por Conta</a></div>
                <div><a href="<?php echo url('receita/mes'); ?>">Receita por Mês</a></div>
                <div><a href="<?php echo url('publicacao/receita'); ?>" rel="shadowbox">Publicações</a></div>
            </div>
        </li>
        <li id="menu_compra_licitacao">Compras e Licitações</li>
        <li id="submenu_compra_licitacao" class="submenu">
            <div class="submenu">
                <div><a href="<?php echo url('compra_licitacao/compra'); ?>">Compras</a></div>
                <div><a href="<?php echo url('compra_licitacao/licitacao'); ?>">Licitações</a></div>
                <div><a href="<?php echo url('publicacao/compra_licitacao'); ?>" rel="shadowbox">Publicações</a></div>
            </div>
        </li>        

        <li id="menu_quadro_funcional">Quadro Funcional</li>
        <li id="submenu_quadro_funcional" class="submenu">
            <div class="submenu">
				<div><a href="<?php echo url('quadro_funcional/cargo'); ?>">Cargos</a></div>
				<div><a href="<?php echo url('quadro_funcional/servidor'); ?>">Servidores</a></div>
				<div><a href="<?php echo url('quadro_funcional/estagiario'); ?>">Estagiários</a></div>
				<div><a href="<?php echo url('quadro_funcional/cedidoadido'); ?>">Servidores Cedidos/Adidos</a></div>
				<div><a href="<?php echo url('quadro_funcional/remuneracao'); ?>">Remunerações</a></div>
                <div><a href="<?php echo url('publicacao/quadro_funcional'); ?>" rel="shadowbox">Publicações</a></div>
			</div>
		</li>

        <li id="menu_publicacao">Publicações Gerais</li>
        <li id="submenu_publicacao" class="submenu">
            <div class="submenu">
				<div><a href="<?php echo url('publicacao/geral'); ?>" rel="shadowbox">Publicações Gerais</a></div>
			</div>
		</li>        
    </ul>
</div>

<div class="conteudo">
    <h2>Transparência</h2>
    <p>
        O Portal da Transparência da Confederação Nacional de Municípios é uma ferramenta para que o Cidadão
        tenha condições de analisar e fiscalizar de forma clara e objetiva a origem e aplicação dos recursos
        públicos de um município. Nele o Cidadão tem acesso as publicações,
        dados de lançamentos contábeis e orçamentários.
    </p>
    
    <div class="boneco">
        <div class="boneco_publicacao">Publicações</div>
        <div class="boneco_contato">Contato</div>
        
        <div class="boneco_publicacao_texto1">Dentro de cada área de consulta existe uma opção para visualizar as publicações da mesma.</div>
        <div class="boneco_contato_texto1">Entre em contato com o Município, setor específico ou Portal Transparência<br/> Urbem para tirar dúvidas ou<br/> solicitar informações.</div>
        
        <div class="boneco_publicacao_texto2">No menu de Publicações Gerais você poderá visualizar todas as publicações agrupadas por área ou filtrar as áreas de seu interesse.</div>
        <div class="boneco_contato_texto2">Entraremos em contato o<br/> mais breve possível para<br/> informar o seu protocolo.</div>
    </div>
</div>

<script type="application/javascript">
$(document).ready(function(){
	$("li#menu_despesa").click(function(){
		$("li#submenu_despesa").slideToggle();
        $("div.menu_home_esquerda ul li.submenu").not("li#submenu_despesa").slideUp("fast");
	});
    
	$("li#menu_receita").click(function(){
		$("li#submenu_receita").slideToggle();
        $("div.menu_home_esquerda ul li.submenu").not("li#submenu_receita").slideUp("fast");
	});
    
	$("li#menu_compra_licitacao").click(function(){
		$("li#submenu_compra_licitacao").slideToggle();
        $("div.menu_home_esquerda ul li.submenu").not("li#submenu_compra_licitacao").slideUp("fast");
	});     

	$("li#menu_quadro_funcional").click(function(){
		$("li#submenu_quadro_funcional").slideToggle();
        $("div.menu_home_esquerda ul li.submenu").not("li#submenu_quadro_funcional").slideUp("fast");
	});     

	$("li#menu_publicacao").click(function(){
		$("li#submenu_publicacao").slideToggle();
        $("div.menu_home_esquerda ul li.submenu").not("li#submenu_publicacao").slideUp("fast");
	});    
});
</script>