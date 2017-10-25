<?php

abstract class BaseSecao extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('publicacao.secao');
    	
		$this->hasColumn('id'        , 'integer'   , 4  , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('type'      , 'string'    , 30 , array('notnull' => true));
		$this->hasColumn('alias'     , 'string'    , 30 , array('notnull' => true));
		$this->hasColumn('secao'     , 'string'    , 100, array('notnull' => true));
		$this->hasColumn('parent_id' , 'integer'   , 4   );
		$this->hasColumn('created'	 , 'timestamp' , array('notnull' => true));
		$this->hasColumn('updated'	 , 'timestamp' , null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasMany('Publicacao', array('local' => 'id', 'foreign' => 'secao_id'));
    }
}
