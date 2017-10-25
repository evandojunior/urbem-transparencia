<?php

class FormCategoria extends Form{

	public function __construct($data=null, $files=null){
	    
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;
		
		$categoria = new Text();
		$categoria->setName('categoria');
		$categoria->setLabel('Categoria');
		$categoria->setRequired(true);
		$categoria->setStyle(array('width' => '400px'));
		$this->fields['categoria'] = $categoria;

		$alias = new Text();
		$alias->setName('alias');
		$alias->setFormat('alias');
		$alias->setLabel('Alias');
		$alias->setRequired(true);
		$alias->setStyle(array('width' => '400px'));
		$this->fields['alias'] = $alias;

        $parent_id = new Select();
        $parent_id->setName('parent_id');
        $parent_id->setLabel('Parent ID');
        $sql = 'SELECT * FROM categoria where parent_id = NULL';
        $parent_id->setQueryOptions('id', 'categoria', $sql, 'Selecione');
        $parent_id->setRequired(true);
        $this->fields['parent_id'] = $parent_id;
		
		parent::__construct($data, $files);
	}
}

class FormCategoriaFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('categoria' => 'Categoria', 'alias' => 'Alias'));
		$filter->setStyle(array('width' => '365px'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}
