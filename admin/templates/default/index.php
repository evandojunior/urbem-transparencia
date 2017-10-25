<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo $GLOBALS['SITE_TITLE'] ?> - Área administrativa</title>
	
	<?php Plugin::load('jquery'); ?>
	
	<link href="<?php echo url('templates/default/css/template.css') ?>" rel="stylesheet" type="text/css" />
	<script src="<?php echo url('templates/default/js/template.js') ?>"  type="application/javascript"></script>
</head>
<body>
	<div class="topo">
		<div class="logo"><img src="<?php echo url('templates/default/img/topo.png'); ?>"></div>
	</div>
	<div class="menu"><?php Load::module('menu', 'show'); ?></div>
	
	<div class="messages">
		<ul><?php Message::getInstance()->showMessages(); ?></ul>
	</div>
	
	<div class="conteudo"><?php echo $main ?></div>
	
    <div class="rodape"><b>Usuário:</b> <?php echo Sessao::get('usuario_nome'); ?> - <b>Município:</b> <?php echo Sessao::get('municipio_nome'); ?></div>
	
	<input type="hidden" id="module"       value="<?php echo $_REQUEST['module'] ?>"/>
	<input type="hidden" id="module_alias" value="<?php echo $_REQUEST['module_alias'] ?>"/>
	<input type="hidden" id="action"       value="<?php echo $_REQUEST['action'] ?>"/>
	<input type="hidden" id="id"           value="<?php echo @$_REQUEST['id'] ?>"/>
</body>
</html>
