<?php

class FormHistorico extends Form{

	public function __construct($data=null, $files=null){
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;

		$usuario = new Text();
		$usuario->setName('usuario');
		$usuario->setLabel('Usuário');
		$usuario->setRequired(false);
		$usuario->setReadOnly(true);
		$usuario->setValue($_SESSION['usuario']['nome']);
		$this->fields['usuario'] = $usuario;		
		
		$descricao = new Textarea();
		$descricao->setName('descricao');
		$descricao->setLabel('Descrição');
		$descricao->setRequired(true);
		$descricao->setStyle(array('width' => '400px'));
		$this->fields['descricao'] = $descricao;

		parent::__construct($data, $files);
	}	
}

class FormHistoricoFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('tags' => 'Tags', 
                                  'descricao' => 'Descricao', 
                                  'usuario_nome' => 'Usuário', 
                                  'modulo_nome' => 'Módulo'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}
