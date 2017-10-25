<?php Plugin::load('jtooltip'); ?>

<script type="application/javascript">
    $(document).ready(function () {
        $("#mpdf").click(function () {

            var elem = $(this);
            $.ajax({
                type: "GET",
                url: window.location + '/pdf',
                beforeSend:function(){
                    $(".se-pre-con").fadeIn("fast");
                },

                success: function(data) {
                    window.location = $("a#inicio").attr("href") + data.replace("/", "");
                },

                complete:function(){
                    $(".se-pre-con").fadeOut("fast");
                }
            });
        });
    });
</script>

<div class="header_reduzido">
    <div class="header_reduzido_esquerda">
        <div class="header_reduzido_titulo">Município</div>
        <div class="header_reduzido_nome"><?php echo Sessao::get('municipio_nome'); ?> / <?php echo Sessao::get('uf_sigla'); ?> <br/>
            <?php /*<a href="<?php echo url(Sessao::get('municipio_alias').'/'.Sessao::get('uf_sigla').'/alterar-municipio') ?>" class="alterar_municipio">(Alterar município)</a>*/ ?>
        </div>
        <div class="header_reduzido_migalhas">
            <a href="/" id="inicio">Início</a>
            <span class="migalhas">&gt;</span>

            <?php
            $listaCategoria = array('despesa' => 'Despesa',
                'receita' => 'Receita',
                'compra_licitacao' => 'Compras e Licitações',
                'quadro_funcional' => 'Quadro Funcional');

            $listaSecao = array('despesa' => array('orgao' => 'Orgão',
                'funcao' => 'Função',
                'programa' => 'Programa',
                'projeto' => 'Projeto',
                'categoria' => 'Categoria',
                'recurso' => 'Recurso',
                'credor' => 'Credor'),

                'receita' => array('conta' => 'Receita por Conta',
                    'mes' => 'Receita por Mês'),

                'compra_licitacao' => array('compra' => 'Compras',
                    'licitacao' => 'Licitações'),

                'quadro_funcional' => array('cargo' => 'Cargo',
                    'servidor' => 'Servidores',
                    'estagiario' => 'Estariágios',
                    'cedidoadido' => 'Servidores Cedidos/Adidos',
                    'remuneracao' => 'Remunerações'),
            );

            $categoria = $listaCategoria[$_REQUEST['categoria']];
            $secao = $listaSecao[$_REQUEST['categoria']][$_REQUEST['secao']];
            ?>

            <a href="#" id="<?php echo $_REQUEST['categoria'] ?>"><?php echo $categoria; ?></a>
            <span class="migalhas">&gt;</span> <a
                    href="<?php echo url(Sessao::get('path') . '/' . $_REQUEST['categoria'] . '/' . $_REQUEST['secao'] . getQueryStringPadrao()); ?>"><?php echo $secao; ?></a>

            <?php if (isset($_REQUEST['natureza'])) { ?>
                <span class="migalhas">&gt;</span> <a
                        href="<?php echo url(Sessao::get('path') . '/' . $_REQUEST['categoria'] . '/' . $_REQUEST['secao'] . '/' . $_REQUEST['cod_categoria'] . '/natureza' . getQueryStringPadrao()); ?>">Natureza</a>
            <?php } ?>

            <?php if (isset($_REQUEST['elemento'])) { ?>
                <span class="migalhas">&gt;</span> <a
                        href="<?php echo url(Sessao::get('path') . '/' . $_REQUEST['categoria'] . '/' . $_REQUEST['secao'] . '/' . $_REQUEST['cod_categoria'] . '/natureza/' . $_REQUEST['cod_natureza'] . '/elemento' . getQueryStringPadrao()); ?>">Elemento</a>
            <?php } ?>

            <?php if (isset($_REQUEST['empenho'])) { ?>
                <span class="migalhas">&gt;</span> Empenho
            <?php } ?>
        </div>
    </div>
    <div class="header_reduzido_direita">
        <div class="header_reduzido_logo"><img src="<?php echo url('templates/default/img/logo_pequeno.png') ?>" alt="Logo CNM" height="40"/></div>
        <div class="header_reduzido_publicacao">
            <div style="margin-top:2px;"><a
                        href="<?php echo url(Sessao::get('path') . '/publicacao/' . $_REQUEST['categoria']) ?>" rel="shadowbox"
                        id="publicacao">Publicações <img src="<?php echo url('templates/default/img/icon-notepad.png?20170801'); ?>" width="20" /></a>
            </div>
            <div style="margin-top:6px;">
                <a href="javascript:void(0)" id="mpdf">
                    Imprimir
                    <img src="<?php echo url('templates/default/img/icon-pdf.png?20170801'); ?>" width="20"/>
                </a>
            </div>
        </div>

    </div>
</div>
<div class="data_atualizacao">
    Dados atualizados: <?php echo formatDateToPHP(ImportacaoBO::getUltimo()->data_limite_dado); ?>
</div>

<script type="application/javascript">
    $(document).ready(function () {
        <?php if($_REQUEST['categoria'] == 'despesa'){ ?>
        var tooltip = "<div class='tooltip-submenu'>";
        tooltip += "        <div><a href='<?php echo url("despesa/orgao" . getQueryStringPadrao()); ?>'>Despesa por Orgão</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/funcao" . getQueryStringPadrao()); ?>'>Despesa por Função/Subfunção</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/programa" . getQueryStringPadrao()); ?>'>Despesa por Programa</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/projeto" . getQueryStringPadrao()); ?>'>Despesa por Projeto/Atividade</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/categoria" . getQueryStringPadrao()); ?>'>Despesa por Categoria</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/recurso" . getQueryStringPadrao()); ?>'>Despesa por Recurso</a></div>";
        tooltip += "        <div><a href='<?php echo url("despesa/credor" . getQueryStringPadrao()); ?>'>Despesa por Credor</a></div>";
        tooltip += "</div>";
        <?php } ?>

        <?php if($_REQUEST['categoria'] == 'receita'){ ?>
        var tooltip = "<div class='tooltip-submenu'>";
        tooltip += "        <div><a href='<?php echo url("receita/conta"); ?>'>Receita por Conta</a></div>";
        tooltip += "        <div><a href='<?php echo url("receita/mes"); ?>'>Receita por Mês</a></div>";
        tooltip += "</div>";
        <?php } ?>

        <?php if($_REQUEST['categoria'] == 'compra_licitacao'){ ?>
        var tooltip = "<div class='tooltip-submenu'>";
        tooltip += "        <div><a href='<?php echo url("compra_licitacao/compra"); ?>'>Compras</a></div>";
        tooltip += "        <div><a href='<?php echo url("compra_licitacao/licitacao"); ?>'>Licitações</a></div>";
        tooltip += "</div>";
        <?php } ?>

        <?php if($_REQUEST['categoria'] == 'quadro_funcional'){ ?>
        var tooltip = "<div class='tooltip-submenu'>";
        tooltip += "        <div><a href='<?php echo url("quadro_funcional/cargo"); ?>'>Cargos</a></div>";
        tooltip += "        <div><a href='<?php echo url("quadro_funcional/servidor"); ?>'>Servidores</a></div>";
        tooltip += "        <div><a href='<?php echo url("quadro_funcional/estagiario"); ?>'>Estagiários</a></div>";
        tooltip += "        <div><a href='<?php echo url("quadro_funcional/cedidoadido"); ?>'>Servidores Cedidos/Adidos</a></div>";
        tooltip += "        <div><a href='<?php echo url("quadro_funcional/remuneracao"); ?>'>Remunerações</a></div>";
        tooltip += "</div>";
        <?php } ?>

        $('#<?php echo $_REQUEST['categoria']; ?>').qtip({
            content: tooltip,
            position: {my: 'top center', at: 'bottom center'},
            hide: {event: false, inactive: 1500},
            style: {tip: true, classes: 'qtip-green'}
        });
    });
</script>