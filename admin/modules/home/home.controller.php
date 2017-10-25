<?php

Load::_require('core/conn.class.php');

Load::_require('modules/pessoa/pessoa.base.php');
Load::_require('modules/pessoa/pessoa.class.php');

Load::_require('modules/usuario/usuario.base.php');
Load::_require('modules/usuario/usuario.class.php');

Load::_require('modules/publicacao/secao.base.php');
Load::_require('modules/publicacao/secao.class.php');
Load::_require('modules/publicacao/secao.bo.php');

Load::_require('modules/publicacao/publicacao.base.php');
Load::_require('modules/publicacao/publicacao.class.php');
Load::_require('modules/publicacao/publicacao.bo.php');
Load::_require('modules/publicacao/publicacao.form.php');

class HomeController extends Controller{

	public static function index(){
		$manualArquivo = $GLOBALS['BASE_URL'].'media/docs/manual.pdf';		
		
		# Carrega somente para o usuário com município configurado. Ou seja, administradores não vêem a lista de publicações
		if(Sessao::get('usuario_grupo_alias') == 'usuario'){
			/********** Conexão **********/
			$manager = Conn::openConnection(Sessao::get('municipio_db'));
			/**********/
			
			$publicacoes = PublicacaoBO::getUltimos(10);
			
			/********** Conexão **********/
			$manager->close();
			Conn::openConnection(DB);
			/**********/
			
			$snippet = array('title' => 'Últimas Publicações do Município');
			$context = array(
				'snippet'       => $snippet,
				'publicacoes'   => $publicacoes,
				'manualArquivo'	=> $manualArquivo
			);
	
			Load::view('home/views/index.php', $context);
			
		} else {
			
			$snippet = array();
			$context = array(
				'snippet'       => $snippet,
				'publicacoes'   => array(),
				'manualArquivo'	=> $manualArquivo
			);
	
			Load::view('home/views/index.php', $context);
		}
	}
}