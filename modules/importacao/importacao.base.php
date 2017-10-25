<?php

abstract class BaseImportacao extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('transparencia.importacao');
    	
        $this->hasColumn('id'     	        , 'integer'  , 4   , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('timestamp'        , 'timestamp', null);
        $this->hasColumn('exercicio'        , 'integer'  , null);
        $this->hasColumn('timestamp_geracao', 'timestamp', null);
        $this->hasColumn('data_limite_dado' , 'date'     , null);
        $this->hasColumn('usuario'          , 'string'   , 60  );
    }

    public function setUp(){
        parent::setUp();
    }
}
