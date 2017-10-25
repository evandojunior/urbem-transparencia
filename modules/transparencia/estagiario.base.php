<?php

abstract class BaseEstagiario extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.estagiario');
        
        $this->hasColumn('id'     		 	, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	, 'integer', 11);
                                                       
        $this->hasColumn('cod_entidade'     , 'integer');
        $this->hasColumn('mes_ano'		    , 'string' , 7);
        $this->hasColumn('numero_estagio'	, 'integer');
        $this->hasColumn('nome'	 		 	, 'string' , 60);
        $this->hasColumn('data_inicio'	 	, 'date'   );
        $this->hasColumn('data_fim'	 		, 'date'   );
        $this->hasColumn('data_renovacao'	, 'date'   );
        $this->hasColumn('descricao_lotacao', 'string' , 60);
        $this->hasColumn('descricao_local'  , 'string' , 60);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}