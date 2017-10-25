<?php

abstract class BaseBalanceteReceita extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.balancete_receita');
        
        $this->hasColumn('id'     		      , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id'      , 'integer', 11);
        
		$this->hasColumn('cod_entidade' 	  , 'integer');
		$this->hasColumn('cod_conta'    	  , 'bigint' );
        $this->hasColumn('cod_orgao_unidade'  , 'integer');
        $this->hasColumn('receita_orcada'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_janeiro'    , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_fevereiro'  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_marco'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_abril'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_maio'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_junho'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_julho'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_agosto'  	  , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_setembro'   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_outubro'    , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_novembro'   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('receita_dezembro'   , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('especificacao_conta', 'string' , 170);
        $this->hasColumn('tipo_nivel'		  , 'string' , 1);
        $this->hasColumn('numero_nivel'	      , 'string' , 2);
        $this->hasColumn('cod_recurso'		  , 'integer');
        
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao'			 , array('local' => 'importacao_id', 'foreign' => 'id'));
		$this->hasOne('ConfiguracaoEntidade' , array('local' => 'cod_entidade' , 'foreign' => 'entidade_id'));
    }
}
