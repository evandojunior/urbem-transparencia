<?php

abstract class BasePessoa extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('pessoa');
        
        $this->hasColumn('id'		          , 'integer', 11	, array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('categoria_id'		  , 'integer', 11);
        $this->hasColumn('nome'				  , 'string' , 100	, array('notnull' => true));
        $this->hasColumn('email'			  , 'string' , 100);
        $this->hasColumn('ddd_celular'		  , 'string' , 2);
        $this->hasColumn('telefone_celular'	  , 'string' , 10);
        $this->hasColumn('ddd_comercial'	  , 'string' , 2);
        $this->hasColumn('telefone_comercial' , 'string' , 10);
		$this->hasColumn('facebook' 		  , 'string' , 100);
		$this->hasColumn('twitter' 		  	  , 'string' , 100);
        $this->hasColumn('observacao'		  , 'string' , null);
		$this->hasColumn('created'		  	  , 'timestamp' , array('notnull' => true));
		$this->hasColumn('updated'		  	  , 'timestamp' , null);
    }

    public function setUp(){
        parent::setUp();
        $this->hasOne('Usuario'	    , array('local' => 'id'				, 'foreign' => 'pessoa_id'));
        $this->hasOne('Categoria'	, array('local' => 'categoria_id'	, 'foreign' => 'id'));
        $this->hasOne('Endereco'	, array('local' => 'id'	            , 'foreign' => 'pessoa_id'));
        $this->hasOne('Usuario'	    , array('local' => 'id'     	    , 'foreign' => 'pessoa_id'));
        $this->hasOne('PessoaPf'	, array('local' => 'id'				, 'foreign' => 'pessoa_id'));
        $this->hasOne('PessoaPj'	, array('local' => 'id'				, 'foreign' => 'pessoa_id'));
    }
}

abstract class BasePessoaPf extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('pessoa_fisica');
        
        $this->hasColumn('id'           		, 'integer'	, 11 , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('pessoa_id'			, 'integer'	, 11 , array('notnull' => true));
		$this->hasColumn('rg'			   		, 'string'	, 20);
		$this->hasColumn('orgao_emissor'   		, 'string'	, 20);
        $this->hasColumn('cpf'			   		, 'string'	, 20);
        $this->hasColumn('ddd_residencial' 		, 'string'	, 2);
        $this->hasColumn('telefone_residencial' , 'string'	, 9);        
    }

    public function setUp(){
        parent::setUp();
		
        $this->hasOne('Pessoa', array('local' => 'pessoa_id', 'foreign' => 'id'));
    }
}

abstract class BasePessoaPj extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('pessoa_jurica');
        
        $this->hasColumn('id'           		, 'integer'	, 11 , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('pessoa_id'			, 'integer'	, 11 , array('notnull' => true));
		$this->hasColumn('cnpj'			   		, 'string'	, 20);
		$this->hasColumn('inscricao_estadual'	, 'string'	, 20);
        $this->hasColumn('site'					, 'string'	, 150);
    }

    public function setUp(){
        parent::setUp();
        $this->hasOne('Pessoa', array('local' => 'pessoa_id', 'foreign' => 'id'));
    }
}