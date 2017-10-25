<?php

abstract class BasePagamento extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.pagamento');
        
        $this->hasColumn('id'     		 	  , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	  , 'integer', 11);
		
        $this->hasColumn('cod_empenho'	 	  , 'integer');
        $this->hasColumn('cod_entidade'       , 'integer');
        $this->hasColumn('numero_pagamento'   , 'integer');
        $this->hasColumn('data_pagamento'     , 'date'   );
        $this->hasColumn('valor_pagamento'	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('sinal_valor' 	      , 'string' , 1);
		$this->hasColumn('historico_pagamento', 'string' , 165);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}