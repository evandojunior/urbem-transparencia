<?php

abstract class BaseUF extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('uf');
    	
		$this->hasColumn('id'		 , 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('nome'		 , 'string', 30, array('notnull' => true));
        $this->hasColumn('sigla'	 , 'string', 2, array('notnull' => true));
        $this->hasColumn('disponivel', 'boolean', 1 , array('notnull' => true));
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasMany('Municipio', array('local' => 'id', 'foreign' => 'uf_id'));
    }
}

abstract class BaseMunicipio extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('municipio');
        
		$this->hasColumn('id'           , 'integer', 4 , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('uf_id'        , 'integer', 4 , array('notnull' => true));
        $this->hasColumn('nome'         , 'string' , 30, array('notnull' => true));
		$this->hasColumn('alias'        , 'string' , 30, array('notnull' => true));
        $this->hasColumn('disponivel'   , 'boolean', 1 , array('notnull' => true));
		$this->hasColumn('hash'		    , 'string', 100);
		$this->hasColumn('db'	 	    , 'string', 100);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('UF', array('local' => 'uf_id', 'foreign' => 'id'));
        $this->hasMany('CEP', array('local' => 'id', 'foreign' => 'municipio_id'));
    }
}

class BaseCEP extends Doctrine_Record{

    public function setTableDefinition(){
		$this->setTableName('cep');

		$this->hasColumn('id'           , 'integer', 11 , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('municipio_id' , 'integer', 11 , array('notnull' => true));
        $this->hasColumn('bairro'       , 'string' , 50 , array('notnull' => true));
        $this->hasColumn('logradouro'   , 'string' , 250, array('notnull' => true));
        $this->hasColumn('numero_cep'   , 'integer', 8  , array('notnull' => true));
    }

    public function setUp(){
       parent::setUp();

       $this->hasOne('Municipio', array('local' => 'municipio_id', 'foreign' => 'id'));
       $this->hasMany('Endereco', array('local' => 'id', 'foreign' => 'cep_id'));
    }    
}
class BaseEndereco extends Doctrine_Record{

    public function setTableDefinition(){
		$this->setTableName('endereco');

		$this->hasColumn('id'         , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('cep_id'     , 'integer', 11, array('notnull' => false));
        $this->hasColumn('numero'     , 'string' , 30, array('notnull' => true));
        $this->hasColumn('complemento', 'string' , 150);
        $this->hasColumn('tipo'       , 'string' , 50);
    }

    public function setUp(){
       parent::setUp();

       $this->hasOne('CEP', array('local' => 'cep_id', 'foreign' => 'id'));
       $this->hasMany('PessoaEndereco', array('local' => 'id', 'foreign' => 'endereco_id'));
       $this->hasMany('Locacao', array('local' => 'id', 'foreign' => 'endereco_id'));
    }    
}
