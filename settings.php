<?php

require_once 'vendor/autoload.php';

use \Symfony\Component\Yaml\Yaml;

if (!file_exists(__DIR__ . "/conf/parameters.yml")
    || !file_exists(__DIR__ . "/conf/prefeitura.yml.dist")) {
    throw new Exception("Arquivo de confirguração não encontrado!");
}

$conf = Yaml::parse(file_get_contents(__DIR__ . "/conf/parameters.yml"));
$parameters = $conf['parameters'];

$prefeituraYmlFile = __DIR__ . "/conf/prefeitura.yml";
if (file_exists($parameters['portal_transparencia_config_prefeitura_info_path'])) {
    $prefeituraYmlFile = $parameters['portal_transparencia_config_prefeitura_info_path'];
}

if (!file_exists($prefeituraYmlFile)) {
    copy($prefeituraYmlFile . ".dist", $prefeituraYmlFile);
    chmod($prefeituraYmlFile, 0777);
}

$prefeitura = Yaml::parse(file_get_contents($prefeituraYmlFile));

if (array_key_exists('debug', $parameters) && $parameters['debug']) {
    ini_set('display_errors', true);
}

date_default_timezone_set($parameters['timezone']);
define('HOST'   , $parameters['database_host']);
define('PORT'   , $parameters['database_port']);
define('USUARIO', $parameters['database_user']);
define('SENHA'  , $parameters['database_password']);
define('DB'     , $parameters['database_name']);

$GLOBALS['BASE_DIR']           =  __DIR__ . DIRECTORY_SEPARATOR;
$GLOBALS['BASE_URL']           = '/';#'http://'.$_SERVER["HTTP_HOST"].'/transparencia/';
//$GLOBALS['BASE_ADMIN_URL']     = 'http://'.$_SERVER["HTTP_HOST"].'/admin/';
//$GLOBALS['BASE_SITE_URL']      = 'http://'.$_SERVER["HTTP_HOST"].'/';
//$GLOBALS['BASE_MEDIA_BANNER']  = 'http://'.$_SERVER["HTTP_HOST"].'/media/img/banners/';
//$GLOBALS['BASE_URL_JS_PLUGIN'] = 'http://'.$_SERVER["HTTP_HOST"].'/plugins/js/';

$GLOBALS['TMP_DIR']   = $GLOBALS['BASE_DIR'].'media/tmp/';
$GLOBALS['TMP_URL']   = $GLOBALS['BASE_URL'].'media/tmp/';
$GLOBALS['MEDIA_IMG'] = $GLOBALS['BASE_URL'].'media/img/';

$GLOBALS['TRANSPARENCIA_FOTO_DIR']   = $GLOBALS['BASE_DIR'].'media/img/transparencia/';
$GLOBALS['TRANSPARENCIA_FOTO_URL']   = $GLOBALS['BASE_URL'].'media/img/transparencia/';
$GLOBALS['TRANSPARENCIA_THUMBS_DIR'] = $GLOBALS['BASE_DIR'].'media/img/transparencia/thumbs/';
$GLOBALS['TRANSPARENCIA_THUMBS_URL'] = $GLOBALS['BASE_URL'].'media/img/transparencia/thumbs/';

$GLOBALS['SITE_TITLE']         = 'Urbem - Manual';
$GLOBALS['MAIL_ADMINISTRATOR'] = 'suporte@cnm.org.br';
$GLOBALS['MAIL_TITLE']         = 'Urbem - Manual';
$GLOBALS['MAIL_SENDER']        = 'suporte@cnm.org.br';
$GLOBALS['TEMPLATE']           = 'default';
$GLOBALS['TOKEN_WEBSERVICE']   = 'a43ew9q98e26wqe87hsah0!';

//$GLOBALS['MODULES']   = array('usuario', 'grupo', 'menu', 'transparencia', 'endereco', 'historico', 'home', 'configuracao', 'urbem', 'site', 'configuracaoEntidade', 'publicacao', 'mpdf');
$GLOBALS['MODULES']   = array('site', 'mpdf');
$GLOBALS['ANONYMOUS'] = [];//array('usuario.login', 'usuario.setLogin', 'usuario.logout');
$GLOBALS['RESERVED']  = array('template', 'meta');

$GLOBALS['sigla']  = $prefeitura['uf'];
$GLOBALS['municipio_nome'] = $prefeitura['nome'];
$GLOBALS['municipio_db'] = DB;

$GLOBALS['municipio_id'] = "";
$GLOBALS['municipio_alias'] = "";
$GLOBALS['municipio_hash'] = "";

// CONFIGURAÇAO PARA ENVIO
define('SMTP_SERVER', $parameters['mailer_host']);
define('SMTP_USERNAME', $parameters['mailer_user']);
define('SMTP_PASSWORD', $parameters['mailer_password']);
define('SMTP_PORT', $parameters['mailer_port']);
define('SMTP_SECURITY', $parameters['mailer_encryption']);

// CONFIGURAÇAO PARA RECEBIMENTO
define('MAIL_ADMINISTRATOR', $prefeitura['email']);
define('MAIL_TITLE', $prefeitura['email_assunto']);