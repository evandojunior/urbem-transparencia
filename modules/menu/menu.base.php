<?php

abstract class BaseMenu extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('menu');
		
        $this->hasColumn('id'		 , 'integer' , 11, array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('acao_id'	 , 'integer' , 11);
		$this->hasColumn('parent_id' , 'integer' , 11);
        $this->hasColumn('url'		 , 'string'	 , 100 , array('notnull' => true));
        $this->hasColumn('label'	 , 'string'	 , 30  , array('notnull' => true));
        $this->hasColumn('target'	 , 'string'	 , 10  , array('notnull' => true));
        $this->hasColumn('posicao'	 , 'int'	 , 11  , array('notnull' => true));        
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasMany('Menu' , array('local' => 'parent_id' , 'foreign' => 'id'));
        $this->hasOne('_Acao'  , array('local' => 'acao_id'   , 'foreign' => 'id'));
    }
}