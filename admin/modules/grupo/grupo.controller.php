<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');

Load::_require('modules/categoria/categoria.base.php');
Load::_require('modules/categoria/categoria.class.php');
Load::_require('modules/categoria/categoria.bo.php');

Load::_require('modules/grupo/acao.base.php');
Load::_require('modules/grupo/acao.class.php');

Load::_require('modules/grupo/grupo.base.php');
Load::_require('modules/grupo/grupo.class.php');
Load::_require('modules/grupo/grupo.bo.php');
Load::_require('modules/grupo/grupo.form.php');

Load::_require('modules/usuario/usuario.base.php');
Load::_require('modules/usuario/usuario.class.php');


class GrupoController extends Controller{

	public static function index(){
		hasPerm('grupo.index');
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$grupoDTO = GrupoBO::filter($search);

		$form = new FormGrupoFilter($_GET);

		$snippet = array('title' => 'Pesquisar Grupos', 'pager' => $grupoDTO->getSearch()->getPager());
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
			'grupos'  => $grupoDTO->getObj(),
		);

		Load::view('grupo/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('grupo.show');
		
		$snippet = array('title' => 'Consultar Grupos');
		$context = array(
			'snippet'   => $snippet,
			'grupo'     => GrupoBO::get($_REQUEST['id']),
			'grupoAcao' => GrupoAcaoBO::getByGrupo($_REQUEST['id']),
		);

		Load::view('grupo/views/show.php', $context);
	}	
	
	public static function _new(){
		hasPerm('grupo.create');
		
		$form = new FormGrupo();

		$snippet = array('title' => 'Adicionar Novo Grupo');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);
	
		Load::view('grupo/views/new.php', $context);			
	}	

	public static function create(){
		hasPerm('grupo.create');
		postRequired('grupo/new');
		
		$form = new FormGrupo($_POST);

		if($form->isValid()){
			try{
				$conn = Doctrine_Manager::connection();
				$conn->beginTransaction();
			    
				$grupo = new Grupo();
				$grupo->setGrupo($form->getFields('grupo')->getValue());
				$grupo->setAlias($form->getFields('alias')->getValue());
                              
				$grupo = GrupoBO::create($grupo, $conn);
				
                foreach($form->getFields('acao')->getValue() as $acaoId){
                	if($acaoId != ''){
	                    $grupoAcao = new GrupoAcao();
	                    $grupoAcao->setGrupoId($grupo->getId());
	                    $grupoAcao->setAcaoId($acaoId);
	                    GrupoAcaoBO::create($grupoAcao, $conn);
                	}
                }

				Message::getInstance()->success('Grupo adicionado com sucesso');
				
				$form->clean();
				$conn->commit();

				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
			    $conn->rollback();
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Adicionar Novo Grupo');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);

		Load::view('grupo/views/new.php', $context);
	}
	
	public static function edit(){
		hasPerm('grupo.update');
		
		$grupo = GrupoBO::get($_REQUEST['id']);
		
		$grupoData = array(
			'id'  	=> $grupo->getId(),
			'grupo' => $grupo->getGrupo(),
			'alias' => $grupo->getAlias(),
			'acao'  => GrupoAcaoBO::getArrayGrupoAcaoId($_REQUEST['id']),
		);
		$form = new FormGrupo($grupoData);
		
		$snippet = array('title' => 'Editar Grupo');
		$context = array(
			'snippet' => $snippet,
			'form'    => $form,
			'grupo'    => $grupo,
		);

		Load::view('grupo/views/edit.php', $context);		
	}	

	public static function update(){
		hasPerm('grupo.update');
		postRequired('grupo/edit/'.$_REQUEST['id']);
		
		$grupo = GrupoBO::get($_REQUEST['id']);
		
		$form = new FormGrupo($_POST);
		
		if($form->isValid()){
			try{
				$grupo->setGrupo($form->getFields('grupo')->getValue());
				$grupo->setAlias($form->getFields('alias')->getValue());
				
				$grupo = GrupoBO::update($grupo, $conn);
				
				GrupoAcaoBO::deleteByGrupo($_REQUEST['id']);
                
                foreach($form->getFields('acao')->getValue() as $acaoId){
                	if($acaoId != ''){
	                    $grupoAcao = new GrupoAcao();
	                    $grupoAcao->setGrupoId($grupo->getId());
	                    $grupoAcao->setAcaoId($acaoId);
	                    GrupoAcaoBO::create($grupoAcao, $conn);
                	}
                }					

				Message::getInstance()->success('Grupo editado com sucesso');
				
				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Editar Grupo');
		$context = array(
			'snippet' => $snippet,
			'form'    => $form,
			'grupo'   => $grupo,
		);

		Load::view('grupo/views/edit.php', $context);
	}
	
	public static function delete(){
		hasPerm('grupo.delete');
		try{
			$conn = Doctrine_Manager::connection();
			$conn->beginTransaction();
			
			GrupoAcaoBO::deleteByGrupo($_REQUEST['id'], $conn);
			GrupoBO::delete($_REQUEST['id'], $conn);
			
			$conn->commit();

			Message::getInstance()->success('Grupo deletado com sucesso');
			
		} catch(Exception $e){
		    $conn->rollback();
			Message::getInstance()->error($e->getMessage());
		}
		
		redirect(Search::restore());
	}
}