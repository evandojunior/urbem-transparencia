<?php

abstract class BaseItem extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.item');
        
        $this->hasColumn('id'     		 	, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	, 'integer', 11);

        $this->hasColumn('numero_empenho' , 'integer');
        $this->hasColumn('cod_entidade'   , 'integer');
        $this->hasColumn('exercicio'	  , 'integer');
        $this->hasColumn('data'	          , 'date');
		$this->hasColumn('numero_item'	  , 'integer');
		$this->hasColumn('descricao'      , 'string' , 160);
		$this->hasColumn('unidade'		  , 'string' , 80);
		$this->hasColumn('quantidade'	  , 'decimal', 14,  array('scale' => 2));
		$this->hasColumn('valor'		  , 'decimal', 14,  array('scale' => 2));
		$this->hasColumn('sinal_valor'	  , 'string' , 1);
		$this->hasColumn('complemento'	  , 'string', null);
    }

    public function setUp(){
        parent::setUp();

        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}