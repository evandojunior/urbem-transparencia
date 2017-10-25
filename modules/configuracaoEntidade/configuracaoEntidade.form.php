<?php

class FormConfiguracaoEntidade extends Form{
	
	public function __construct($data=null, $files=null, $args=null){
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;
		
		/********** Conexão **********/
		$query = new Doctrine_Query();
        $query->select("e.cod_entidade, e.nome_entidade")
              ->from("Entidade e")
			  ->orderBy("e.nome_entidade");
	 
		$lista = $query->execute();
		/**********/
		
        $entidade_id = new MultiCheckbox();
        $entidade_id->setName('entidade_id');
        $entidade_id->setLabel('Entidades selecionadas');
        $entidade_id->setQueryOptions('cod_entidade', 'nome_entidade', $lista);
        $entidade_id->setRequired(true);
        $this->fields['entidade_id'] = $entidade_id;

		parent::__construct($data, $files);
	}
}