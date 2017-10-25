<?php

abstract class BaseLog extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('log');
        
		$this->hasColumn('id'          , 'integer'  , 4   , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('municipio_id', 'integer'  , 4   , array('notnull' => true));
		$this->hasColumn('remessa'     , 'string'   , 100 , array('notnull' => true));
        $this->hasColumn('arquivo'     , 'string'   , 100 , array('notnull' => true));
        $this->hasColumn('mensagem'    , 'string'   , null, array('notnull' => true));
        $this->hasColumn('excecao'     , 'string'   , null, array('notnull' => true));
        $this->hasColumn('created'     , 'timestamp', null, array('notnull' => true));
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Municipio' , array('local' => 'municipio_id' , 'foreign' => 'id'));
    }
}
