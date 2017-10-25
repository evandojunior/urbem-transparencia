<?php

abstract class BaseModulo extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('modulo');
    	
		$this->hasColumn('id'     , 'integer' , 4  , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('modulo' , 'string'  , 30 , array('notnull' => true));
		$this->hasColumn('alias'  , 'string'  , 30 , array('notnull' => true));
    }

    public function setUp(){
        parent::setUp();

        $this->hasMany('Configuracao' , array('local' => 'id', 'foreign' => 'modulo_id'));
        $this->hasMany('Historico'    , array('local' => 'id', 'foreign' => 'modulo_id'));
    }
}
