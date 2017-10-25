<?php

abstract class BaseLiquidacao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.liquidacao');
        
        $this->hasColumn('id'     		 	   , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	   , 'integer', 11);
        
		$this->hasColumn('cod_empenho'	 	   , 'integer');
        $this->hasColumn('cod_entidade'        , 'integer');
        $this->hasColumn('cod_liquidacao'      , 'integer');
        $this->hasColumn('data_liquidacao'     , 'date'   );
        $this->hasColumn('valor_liquidacao'	   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('sinal_valor' 	       , 'string' , 1);
		$this->hasColumn('historico_liquidacao', 'string' , 165);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}