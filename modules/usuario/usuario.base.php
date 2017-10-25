<?php

abstract class BaseUsuario extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('usuario');
        
        $this->hasColumn('id'       		  , 'integer'  , 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('grupo_id'			  , 'integer'  , 11, array('notnull' => true));
		$this->hasColumn('municipio_id'  	  , 'integer'  , 11, array('notnull' => true));
		$this->hasColumn('pessoa_id'		  , 'integer'  , 11);
		$this->hasColumn('status'		  	  , 'enum'     , array('notnull' => true, 'values' => array('0', '1')));
		$this->hasColumn('senha'			  , 'string'   , 150);
		$this->hasColumn('created'		  	  , 'timestamp', null, array('notnull' => true));
		$this->hasColumn('updated'		  	  , 'timestamp', null);
    }

    public function setUp(){
        parent::setUp();        
        $this->hasOne('Grupo'     , array('local' => 'grupo_id'    , 'foreign' => 'id'));
        $this->hasOne('Pessoa'    , array('local' => 'pessoa_id'   , 'foreign' => 'id'));
		$this->hasOne('Municipio' , array('local' => 'municipio_id', 'foreign' => 'id'));
    }
}
