<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');

Load::_require('modules/modulo/modulo.base.php');
Load::_require('modules/modulo/modulo.class.php');
Load::_require('modules/modulo/modulo.bo.php');

Load::_require('modules/endereco/endereco.base.php');
Load::_require('modules/endereco/endereco.class.php');
Load::_require('modules/endereco/endereco.bo.php');

Load::_require('modules/configuracao/configuracao.base.php');
Load::_require('modules/configuracao/configuracao.class.php');
Load::_require('modules/configuracao/configuracao.bo.php');
Load::_require('modules/configuracao/configuracao.form.php');


class ConfiguracaoController extends Controller{

	public static function index(){
		hasPerm('configuracao.index');
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$configuracaoDTO = ConfiguracaoBO::filter($search);

		$form = new FormConfiguracaoFilter($_GET);

		$snippet = array('title' => 'Pesquisar lista de parâmetros', 'pager' => $configuracaoDTO->getSearch()->getPager());
		$context = array(
			'snippet' 	=> $snippet,
			'form' 		=> $form,
			'configuracao' 		=> $configuracaoDTO->getObj(),
		);

		Load::view('configuracao/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('configuracao.show');
		
		$snippet = array('title' => 'Consultar configuracao');
		$context = array(
			'snippet'      => $snippet,
			'configuracao' => ConfiguracaoBO::get($_REQUEST['id']),
		);

		Load::view('configuracao/views/show.php', $context);
	}	
	
	public static function _new(){
		hasPerm('configuracao.create');
		
		$form = new FormConfiguracao();

		$snippet = array('title' => 'Adicionar novo parâmetro de configuração');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);
	
		Load::view('configuracao/views/new.php', $context);			
	}	

	public static function create(){
		hasPerm('configuracao.create');
		postRequired('configuracao/new');
		
		$form = new FormConfiguracao($_POST);

		if($form->isValid()){
			try{
				$configuracao = new Configuracao();
				$configuracao->setMunicipioId($form->getFields('municipio_id')->getValue());
				$configuracao->setModuloId($form->getFields('modulo_id')->getValue());
				$configuracao->setAlias($form->getFields('alias')->getValue());
				$configuracao->setParametro($form->getFields('parametro')->getValue());
				$configuracao->setValor($form->getFields('valor')->getValue());
				$configuracao->setDescricao($form->getFields('descricao')->getValue());
                              
				$configuracao = ConfiguracaoBO::create($configuracao);

				Message::getInstance()->success('Parâmetro de configuração adicionado com sucesso');
				
				$form->clean();

				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Adicionar novo parâmetro de configuração');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);

		Load::view('configuracao/views/new.php', $context);
	}
	
	public static function edit(){
		hasPerm('configuracao.update');
		
		$configuracao = ConfiguracaoBO::get($_REQUEST['id']);
		
		$configuracaoData = array(
			'id'  	       => $configuracao->getId(),
			'municipio_id' => $configuracao->getMunicipioId(),
			'modulo_id'    => $configuracao->getModuloId(),
			'alias'        => $configuracao->getAlias(),
			'parametro'    => $configuracao->getParametro(),
			'valor'        => $configuracao->getValor(),
			'descricao'    => $configuracao->getDescricao(),
		);
		$form = new FormConfiguracao($configuracaoData);
		
		$snippet = array('title' => 'Editar parâmetro de configuração');
		$context = array(
			'snippet'      => $snippet,
			'form'         => $form,
			'configuracao' => $configuracao,
		);

		Load::view('configuracao/views/edit.php', $context);		
	}	

	public static function update(){
		hasPerm('configuracao.update');
		postRequired('configuracao/edit/'.$_REQUEST['id']);
		
		$configuracao = ConfiguracaoBO::get($_REQUEST['id']);
		
		$form = new FormConfiguracao($_POST);
		
		if($form->isValid()){
			try{
				$configuracao->setMunicipioId($form->getFields('municipio_id')->getValue());
				$configuracao->setModuloId($form->getFields('modulo_id')->getValue());
				$configuracao->setAlias($form->getFields('alias')->getValue());
				$configuracao->setParametro($form->getFields('parametro')->getValue());
				$configuracao->setValor($form->getFields('valor')->getValue());
				$configuracao->setDescricao($form->getFields('descricao')->getValue());
				
				$configuracao = ConfiguracaoBO::update($configuracao, $conn);

				Message::getInstance()->success('Parâmetro editado com sucesso');
				
				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Editar parâmetro de configuração');
		$context = array(
			'snippet'      => $snippet,
			'form'         => $form,
			'configuracao' => $configuracao,
		);

		Load::view('configuracao/views/edit.php', $context);
	}
	
	public static function delete(){
		hasPerm('configuracao.delete');
		try{
			ConfiguracaoBO::delete($_REQUEST['id']);

			Message::getInstance()->success('Parâmetro deletado com sucesso');
			
		} catch(Exception $e){
			Message::getInstance()->error($e->getMessage());
		}
		
		redirect(Search::restore());
	}
}