<?php

abstract class BasePublicacaoEdital extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.publicacao_edital');
        
        $this->hasColumn('id'     		 	  , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	  , 'integer', 11);
	    
        $this->hasColumn('exercicio_edital'	  , 'integer');
        $this->hasColumn('num_edital'         , 'integer');
        $this->hasColumn('exercicio_licitacao', 'integer');
        $this->hasColumn('cod_licitacao'      , 'integer');
        $this->hasColumn('cod_entidade'	  	  , 'integer');
        $this->hasColumn('modalidade' 	      , 'string' , 50);
        $this->hasColumn('veiculo_publicacao' , 'string' , 80);
        $this->hasColumn('data_publicacao' 	  , 'date'   );
        $this->hasColumn('observacao' 	      , 'string' , 50);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}