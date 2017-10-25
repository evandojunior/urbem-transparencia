<?php

abstract class BaseCategoria extends Doctrine_Record{
	
    public function setTableDefinition(){
    	$this->setTableName('categoria');
    	
		$this->hasColumn('id'        , 'integer'   , 4  , array('primary' => true, 'autoincrement' => true));
		$this->hasColumn('type'      , 'string'    , 30 , array('notnull' => true));
		$this->hasColumn('alias'     , 'string'    , 30 , array('notnull' => true));
		$this->hasColumn('categoria' , 'string'    , 30 , array('notnull' => true));
		$this->hasColumn('parent_id' , 'integer'   , 4   );
		$this->hasColumn('created'	 , 'timestamp' , array('notnull' => true));
		$this->hasColumn('updated'	 , 'timestamp' , null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasOne('Categoria'    , array('local' => 'parent_id', 'foreign' => 'id'));
        $this->hasMany('Banner'      , array('local' => 'id', 'foreign' => 'categoria_id'));
        $this->hasMany('Produto'     , array('local' => 'id', 'foreign' => 'categoria_id'));
        $this->hasMany('Conteudo'    , array('local' => 'id', 'foreign' => 'categoria_id'));
        $this->hasMany('Pessoa'      , array('local' => 'id', 'foreign' => 'categoria_id'));
        $this->hasMany('Contato'     , array('local' => 'id', 'foreign' => 'categoria_id'));
        $this->hasMany('ContatoGrupo', array('local' => 'id', 'foreign' => 'categoria_id'));
    }
}
