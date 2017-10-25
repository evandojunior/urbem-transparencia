<?php

abstract class BaseFuncao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.funcao');
        
        $this->hasColumn('id'     		, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id', 'integer', 11);
		
        $this->hasColumn('exercicio'    , 'integer');
        $this->hasColumn('cod_funcao'   , 'integer');
        $this->hasColumn('nome_funcao'	, 'string' , 80);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
		$this->hasMany('BalanceteDespesa' , array('local' => 'cod_funcao' , 'foreign' => 'cod_funcao'));
    }
}