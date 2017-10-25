<?php date_default_timezone_set('America/Sao_Paulo'); ?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
    <div><?php echo Sessao::get('municipio_nome').'/'.Sessao::get('uf_sigla') ?></div>
    <div><?php echo $nomeRelatorio ?></div>
    <div>Emissão: {DATE d/m/Y - H:i}</div>
    <div>PÃ¡gina: {PAGENO} de {nbpg}</div>