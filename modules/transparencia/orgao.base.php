<?php

abstract class BaseOrgao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.orgao');
        
        $this->hasColumn('id'     	    , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id', 'integer', 11);
		
        $this->hasColumn('exercicio'    , 'integer');
        $this->hasColumn('cod_orgao'    , 'integer');
        $this->hasColumn('nome_orgao'   , 'string' , 80);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao'        , array('local' => 'importacao_id' , 'foreign' => 'id'));
		$this->hasMany('BalanceteDespesa' , array('local' => 'cod_orgao'     , 'foreign' => 'cod_orgao'));
    }
}