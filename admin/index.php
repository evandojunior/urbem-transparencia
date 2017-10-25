<?php

	if (!isset($_SESSION)) { session_start(); } ;

	/**
     * Carrega as variáveis de configuração
     */
	require_once '../settings.php';
	
	/**
     * Carrega as classes do ../core do framework
     */
	require_once '../core/sessao.class.php';
    require_once '../core/doctrine/bootstrap.php';
	require_once '../core/utils.php';
	require_once '../core/form.class.php';
	require_once '../core/field.class.php';
	require_once '../core/validator.class.php';
	require_once '../core/plugin.class.php';
	require_once '../core/model.class.php';
	require_once '../core/controller.class.php';
	require_once '../core/load.class.php';
	require_once '../core/message.class.php';
	require_once '../core/pagination.class.php';
	
	/**
	 * Classes responsáveis pela configuração
	 */
	require_once '../modules/configuracao/configuracao.base.php';
	require_once '../modules/configuracao/configuracao.class.php';
	require_once '../modules/configuracao/configuracao.bo.php';	
		
	/**
	 * Carrega classes responsáveis pela geração de log
	 */
	require_once '../modules/log/log.base.php';
	require_once '../modules/log/log.class.php';
	require_once '../modules/log/log.bo.php';	
	
	//LogBO::create();
	
	/**
	 * Classes responsáveis pelo controle de acesso
	 */
	require_once '../modules/grupo/acao.base.php';
	
	/**
     * Carrega módulos que são utilizados em toda a aplicação
     */
	require_once 'modules/menu/menu.controller.php';
	require_once '../modules/usuario/usuario.bo.php';
	
	/**
	 * Seta site na sessão
	 */
	Sessao::set('site', 'admin/');

	/**
	 * Verifica se o usuário está autenticado
	 */
	UsuarioBO::isAuth($_REQUEST);
	
	/**
     * Realiza carregamento do módulo principal
     * Inicializa buffer
     * Bufferiza saída
     * Salva saída bufferizada
     * Limpa buffer
     */ 
	ob_start();
	    Load::main();
	    $main = ob_get_contents();
	ob_end_clean();
	
	/**
	 * Quando a requisição for ajax, não carrega template
	 */
	if(!isset($_REQUEST['ajax'])){
		
		/**
     	* Carrega template que será utilizado na aplicação
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