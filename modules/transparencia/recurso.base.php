<?php

abstract class BaseRecurso extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.recurso');
        
        $this->hasColumn('id'     	    , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id', 'integer', 11);
		
        $this->hasColumn('cod_recurso'  , 'integer');
        $this->hasColumn('nome_recurso' , 'string' , 80);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id'       , 'foreign' => 'id'));
		$this->hasMany('BalanceteDespesa' , array('local' => 'cod_recurso'  , 'foreign' => 'cod_recurso'));
    }
}