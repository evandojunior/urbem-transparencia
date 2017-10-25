<?php

abstract class BasePublicacao extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('publicacao.publicacao');
    	
		$this->hasColumn('id'          , 'integer'  , 11  , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('usuario   '  , 'string'   , 150 , array('notnull' => true));
		$this->hasColumn('secao_id'    , 'integer'  , 11  , array('notnull' => true));
		$this->hasColumn('descricao'   , 'string'   , 150 , array('notnull' => true));
		$this->hasColumn('detalhamento', 'string'   , null, array('notnull' => true));
		$this->hasColumn('status'      , 'string'   , 2   , array('notnull' => true));
		$this->hasColumn('arquivo'     , 'string'   , 150 , array('notnull' => true));
		$this->hasColumn('created'	   , 'timestamp', null, array('notnull' => true));
		$this->hasColumn('updated'	   , 'timestamp', null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasOne('Secao', array('local' => 'secao_id'   , 'foreign' => 'id'));
    }
}
