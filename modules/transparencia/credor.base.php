<?php

abstract class BaseCredor extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.credor');
        
        $this->hasColumn('id'     		  , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id'  , 'integer', 11);

        $this->hasColumn('cod_credor' 	  , 'integer');
        $this->hasColumn('nome_credor'	  , 'string' , 60);
		$this->hasColumn('cnpj_cpf_credor', 'varchar', 14);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}
