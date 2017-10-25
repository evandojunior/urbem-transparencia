<?php

abstract class BaseEntidade extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.entidade');
        
        $this->hasColumn('id'     		 , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' , 'integer', 11);
	    
        $this->hasColumn('cod_entidade'  , 'integer');
        $this->hasColumn('nome_entidade' , 'string' , 160);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao'          , array('local' => 'importacao_id', 'foreign' => 'id'));
		$this->hasOne('ConfiguracaoEntidade', array('local' => 'cod_entidade' , 'foreign' => 'entidade_id'));
    }
}