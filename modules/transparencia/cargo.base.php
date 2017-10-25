<?php

abstract class BaseCargo extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.cargo');
        
        $this->hasColumn('id'     		 		, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 		, 'integer', 11);
		
        $this->hasColumn('cod_entidade' 		, 'integer');
		$this->hasColumn('mes_ano'				, 'string' , 7);
		$this->hasColumn('codigo'				, 'integer');
        $this->hasColumn('descricao_cargo'		, 'string' , 60);
        $this->hasColumn('tipo_cargo'  			, 'string' , 20);
        $this->hasColumn('lei'  				, 'string' , 10);
        $this->hasColumn('descricao_padrao'  	, 'string' , 60);
        $this->hasColumn('carga_horaria_mensal' , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('carga_horaria_semanal', 'decimal', 14, array('scale' => 2));
        $this->hasColumn('valor'  				, 'decimal', 14, array('scale' => 2));
        $this->hasColumn('vigencia'  			, 'date'   );
        $this->hasColumn('regime_subdivisao'  	, 'string' , 80);
        $this->hasColumn('vagas_criadas'  		, 'integer');
        $this->hasColumn('vagas_ocupadas'  		, 'integer');
        $this->hasColumn('vagas_disponiveis'  	, 'integer');
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}
