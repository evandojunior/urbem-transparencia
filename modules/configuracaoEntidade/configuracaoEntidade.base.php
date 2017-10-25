<?php

abstract class BaseConfiguracaoEntidade extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.configuracao_entidade');
        
        $this->hasColumn('id'     	   , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('entidade_id' , 'integer', 11);
    }

    public function setUp(){
        parent::setUp();
		
		$this->hasOne('Entidade', array('local' => 'entidade_id', 'foreign' => 'cod_entidade'));
    }
}