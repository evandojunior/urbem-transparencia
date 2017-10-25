<?php

class FormGrupo extends Form{

	public function __construct($data=null, $files=null){
	    
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;
		
		$grupo = new Text();
		$grupo->setName('grupo');
		$grupo->setLabel('Grupo');
		$grupo->setRequired(true);
		$grupo->setStyle(array('width' => '400px'));
		$this->fields['grupo'] = $grupo;

		$alias = new Text();
		$alias->setName('alias');
		$alias->setLabel('Alias');
		$alias->setRequired(true);
		$this->fields['alias'] = $alias;
		
		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("a.*")
              ->from("_Acao a");
		$lista = $query->execute();
		/**********/
		
		$acao = new MultiCheckbox();
		$acao->setName('acao');
		$acao->setLabel('Lista de permissões');
		$acao->setQueryOptions('id', 'alias', $lista);
		$acao->setRequired(true);
		$this->fields['acao'] = $acao;
		
		parent::__construct($data, $files);
	}

	public function cleanAlias(){
		if(Validator::validateUnique('Grupo', 'alias', $this->fields['alias']->getValue(), $this->fields['id']->getValue())){
			return true;
		} else {
			$this->fields['alias']->setError('Já existe um grupo cadastrado com este alias!');
			return false;
		}
	}	
}

class FormGrupoFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('grupo' => 'Grupo', 'alias' => 'Alias'));
		$filter->setStyle(array('width' => '365px'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}