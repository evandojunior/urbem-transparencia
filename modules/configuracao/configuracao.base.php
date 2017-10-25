<?php

abstract class BaseConfiguracao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('configuracao');
        
		$this->hasColumn('id'		   , 'integer'  ,  11,  array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('municipio_id', 'integer'  ,  11							);
		$this->hasColumn('modulo_id'   , 'integer'  ,  11,  array('notnull' => true));
        $this->hasColumn('alias'	   , 'string'   ,  20,  array('notnull' => true));
        $this->hasColumn('parametro'   , 'string'   , 100,  array('notnull' => true));
        $this->hasColumn('valor'	   , 'string'   , 100,  array('notnull' => true));
        $this->hasColumn('descricao'   , 'string'   , null, array('notnull' => false));
        $this->hasColumn('created'     , 'timestamp', null, array('notnull' => true));
        $this->hasColumn('updated'     , 'timestamp', null, array('notnull' => false));
    }

    public function setUp(){
        parent::setUp();
        
		$this->hasOne('Municipio', array('local' => 'municipio_id' , 'foreign' => 'id'));
		$this->hasOne('Modulo'   , array('local' => 'modulo_id' , 'foreign' => 'id'));
    }
}