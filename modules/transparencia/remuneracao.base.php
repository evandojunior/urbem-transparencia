<?php

abstract class BaseRemuneracao extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.remuneracao');
        
        $this->hasColumn('id'     		 	  			, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	  			, 'integer', 11);
		
        $this->hasColumn('cod_entidade'	 				, 'integer');
        $this->hasColumn('mes_ano'    					, 'string' , 7);
        $this->hasColumn('matricula'      				, 'string' , 8);
        $this->hasColumn('nome'      					, 'string' , 60);
        $this->hasColumn('remuneracao_bruta'    		, 'decimal', 14, array('scale' => 2));
        $this->hasColumn('remuneracao_teto'     		, 'decimal', 14, array('scale' => 2));
        $this->hasColumn('remuneracao_eventual_natalina', 'decimal', 14, array('scale' => 2));
        $this->hasColumn('remuneracao_eventual_ferias'  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('remuneracao_eventual_outras'  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('deducoes_obrigatorias_irrf'   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('deducoes_obrigatorias_prev'   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('demais_deducoes'      		, 'decimal', 14, array('scale' => 2));
        $this->hasColumn('remuneracao_apos_deducoes'    , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('verbas_salario_familia'       , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('verbas_jetons'      			, 'decimal', 14, array('scale' => 2));
        $this->hasColumn('demais_verbas'      			, 'decimal', 14, array('scale' => 2));
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}