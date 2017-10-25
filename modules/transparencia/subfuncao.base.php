<?php

abstract class BaseSubfuncao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.subfuncao');
        
        $this->hasColumn('id'     		 , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' , 'integer', 11);
        
        $this->hasColumn('exercicio'	 , 'integer');
        $this->hasColumn('cod_subfuncao' , 'integer');
        $this->hasColumn('nome_subfuncao', 'string' , 80);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}