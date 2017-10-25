<?php

abstract class _BaseAcao extends Doctrine_Record{
    
    public function setTableDefinition(){
        $this->setTableName('acao');
        
        $this->hasColumn('id'        , 'integer', 4     , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('alias '    , 'string' , 20    , array('notnull' => true));
        $this->hasColumn('descricao' , 'string' , null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasMany('GrupoAcao', array('local' => 'id', 'foreign' => 'acao_id'));
        $this->hasMany('Menu'     , array('local' => 'id', 'foreign' => 'acao_id'));
    }
}