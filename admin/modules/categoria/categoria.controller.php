<?php

Load::_require('core/dto.class.php');
Load::_require('core/search.class.php');

Load::_require('modules/categoria/categoria.base.php');
Load::_require('modules/categoria/categoria.class.php');
Load::_require('modules/categoria/categoria.bo.php');
Load::_require('modules/categoria/categoria.form.php');


class CategoriaController extends Controller{

	public static function index(){
		hasPerm('categoria.index');
		
		$search = new Search();
		$search->setQ($_REQUEST['q']);
		$search->setFilter($_REQUEST['filter']);
		$search->setOrder($_REQUEST['order']);
		$search->setDirection($_REQUEST['direction']);
		$search->setPage($_REQUEST['page']);
		$search->setMax(25);

		$categoriaDTO = CategoriaBO::filter($search);

		$form = new FormCategoriaFilter($_GET);

		$snippet = array('title' => 'Pesquisar lista de mailing', 'pager' => $categoriaDTO->getSearch()->getPager());
		$context = array(
			'snippet' 	=> $snippet,
			'form' 		=> $form,
			'categoria' 		=> $categoriaDTO->getObj(),
		);

		Load::view('categoria/views/index.php', $context);
	}
	
	public static function show(){
		hasPerm('categoria.show');
		
		$snippet = array('title' => 'Consultar categoria');
		$context = array(
			'snippet'   => $snippet,
			'categoria' => CategoriaBO::get($_REQUEST['id']),
		);

		Load::view('categoria/views/show.php', $context);
	}	
	
	public static function _new(){
		hasPerm('categoria.create');
		
		$form = new FormCategoria();

		$snippet = array('title' => 'Adicionar categoria');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);
	
		Load::view('categoria/views/new.php', $context);			
	}	

	public static function create(){
		hasPerm('categoria.create');
		postRequired('categoria/new');
		
		$form = new FormCategoria($_POST);

		if($form->isValid()){
			try{
				$categoria = new Categoria();
                $categoria->setCategoria($form->getFields('categoria')->getValue());
				$categoria->setAlias($form->getFields('alias')->getValue());
				$categoria->setParentId($form->getFields('parent_id')->getValue());
                              
				$categoria = CategoriaBO::create($categoria);

				Message::getInstance()->success('Categoria adicionada com sucesso');
				
				$form->clean();

				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Adicionar categoria');
		$context = array(
			'snippet' => $snippet,
			'form' 	  => $form,
		);

		Load::view('categoria/views/new.php', $context);
	}
	
	public static function edit(){
		hasPerm('categoria.update');
		
		$categoria = CategoriaBO::get($_REQUEST['id']);
		
		$categoriaData = array(
			'id'  	    => $categoria->getId(),
            'categoria' => $categoria->getCategoria(),
			'alias'     => $categoria->getAlias(),
			'parent_id' => $categoria->getParentId(),
		);
		$form = new FormCategoria($categoriaData);
		
		$snippet = array('title' => 'Editar categoria');
		$context = array(
			'snippet'   => $snippet,
			'form'      => $form,
			'categoria' => $categoria,
		);

		Load::view('categoria/views/edit.php', $context);		
	}	

	public static function update(){
		hasPerm('categoria.update');
		postRequired('categoria/edit/'.$_REQUEST['id']);
		
		$categoria = CategoriaBO::get($_REQUEST['id']);
		
		$form = new FormCategoria($_POST);
		
		if($form->isValid()){
			try{
                $categoria->setCategoria($form->getFields('categoria')->getValue());
				$categoria->setAlias($form->getFields('alias')->getValue());
				$categoria->setParentId($form->getFields('parent_id')->getValue());
				
				$categoria = CategoriaBO::update($categoria, $conn);

				Message::getInstance()->success('Categoria editada com sucesso');
				
				if($_POST['save']) {
					redirect(Search::restore());
				}
				
			} catch(Exception $e){
				Message::getInstance()->error($e->getMessage());
			}
		}
		
		$snippet = array('title' => 'Editar categoria');
		$context = array(
			'snippet'   => $snippet,
			'form'      => $form,
			'categoria' => $categoria,
		);

		Load::view('categoria/views/edit.php', $context);
	}
	
	public static function delete(){
		hasPerm('categoria.delete');
		try{
			CategoriaBO::delete($_REQUEST['id']);

			Message::getInstance()->success('Categoria deletada com sucesso');
			
		} catch(Exception $e){
			Message::getInstance()->error($e->getMessage());
		}
		
		redirect(Search::restore());
	}
}
