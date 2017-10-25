<?php

class FormPublicacao extends Form{

	public function __construct($data=null, $files=null, $args=null){
	    
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;
		
		$usuario = new Text();
		$usuario->setName('usuario');
		$usuario->setLabel('Usuario');
		$usuario->setRequired(true);
		$usuario->setStyle(array('width' => '400px'));
		$usuario->setReadOnly(true);
		$usuario->setValue(Sessao::get('usuario_nome'));
		$this->fields['usuario'] = $usuario;		
		
		/********** Conexão **********/
		$manager = Conn::openConnection(Sessao::get('municipio_db'));
		$query = new Doctrine_Query();
        $query->select("s.*")
              ->from("Secao s")
			  ->where("s.type LIKE 'publicacao'");
		$lista = $query->execute();
		/**********/
		
        $secao_id = new Select();
        $secao_id->setName('secao_id');
        $secao_id->setLabel('Seção');
        $secao_id->setQueryOptions('id', 'secao', $lista, 'Selecione');
        $secao_id->setRequired(true);
        $this->fields['secao_id'] = $secao_id;

		/********** Conexão **********/
		$manager->close();
		Conn::openConnection(DB);
		/**********/
		
		$descricao = new Text();
		$descricao->setName('descricao');
		$descricao->setLabel('Descrição');
		$descricao->setRequired(true);
		$descricao->setStyle(array('width' => '600px'));
		$this->fields['descricao'] = $descricao;
		
		$detalhamento = new Textarea();
		$detalhamento->setName('detalhamento');
		$detalhamento->setLabel('Detalhamento');
		$detalhamento->setRequired(true);
		$detalhamento->setStyle(array('width' => '618px', 'height' => '120px'));
		$this->fields['detalhamento'] = $detalhamento;		

        $status = new Select();
        $status->setName('status');
        $status->setLabel('Status');
        $status->setOptions(array('p' => 'Publicado', 'np' => 'Não Publicado'));
        $status->setRequired(true);
        $this->fields['status'] = $status;
		
		$arquivo = new File();
		$arquivo->setName('arquivo');
		$arquivo->setLabel('Arquivo');
		$arquivo->setUploadParameters($GLOBALS['BASE_DIR'].'uploads/'.Sessao::get('municipio_hash').'/publicacao/', 5000);
		if(!isset($args['arquivo_required'])){
			$arquivo->setRequired(true);
		}
		$this->fields['arquivo'] = $arquivo;
		
		parent::__construct($data, $files);
	}
}

class FormPublicacaoFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('descricao' => 'Descrição'));
		$filter->setStyle(array('width' => '365px'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}
