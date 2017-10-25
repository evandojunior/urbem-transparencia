<?php
    ini_set('max_execution_time', 300);
    ini_set('error_reporting', E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);


    if (!isset($_SESSION)) { session_start(); } ;

	require_once __DIR__ . "/../vendor/autoload.php";

    $_REQUEST['cod_entidade'] = isset($_REQUEST['cod_entidade']) ? $_REQUEST['cod_entidade'] : null;
    $_REQUEST['exercicio'] = isset($_REQUEST['exercicio']) ? $_REQUEST['exercicio'] : null;
    $_REQUEST['page'] = isset($_REQUEST['page']) ? $_REQUEST['page'] : null;

    /*
     * Carrega as variï¿½veis de configuraï¿½ï¿½o
     */
	require_once '../settings.php';

	/*
     * Carrega as classes do ../core do framework
     */
    require_once '../core/doctrine/bootstrap.php';
	require_once '../core/utils.php';
	require_once '../core/sessao.class.php';
	require_once '../core/field.class.php';
	require_once '../core/validator.class.php';
	require_once '../core/plugin.class.php';
	require_once '../core/model.class.php';
	require_once '../core/controller.class.php';
	require_once '../core/load.class.php';
	require_once '../core/message.class.php';
	require_once '../core/email.class.php';

	if(!strstr($_SERVER['REQUEST_URI'], 'pdf')) {
		require_once '../core/form.class.php';
	}

	/*
	 * Classes responsï¿½veis pelo controle de acesso
	 */
	require_once '../modules/grupo/acao.base.php';

	/*
     * Carrega mï¿½dulos que sï¿½o utilizados em toda a aplicaï¿½ï¿½o
     */
	require_once '../modules/usuario/usuario.bo.php';

	if(!isset($_REQUEST['module'])){
		$_REQUEST['module'] = 'site';
		$_REQUEST['action'] = 'index';
	}

	/*
	 * Redireciona o usuï¿½rio para a tela inicial se a sessï¿½o estiver encessada
	 */
	if((empty($_SESSION)) && ($_REQUEST['module'] == 'site') && ($_REQUEST['action'] != 'index')){
		redirect();
	}

	/*
	 * Seta site session
	 */
	Sessao::set('site', '');

	/*
     * Realiza carregamento do mï¿½dulo principal
     * Inicializa buffer
     * Bufferiza saï¿½da
     * Salva saï¿½da bufferizada
     * Limpa buffer
     */

	ob_start();
	    Load::main();
	    $main = ob_get_contents();
	ob_end_clean();

	/*
	 * Quando a requisiï¿½ï¿½o for ajax, nï¿½o carrega template
	 */
	if(!isset($_REQUEST['ajax'])){

		/**
     	* Carrega template que serï¿½ utilizado na aplicaï¿½ï¿½o
     	*/
		if((!isset($GLOBALS['template'])) && (!isset($_REQUEST['template']))){
			require_once 'templates/default/index.php';

		} else {
			if(isset($_REQUEST['template'])){
				$template = $_REQUEST['template'];
			} else {
				$template = $GLOBALS['template'];
			}

			require_once 'templates/default/'.$template.'.php';
		}
	} else {
		print($main);
	}
