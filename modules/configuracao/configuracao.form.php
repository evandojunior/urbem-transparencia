<?php

class FormConfiguracao extends Form{

	public function __construct($data=null, $files=null){
	    
		$id = new Hidden();
		$id->setName('id');
		$id->setRequired(false);
		$this->fields['id'] = $id;

		/********** Consulta **********/
		$query = new Doctrine_Query();
        $query->select("m.*, CONCAT(m.nome, '/' ,u.sigla) as nome")
              ->from("Municipio m")
			  ->innerJoin("m.UF u")
			  ->orderBy("u.sigla, m.nome");
		$lista = $query->execute();
		/**********/
		
        $municipio_id = new Select();
        $municipio_id->setName('municipio_id');
        $municipio_id->setLabel('Município');
        $municipio_id->setQueryOptions('id', 'nome', $lista, 'Selecione');
        $this->fields['municipio_id'] = $municipio_id;
		
		/********** Conexão **********/
		$query = new Doctrine_Query();
        $query->select("m.id, m.modulo")
              ->from("Modulo m")
			  ->orderBy("m.modulo");
		$lista = $query->execute();
		/**********/
		
        $modulo_id = new Select();
        $modulo_id->setName('modulo_id');
        $modulo_id->setLabel('Módulo');
        $sql = 'SELECT * FROM modulo';
        $modulo_id->setQueryOptions('id', 'modulo', $lista, 'Selecione');
        $modulo_id->setRequired(true);
        $this->fields['modulo_id'] = $modulo_id;		    
	    
		$alias = new Text();
		$alias->setName('alias');
		$alias->setLabel('Alias');
		$alias->setRequired(true);
		$this->fields['alias'] = $alias;
		
		$parametro = new Text();
		$parametro->setName('parametro');
		$parametro->setLabel('Parametro');
		$parametro->setRequired(true);
		$this->fields['parametro'] = $parametro;
		
		$valor = new Text();
		$valor->setName('valor');
		$valor->setLabel('Valor');
		$valor->setRequired(true);
		$this->fields['valor'] = $valor;
		
		$descricao = new Textarea();
		$descricao->setName('descricao');
		$descricao->setLabel('Descrição');
		$descricao->setStyle(array('width' => '400px'));
		$this->fields['descricao'] = $descricao;

		parent::__construct($data, $files);
	}

    public function cleanAlias(){
        if(Validator::validateUnique('Configuracao', 'alias', $this->fields['alias']->getValue(), $this->fields['id']->getValue())){
            return true;
        } else {
            $this->fields['alias']->setError('Já existe um parâmetro cadastrado com este alias!');
            return false;
        }
    }
	
    public function cleanMunicipio_Id(){
        if ($this->fields['municipio_id']->getValue() == ''){
			$this->fields['municipio_id']->setValue(null);
		}
        
		return true;
    }	
}

class FormConfiguracaoFilter extends Form{
	
	public function __construct($data=null, $files=null){
		$q = new Text();
		$q->setName('q', true);
		$q->setLabel('Digite o que deseja pesquisar');
		$q->setStyle(array('width' => '500px'));
		$this->fields['q'] = $q;

		$filter = new Select();
		$filter->setName('filter', true);
		$filter->setLabel('Selecione o tipo de filtro');
		$filter->setOptions(array('municipio' => 'Município',
								  'parametro' => 'Parâmetro',
								  'alias' => 'Alias',
								  'Valor' => 'valor')
							);
		$filter->setStyle(array('width' => '365px'));
		$this->fields['filter'] = $filter;

		parent::__construct($data, $files);
	}
}