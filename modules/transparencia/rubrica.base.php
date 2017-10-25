<?php

abstract class BaseRubrica extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.rubrica');
        
        $this->hasColumn('id'     		 	  			, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	  			, 'integer', 11);
		
        $this->hasColumn('exercicio'	 	      		, 'integer');
        $this->hasColumn('cod_rubrica_despesa'          , 'bigint');
        $this->hasColumn('especificacao_rubrica_despesa', 'string', 110);
        $this->hasColumn('tipo_nivel_conta'      		, 'string', 1);
        $this->hasColumn('numero_nivel_conta'      		, 'integer');
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
		$this->hasMany('BalanceteDespesa'   , array('local' => 'cod_rubrica_despesa'  , 'foreign' => 'cod_elemento'));
    }
}