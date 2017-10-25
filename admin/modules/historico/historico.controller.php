<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');

Load::_require('modules/usuario/usuario.base.php');
Load::_require('modules/usuario/usuario.class.php');

Load::_require('modules/modulo/modulo.base.php');
Load::_require('modules/modulo/modulo.class.php');
Load::_require('modules/modulo/modulo.bo.php');

Load::_require('modules/historico/historico.base.php');
Load::_require('modules/historico/historico.class.php');
Load::_require('modules/historico/historico.form.php');
Load::_require('modules/historico/historico.bo.php');


class HistoricoController extends Controller{

	public static function index(){
		hasPerm('historico.index');
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$historicoDTO = HistoricoBO::filter($search);
		$form = new FormHistoricoFilter($_GET);

		$snippet = array('title' => 'Pesquisar posições no histórico', 'pager' => $historicoDTO->getSearch()->getPager());
		$context = array(
			'snippet'    => $snippet,
			'form' 	     => $form,
			'historicos' => $historicoDTO->getObj(),
		);

		Load::view('historico/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('historico.show');
		
		$snippet = array('title' => 'Consultar posição');
		$context = array(
			'template'  => 'iframe',
			'snippet'   => $snippet,
			'historico' => HistoricoBO::get($_REQUEST['id']),
		);

		Load::view('historico/views/show.php', $context);
	}	
	
	public static function _new(){
		hasPerm('historico.create');
		
		$form = new FormHistorico();
		
		$snippet = array('title' => 'Adicionar nova posição');
		$context = array(
	        'template' => 'iframe',
			'snippet'  => $snippet,
	        'form'     => $form,
		);

		Load::view('historico/views/new.php', $context);			
	}	

	public static function create(){
		hasPerm('historico.create');
		postRequired('historico/new');
		
		$form = new FormHistorico($_POST);

		if($form->isValid()){
			try{
				$modulo = ModuloBO::getByAlias($_REQUEST['moduleHistorico']);
				
				$historico = new Historico();
				$historico->setPessoaId(Sessao::get('usuario_pessoa_id'));
				$historico->setModuloId($modulo->getId());
				$historico->setEntidadeId($_REQUEST['entidade_id']);
				$historico->setDescricao($form->getFields('descricao')->getValue());
				
				$historico = HistoricoBO::create($historico);

				Message::getInstance()->success('Posição adicionada com sucesso ao histórico');
				
				$form->clean();
				
				$closeFrame = true;
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Adicionar nova posição');
		$context = array(
		    'template'   => 'iframe',	
			'form'       => $form,
			'snippet'    => $snippet,
			'closeFrame' => $closeFrame,
		);
		
		Load::view('historico/views/new.php', $context);
	}
	
	public static function edit(){
		hasPerm('usuario.update');
		
		$historico = HistoricoBO::get($_REQUEST['id']);
		
		$historicoData = array(
			'id'  	      => $historico->getId(),
			'usuario_id'  => $historico->getUsuarioId(),
			'modulo_id'   => $historico->getModuloId(),
			'entidade_id' => $historico->getEntidadeId(),
			'descricao'   => $historico->getDescricao(),
		);
		$form = new FormHistorico($historicoData);
		
		$snippet = array('title' => 'Editar posição');
		$context = array(
	        'template'  => 'iframe',
            'snippet'   => $snippet,
			'historico' => $historico,
			'form'      => $form,
		);

		Load::view('historico/views/edit.php', $context);		
	}	

	public static function update(){
		hasPerm('historico.update');
		postRequired('historico/edit/'.$_REQUEST['id']);
		
		$form = new FormHistorico($_POST);

		$historico = HistoricoBO::get($_REQUEST['id']);
		
		if($form->isValid()){
			try{
                $historico->setId($form->getFields('id')->getValue());    
				$historico->setDescricao($form->getFields('descricao')->getValue());
				
				HistoricoBO::update($historico);

				Message::getInstance()->success('Conteúdo editado com sucesso');
				
				$closeFrame = true;
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Editar posição');
		$context = array(
	        'template'   => 'iframe',
	        'snippet'    => $snippet,
	        'historico'  => $historico,
			'form'       => $form,
			'closeFrame' => $closeFrame,
		);

		Load::view('historico/views/edit.php', $context);
	}

	public static function delete(){
		hasPerm('historico.delete');
		try{
			HistoricoBO::delete($_REQUEST['id']);

			Message::getInstance()->success('Posição deletada com sucesso');
			
		} catch(Exception $e){
			Message::getInstance()->error($e->getMessage());
		}
		
		redirect(Search::restore());
	}	

	public static function _list(){
		$snippet = array('title' => 'Histórico');
		$context = array(
			'snippet'    => $snippet,
			'form' 	     => $form,
			'historicos' => HistoricoBO::getHistoricoByEntidade($_REQUEST['module'], $_REQUEST['id']),
		);

		Load::view('historico/views/_list.php', $context);
	}	
}

