<?php Load::snippet('header_municipio', $snippet); ?>



<!-- About Section -->
<section class="row success text-center margin-top">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h2>Transparência</h2>
            <p class="text-justify">
                O Portal da Transparência da Confederação Nacional de Municípios é uma ferramenta para que o Cidadão
                tenha condições de analisar e fiscalizar de forma clara e objetiva a origem e aplicação dos recursos
                públicos de um município. Nele o Cidadão tem acesso as publicações,
                dados de lançamentos contábeis e orçamentários.
            </p>
        </div>
    </div>
</section>



<!-- Menu Tabs Section -->
<section class="row success text-center margin-top">
    <div class="row">
        <div class="col-lg-12 text-center">

            <div class="tabs">

                <ul>
                    <li ><a data-toggle="tab" href="#tabs-1">Despesas</a></li>
                    <li ><a data-toggle="tab" href="#tabs-2">Receitas</a></li>
                    <li ><a data-toggle="tab" href="#tabs-3">Compras e Licitações</a></li>
                    <li ><a data-toggle="tab" href="#tabs-4">Quadro funcional</a></li>
                    <li ><a data-toggle="tab" href="#tabs-5">Publicações gerais</a></li>
                </ul>

                <div class="tab-content">

                    <div id="tabs-1" class="tab-pane">
                        <h3>Despesas</h3>
                        <div class="colunas">
                            <div><a href="<?php echo $hasEntidades ? url('despesa/orgao') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Órgao</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/funcao') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Função/Subfunção</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/programa') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Programa</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/projeto') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Projeto/Atividade</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/categoria') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Categoria</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/recurso') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Recurso</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('despesa/credor') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Despesa por Credor</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('publicacao/despesa') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?> rel="shadowbox">Publicações</a></div>
                        </div>
                    </div>

                    <div id="tabs-2" class="tab-pane">
                        <h3>Receitas</h3>
                        <div class="colunas">
                            <div><a href="<?php echo $hasEntidades ? url('receita/conta') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Receita por Conta</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('receita/mes') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Receita por Mês</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('publicacao/receita') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?> rel="shadowbox">Publicações</a></div>
                        </div>
                    </div>

                    <div id="tabs-3" class="tab-pane">
                        <h3>Compras e Licitações</h3>
                        <div class="colunas">
                            <div><a href="<?php echo $hasEntidades ? url('compra_licitacao/compra') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Compras</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('compra_licitacao/licitacao') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Licitações</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('publicacao/compra_licitacao') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?> rel="shadowbox">Publicações</a></div>
                        </div>

                    </div>

                    <div id="tabs-4" class="tab-pane">
                        <h3>Quadro funcional</h3>
                        <div class="colunas">
                            <div><a href="<?php echo $hasEntidades ? url('quadro_funcional/cargo') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Cargos</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('quadro_funcional/servidor') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Servidores</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('quadro_funcional/estagiario') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Estagiários</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('quadro_funcional/cedidoadido') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Servidores Cedidos/Adidos</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('quadro_funcional/remuneracao') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?>>Remunerações</a></div>
                            <div><a href="<?php echo $hasEntidades ? url('publicacao/quadro_funcional') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?> rel="shadowbox">Publicações</a></div>
                        </div>
                    </div>

                    <div id="tabs-5" class="tab-pane">
                        <h3>Publicações gerais</h3>
                        <div class="colunas">
                            <p>&nbsp;</p>
                            <div><a href="<?php echo $hasEntidades ? url('publicacao/geral') : '#' ?>" <?php echo $hasEntidades ? '' : 'class="disabled"' ?> rel="shadowbox">Publicações Gerais</a></div>
                            <p>&nbsp;</p>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</section>



<!-- Two Boxes Section -->
<div class="background-03"></div>

<section class="row success text-center margin-top">
        <div class="row">

            <div class="col-lg-6 text-center">
                <div class="box">
                    <img src="<?php echo url('templates/default/img/icon-publicacoes.png?20170801') ?>" alt="Publicações">
                    <div class="caption">
                        <h3>Publicações</h3>
                        <p class="text-justify">Dentro de cada área de consulta existe uma opção para visualizar as publicações da mesma.</p>
                        <p class="text-justify">No menu de Publicações Gerais você poderá visualizar todas as publicações agrupadas por área ou filtrar as áreas de seu interesse.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 text-center">
                <div class="box">
                    <img src="<?php echo url('templates/default/img/icon-contato.png?20170801') ?>" alt="Contato">
                    <div class="caption">
                        <h3>Contato</h3>
                        <p class="text-justify">Entre em contato com o Município, setor específico ou Portal Transparência Urbem para tirar dúvidas ou solicitar informações.</p>
                        <p class="text-justify">Entraremos em contato o mais breve possível para informar o seu protocolo.</p>
                    </div>
                </div>
            </div>

    </div>
</section>
