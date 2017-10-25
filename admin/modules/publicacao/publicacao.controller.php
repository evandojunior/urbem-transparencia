<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');
Load::_require('core/conn.class.php');
Load::_require('core/upload.class.php');

Load::_require('modules/publicacao/secao.base.php');
Load::_require('modules/publicacao/secao.class.php');
Load::_require('modules/publicacao/secao.bo.php');

Load::_require('modules/publicacao/publicacao.base.php');
Load::_require('modules/publicacao/publicacao.class.php');
Load::_require('modules/publicacao/publicacao.bo.php');
Load::_require('modules/publicacao/publicacao.form.php');


class PublicacaoController extends Controller{

	public static function index(){
		hasPerm('publicacao.index');
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$publicacaoDTO = PublicacaoBO::filter($search, 'getAll', $_REQUEST);
		$form 		   = new FormPublicacaoFilter($_REQUEST);

		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/
		
		$snippet = array('title' => 'Pesquisar Publicações', 'pager' => $publicacaoDTO->getSearch()->getPager());
		$context = array(
			'snippet' 	  => $snippet,
			'form' 		  => $form,
			'publicacoes' => $publicacaoDTO->getObj(),
		);

		Load::view('publicacao/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('publicacao.show');
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/
		
		$publicacao = PublicacaoBO::get($_REQUEST['id']);
		
		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/
		
		$snippet = array('title' => 'Consultar Publicação');
		$context = array(
			'snippet'    => $snippet,
			'publicacao' => $publicacao,
		);

		Load::view('publicacao/views/show.php', $context);
	}	
	
	public static function _new(){
		hasPerm('publicacao.create');
		
		$form = new FormPublicacao();
		
		$snippet = array('title' => 'Adicionar Publicação');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);
	
		Load::view('publicacao/views/new.php', $context);
	}	

	public static function create(){
		hasPerm('publicacao.create');
		postRequired('publicacao/new');
		
		$form = new FormPublicacao($_POST, $_FILES);

		if($form->isValid()){
			try{
				/********** Conexão **********/
				$manager = Conn::openConnection(Sessao::get('municipio_db'));
				/**********/
				
				$publicacao = new Publicacao();
                $publicacao->setUsuario(Sessao::get('usuario_nome'));
				$publicacao->setSecaoId($form->getFields('secao_id')->getValue());
				$publicacao->setDescricao($form->getFields('descricao')->getValue());
				$publicacao->setDetalhamento($form->getFields('detalhamento')->getValue());
				$publicacao->setStatus($form->getFields('status')->getValue());
				
				$filename = $form->getFields('arquivo')->executeUpload(true)->getFilename();
				$publicacao->setArquivo($filename);
				$publicacao = PublicacaoBO::create($publicacao, $manager);
				
				/********** Conexão **********/
				$manager->close();
			    Conn::openConnection(DB);
				/**********/
				
				Message::getInstance()->success('Publicação adicionada com sucesso');
				
				$form->clean();

				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Adicionar Publicação');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);

		Load::view('publicacao/views/new.php', $context);
	}
	
	public static function edit(){
		hasPerm('publicacao.update');
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/
		
		$publicacao = PublicacaoBO::get($_REQUEST['id']);
		
		$publicacaoData = array(
			'id'  	       => $publicacao->getId(),
            'usuario'      => $publicacao->getUsuario(),
			'secao_id'     => $publicacao->getSecaoId(),
			'descricao'    => $publicacao->getDescricao(),
			'detalhamento' => $publicacao->getDetalhamento(),
			'status' 	   => $publicacao->getStatus(),
		);
		$form = new FormPublicacao($publicacaoData, null, array('arquivo_required' => false));

		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/
		
		$snippet = array('title' => 'Editar Publicação');
		$context = array(
			'snippet'    => $snippet,
			'form'       => $form,
			'publicacao' => $publicacao,
		);

		Load::view('publicacao/views/edit.php', $context);		
	}

	public static function update(){
		hasPerm('publicacao.update');
		postRequired('publicacao/edit/'.$_REQUEST['id']);
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		/**********/
		
		$publicacao = PublicacaoBO::get($_REQUEST['id'], null, array('arquivo_required' => false));
		$form 		= new FormPublicacao($_POST, $_FILES, array('arquivo_required' => false));
		
		if($form->isValid()){
			try{
                $publicacao->setUsuario(Sessao::get('usuario_nome'));
				$publicacao->setSecaoId($form->getFields('secao_id')->getValue());
				$publicacao->setDescricao($form->getFields('descricao')->getValue());
				$publicacao->setDetalhamento($form->getFields('detalhamento')->getValue());
				$publicacao->setStatus($form->getFields('status')->getValue());
				
				$filename = $form->getFields('arquivo')->executeUpload(true)->getFilename();
				if($filename != ''){
					$publicacao->setArquivo($filename);
				}

				$publicacao = PublicacaoBO::update($publicacao, $conn);

				Message::getInstance()->success('Publicação editada com sucesso');

				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/
		
		$snippet = array('title' => 'Editar Publicação');
		$context = array(
			'snippet'   => $snippet,
			'form'      => $form,
			'publicacao' => $publicacao,
		);

		Load::view('publicacao/views/edit.php', $context);
	}
	
	public static function delete(){
		hasPerm('publicacao.delete');
		try{
			/********** Conexão **********/
			$manager = Conn::openConnection(Sessao::get('municipio_db'));
			/**********/			
			
			PublicacaoBO::delete($_REQUEST['id']);

			/********** Conexão **********/
			$manager->close();
			Conn::openConnection(DB);
			/**********/

			Message::getInstance()->success('Publicação deletada com sucesso');
			
		} catch(Exception $e){
			Message::getInstance()->error($e->getMessage());
		}
		
		redirect(Search::restore());
	}
}
