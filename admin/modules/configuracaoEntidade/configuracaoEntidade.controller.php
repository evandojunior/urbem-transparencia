<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');
Load::_require('core/conn.class.php');

Load::_require('modules/modulo/modulo.base.php');
Load::_require('modules/modulo/modulo.class.php');
Load::_require('modules/modulo/modulo.bo.php');

Load::_require('modules/transparencia/entidade.base.php');
Load::_require('modules/transparencia/entidade.class.php');

Load::_require('modules/configuracaoEntidade/configuracaoEntidade.base.php');
Load::_require('modules/configuracaoEntidade/configuracaoEntidade.class.php');
Load::_require('modules/configuracaoEntidade/configuracaoEntidade.bo.php');
Load::_require('modules/configuracaoEntidade/configuracaoEntidade.form.php');


class ConfiguracaoEntidadeController extends Controller{

	public static function index(){
		hasPerm('configuracaoEntidade.index');
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/
		
		$configuracaoEntidade = ConfiguracaoEntidadeBO::get();
		
		if($configuracaoEntidade instanceof ConfiguracaoEntidade) {
			$configuracaoEntidadeData = array(
				'id'          => $configuracaoEntidade->getId(),
				'entidade_id' => ConfiguracaoEntidadeBO::getArrayEntidadeId(),
			);
			
		} else {
			$configuracaoEntidadeData = array();
		}
		
		$form = new FormConfiguracaoEntidade($configuracaoEntidadeData);
		
		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/		
		
		$snippet = array('title' => 'Entidades a serem apresentadas no site');
		$context = array(
			'snippet' => $snippet,
			'form'    => $form,
		);

		Load::view('configuracaoEntidade/views/index.php', $context);		
	}	

	public static function update(){
		hasPerm('configuracaoEntidade.index');
		postRequired('configuracaoEntidade');
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/		
		
		$form = new FormConfiguracaoEntidade($_POST);
		
		ConfiguracaoEntidadeBO::deleteAll();
		
		if($form->isValid()){
			try{
				$entidades = $form->getFields('entidade_id')->getValue();
				
				foreach($entidades as $entidadeId) {
					$configuracaoEntidade = new ConfiguracaoEntidade();
					$configuracaoEntidade->setEntidadeId($entidadeId);
					ConfiguracaoEntidadeBO::create($configuracaoEntidade, $conn);
				}
				
				Message::getInstance()->success('Dados salvos.');
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/		
		
		$snippet = array('title' => 'Entidades a serem apresentadas no site');
		$context = array(
			'snippet'              => $snippet,
			'form'                 => $form,
			'configuracaoEntidade' => $configuracaoEntidade,
		);

		Load::view('configuracaoEntidade/views/index.php', $context);
	}
}