<?php

abstract class BaseGrupo extends Doctrine_Record{
    
    public function setTableDefinition(){
        $this->setTableName('grupo');
        
        $this->hasColumn('id'      , 'integer', 4  , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('grupo'   , 'string' , 20 , array('notnull' => true));
        $this->hasColumn('alias'   , 'string' , 20 , array('notnull' => true));
		$this->hasColumn('created' , 'timestamp' , array('notnull' => true));
		$this->hasColumn('updated' , 'timestamp' , null);        
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasMany('Usuario'      , array('local' => 'id', 'foreign' => 'grupo_id'));
        $this->hasMany('GrupoAcao'    , array('local' => 'id', 'foreign' => 'grupo_id'));
        $this->hasMany('ContatoGrupo' , array('local' => 'id', 'foreign' => 'grupo_id'));
    }
}

abstract class BaseGrupoAcao extends Doctrine_Record{
    
    public function setTableDefinition(){
        $this->setTableName('grupo_acao');
        
        $this->hasColumn('id'       , 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('grupo_id' , 'integer', 4, array('notnull' => true));
        $this->hasColumn('acao_id'  , 'integer', 4, array('notnull' => true));
    }

    public function setUp(){
        parent::setUp();

        $this->hasOne('_Acao'  , array('local' => 'acao_id', 'foreign' => 'id'));
        $this->hasOne('Grupo' , array('local' => 'acao_id', 'foreign' => 'id'));
    }
}