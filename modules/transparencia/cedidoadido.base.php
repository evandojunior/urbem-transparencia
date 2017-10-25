<?php

abstract class BaseCedidoAdido extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.cedidoadido');
        
        $this->hasColumn('id'     		 			, 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 			, 'integer', 11);
                                                               
        $this->hasColumn('cod_entidade' 			, 'integer');        
		$this->hasColumn('mes_ano'					, 'string' , 7);
		$this->hasColumn('matricula'				, 'integer');
        $this->hasColumn('nom_cgm'					, 'string' , 60);
        $this->hasColumn('situacao'  				, 'string' , 40);
        $this->hasColumn('ato_cedencia'  			, 'string' , 10);
        $this->hasColumn('dt_inicial'  				, 'date'   );
        $this->hasColumn('dt_final' 				, 'date'   );
        $this->hasColumn('tipo_cedencia'			, 'string' , 10);
        $this->hasColumn('indicativo_onus'  		, 'string' , 20);
        $this->hasColumn('orgao_cedente_cessionario', 'string' , 60);
        $this->hasColumn('num_convenio'  			, 'string' , 15);
        $this->hasColumn('local'  					, 'string' , 60);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}
