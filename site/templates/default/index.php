<!DOCTYPE html>
<html>
<head>
    <title>Urbem - Transparência</title>
    <meta name="title" content="Urbem - Transparência"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <!-- Carrega plugins utilizados em todo o site -->
    <?php Plugin::load('jquery'); ?>
    <?php Plugin::load('shadowbox'); ?>
    <?php Plugin::load('jquery-ui'); ?>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo url('templates/default/css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css">

    <!-- Carrega arquivos CSS do template -->
    <link href="<?php echo url('templates/default/css/template.css?20170801') ?>" rel="stylesheet" type="text/css"/>

    <!-- Carrega arquivos JS do template -->
    <script src="<?php echo url('templates/default/js/template.js') ?>" type="application/javascript"></script>

</head>

<body>

<div class="se-pre-con"></div>

<script type="application/javascript">
    $(document).ready(function(){
        $( ".tabs" ).tabs({
            beforeLoad: function( event, ui ) {
                ui.jqXHR.error(function() {
                    ui.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible. " +"If this wouldn't be a demo." );
                });
            }
        });
    });
</script>

<div class="principal">

    <div class="topo">
        <div class="menu">
            <div class="menu_logo"><a href="http://www.cnm.org.br" target="_blank"><img src="<?php echo url('templates/default/img/logo_cnm_menu2.png') ?>" alt="Logo CNM" /></a></div>
            <ul>
                <?php if (Sessao::get('municipio_db')) { ?>
                    <li><a href="<?php echo url('contato') ?>">Fale Conosco</a></li>
                <?php } ?>

                <li><a href="<?php echo url('legislacao') ?>">Legislação</a></li>
                <li><a href="/">Início</a></li>
            </ul>
        </div>
    </div>

    <div class="background">
        <div class="matrix"></div>
    </div>


    <div class="messages">
        <ul><?php Message::getInstance()->showMessages(); ?></ul>
    </div>

    <?php echo $main; ?>


<div class="rodape"></div>

</div>

<input type="hidden" id="module" value="<?php echo $_REQUEST['module'] ?>"/>
<input type="hidden" id="module_alias" value="<?php echo @$_REQUEST['module_alias'] ?>"/>
<input type="hidden" id="action" value="<?php echo $_REQUEST['action'] ?>"/>
<input type="hidden" id="id" value="<?php echo @$_REQUEST['id'] ?>"/>

<script type="application/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-40713250-1']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'application/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

</script>

<!-- Bootstrap core JS -->
<script src="<?php echo url('templates/default/js/bootstrap.min.js') ?>"></script>

</body>
</html>