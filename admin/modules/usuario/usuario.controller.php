<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');

Load::_require('modules/pessoa/pessoa.base.php');
Load::_require('modules/pessoa/pessoa.class.php');
Load::_require('modules/pessoa/pessoa.bo.php');

Load::_require('modules/usuario/usuario.base.php');
Load::_require('modules/usuario/usuario.class.php');
Load::_require('modules/usuario/usuario.bo.php');
Load::_require('modules/usuario/usuario.form.php');

Load::_require('modules/grupo/grupo.base.php');
Load::_require('modules/grupo/grupo.class.php');
Load::_require('modules/grupo/grupo.bo.php');
Load::_require('modules/grupo/acao.base.php');
Load::_require('modules/grupo/acao.class.php');

Load::_require('modules/endereco/endereco.base.php');
Load::_require('modules/endereco/endereco.class.php');
Load::_require('modules/endereco/endereco.bo.php');
Load::_require('modules/endereco/endereco.form.php');


class UsuarioController extends Controller{

	public static function index(){
		hasPerm('usuario.index');
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$usuarioDTO = UsuarioBO::filter($search);
		$form = new FormUsuarioFilter($_GET);

		$snippet = array('title' => 'Pesquisar Usuários', 'pager' => $usuarioDTO->getSearch()->getPager());
		$context = array(
			'snippet' 	=> $snippet,
			'form' 		=> $form,
			'usuarios' 	=> $usuarioDTO->getObj(),
		);

		Load::view('usuario/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('usuario.show');
		
		$snippet = array('title' => 'Consultar Usuário');
		$context = array(
			'snippet'  => $snippet,
			'usuario'  => UsuarioBO::getByPessoa($_REQUEST['id']),
		);

		Load::view('usuario/views/show.php', $context);
	}
	
	public static function _new(){
		hasPerm('usuario.create');
		
		$form      = new FormUsuario();
		$formSenha = new FormSenha();
		
		$snippet = array('title' => 'Cadastrar Usuário');
		$context = array(
	        'snippet'      => $snippet,
			'form'         => $form,
			'formSenha'    => $formSenha,
		);

		Load::view('usuario/views/new.php', $context);
	}
	
	public static function create(){
		hasPerm('usuario.create');
		postRequired('usuario/new');
		
		$form         = new FormUsuario($_POST);
		$formSenha    = new FormSenha($_POST);

		if(($form->isValid())&&($formSenha->isValid())){
			try{
                $conn = Doctrine_Manager::connection();
                $conn->beginTransaction();

                $pessoa = new Pessoa();
                $pessoa->setNome($form->getFields('nome')->getValue());
                $pessoa->setEmail($form->getFields('email')->getValue());

                $pessoa = PessoaBO::create($pessoa, $conn);
                
                $usuario = new Usuario();
				$usuario->setPessoaId($pessoa->getId());
				$usuario->setGrupoId($form->getFields('grupo_id')->getValue());
				$usuario->setMunicipioId($form->getFields('municipio_id')->getValue() != '' ? $form->getFields('municipio_id')->getValue() : null);
				$usuario->setSenha($formSenha->getFields('senha')->getValue());
				
				UsuarioBO::create($usuario, $conn);

                $conn->commit();
				Message::getInstance()->success('Usuário cadastrado com sucesso');
				
				$form->clean();
				$formSenha->clean();

				if($_POST['save']){
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
                $conn->rollback();
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Cadastrar Usuário');
		$context = array(
			'form' 		=> $form,
			'formSenha' => $formSenha,
			'snippet'   => $snippet,
		);

		Load::view('usuario/views/new.php', $context);
	}

	public static function edit(){
		hasPerm('usuario.update');
		
		$usuario = UsuarioBO::getByPessoa($_REQUEST['id']);

		$usuarioData = array(
			'id'  		   => $usuario->getId(),
			'nome'	  	   => $usuario->getPessoa()->getNome(),
			'email' 	   => $usuario->getPessoa()->getEmail(),
			'grupo_id'     => $usuario->getGrupoId(),
			'municipio_id' => $usuario->getMunicipioId(),
			'status'	   => $usuario->getStatus() == 1 ? "Usuário ativo" : "Usuário desativado"
		);

		$form 	   = new FormUsuario($usuarioData);
		$formSenha = new FormSenha();
		
		$snippet = array('title' => 'Editar Usuário');
		$context = array(
			'snippet'      => $snippet,
			'form'         => $form,
			'formSenha'	   => $formSenha,
	        'usuario'      => $usuario,
		);

		Load::view('usuario/views/edit.php', $context);		
	}

	public static function update(){
		hasPerm('usuario.update');
		postRequired('usuario/edit/'.$_REQUEST['id']);
		
		$usuario = UsuarioBO::get($_REQUEST['id']);
		$pessoa  = $usuario->getPessoa();
		
		$form      = new FormUsuario($_POST);
		$formSenha = new FormSenha($_POST, null, $required=false);
			
		if($form->isValid()){
			try{
                $conn = Doctrine_Manager::connection();
                $conn->beginTransaction();

				$pessoa->setNome($form->getFields('nome')->getValue());
				$pessoa->setEmail($form->getFields('email')->getValue());
				$usuario->setGrupoId($form->getFields('grupo_id')->getValue());
				$usuario->setMunicipioId($form->getFields('municipio_id')->getValue() != '' ? $form->getFields('municipio_id')->getValue() : null);

				if($formSenha->getFields('senha')->getValue() != ''){
					if($formSenha->isValid()){
						$usuario->setSenha($formSenha->getFields('senha')->getValue());
					} else {
						throw new Exception('A senha não está preenchida corretamente');
					}
				}

				PessoaBO::update($pessoa, $conn);
				UsuarioBO::update($usuario, $conn);

                $conn->commit();
                Message::getInstance()->success('Usuário editado com sucesso');

				if($_POST['save']){
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
                $conn->rollback();
				Message::getInstance()->error($e->getMessage());
			}
		}

		$snippet = array('title' => 'Editar Usuário');
		$context = array(
			'snippet'      => $snippet,
			'form'         => $form,
			'formSenha'    => $formSenha,
	        'usuario'      => $usuario,
		);

		Load::view('usuario/views/edit.php', $context);
		
	}

	public static function delete(){
		hasPerm('usuario.delete');
		try{
			$conn = Doctrine_Manager::connection();
			$conn->beginTransaction();			
			
		    $usuario = UsuarioBO::getByPessoa($_REQUEST['id']);
			UsuarioBO::delete($usuario->getId(), $conn);

			$conn->commit();
			Message::getInstance()->success('Usuário deletado com sucesso');
			
		} catch(Exception $e){
			$conn->rollback();
			Message::getInstance()->error($e->getMessage());
		}		
		
		redirect(Search::restore());
	}	
	
	public static function setConta(){
		$usuarioData = array(
			'id'           => Sessao::get('usuario_id'),
			'pessoa_id'    => Sessao::get('usuario_pessoa_id'),
			'nome'         => Sessao::get('usuario_nome'),
			'email'        => Sessao::get('usuario_email'),
	        'grupo_id'     => Sessao::get('usuario_grupo_id'),
		);
		
		$form 	   = new FormUsuario($usuarioData, null, array('nome_readonly' => true, 'email_readonly' => true));
		$formSenha = new FormSenha();
		
		$snippet = array('title' => 'Meus Dados');
		$context = array(
			'snippet' 	=> $snippet,
			'form' 		=> $form,
			'formSenha'	=> $formSenha,
		);
		
		Load::view('usuario/views/_conta.php', $context);
	}
	
	public static function updateConta(){
		postRequired('setConta');
		
		$args = array('grupo_id_required' => false,
			          'municipio_id_required'=>false,
			          'nome_readonly'=>true,
			          'email_readonly'=>true,
				);
		
		$form      = new FormUsuario($_POST, null, $args);
		$formSenha = new FormSenha($_POST, null, array('required' => false));
		$usuario   = UsuarioBO::getByPessoa(Sessao::get('usuario_pessoa_id'));

		if($form->isValid()){
			try{
			    $conn = Doctrine_Manager::connection();
			    $conn->beginTransaction();
			    
				if($formSenha->getFields('senha')->getValue() != ''){
					if($formSenha->isValid()){
						$usuario->setSenha($formSenha->getFields('senha')->getValue());
					} else {
						throw new Exception('A senha não está preenchida corretamente');
					}
				}
				
				UsuarioBO::update($usuario, $conn);
				
				$conn->commit();
				Message::getInstance()->success('Seus dados foram editado com sucesso');

			} catch(Exception $e){
			    $conn->rollback();
				Message::getInstance()->error($e->getMessage());
			}
		}

		$snippet = array('title' => 'Meus Dados');
		$context = array(
			'snippet' 	=> $snippet,
			'form' 		=> $form,
			'formSenha'	=> $formSenha,
			'alias'	    => $alias,
		);

		Load::view('usuario/views/_conta.php', $context);
	}	
	
	public static function setLogin(){
		$form = new FormLogin();
		
		$snippet = array('title' => 'Urbem - Transparência');
		$context = array(
			'template' => 'login',
			'snippet'  => $snippet,
			'form'	   => $form,
		);

		Load::view('usuario/views/_login.php', $context);
	}

	public static function login(){
		postRequired('setLogin');

		$form = new FormLogin($_POST);
		
		if($form->isValid()){
			try{
				$usuario = UsuarioBO::auth($form->getFields('email')->getValue(), $form->getFields('senha')->getValue(), true);
				UsuarioBO::login($usuario);

				redirect('home');
				
			} catch (Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Urbem - Transparência');
		$context = array(
			'template' => 'login',
			'snippet'  => $snippet,
			'form'	   => $form,
		);	
		
		Load::view('usuario/views/_login.php', $context);
	}

	public static function logout(){
		UsuarioBO::logout();
		
		redirect('setLogin');
	}
	
	public static function editStatus(){
		hasPerm('usuario.editStatus');
		
		$pessoa = PessoaBO::get($_REQUEST['id']);
		UsuarioBO::editStatus($pessoa->getUsuario()->getId());
		
		Message::getInstance()->success('Status alterado com sucesso!');
		
		redirect('usuario/edit/'.$_REQUEST['id']);
	}
}