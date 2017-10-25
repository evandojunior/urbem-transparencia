<?php

abstract class BaseHistorico extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('historico');
        
		$this->hasColumn('id'            , 'integer'    , 4    , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('pessoa_id'     , 'integer'    , 4    , array('notnull' => true));
        $this->hasColumn('modulo_id'     , 'integer'    , 4    , array('notnull' => true));
        $this->hasColumn('entidade_id'   , 'integer'    , 4    , array('notnull' => true));
        $this->hasColumn('descricao'     , 'string'     , null , array('notnull' => true));
        $this->hasColumn('created'       , 'timestamp'  , null , array('notnull' => true));
        $this->hasColumn('updated'       , 'timestamp'  , null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasOne('Modulo', array('local' => 'modulo_id', 'foreign' => 'id'));
        $this->hasOne('Pessoa', array('local' => 'pessoa_id', 'foreign' => 'id'));
    }
}
