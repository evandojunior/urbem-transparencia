<?php

abstract class BaseContato extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('contato');
        
		$this->hasColumn('id'              , 'integer'  , 11   , array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('configuracao_id' , 'integer'  , 11   , array('notnull' => true));
        $this->hasColumn('assunto'         , 'string'   , 100  , array('notnull' => true));
        $this->hasColumn('nome'            , 'string'   , 100  , array('notnull' => true));
        $this->hasColumn('email'           , 'string'   , 150  );
        $this->hasColumn('ddd'             , 'string'   , 2    );
        $this->hasColumn('telefone'        , 'string'   , 9    );
        $this->hasColumn('mensagem'        , 'string'   , null , array('notnull' => true));
        $this->hasColumn('status'          , 'string'   , 2    , array('notnull' => true));
        $this->hasColumn('created'         , 'timestamp', null , array('notnull' => true));
        $this->hasColumn('updated'         , 'timestamp', null ) ;
    }

    public function setUp(){
        $this->hasOne('Configuracao', array('local' => 'configuracao_id', 'foreign' => 'id'));

        parent::setUp();
    }
}