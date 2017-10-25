<?php

abstract class BaseEmpenho extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.empenho');
        
        $this->hasColumn('id'     		 	    , 'integer', 4, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	    , 'integer', 4);
        
		$this->hasColumn('cod_entidade'	    	, 'integer');
        $this->hasColumn('cod_orgao' 	    	, 'integer');
        $this->hasColumn('cod_unidade' 	    	, 'integer');
        $this->hasColumn('cod_funcao' 	    	, 'integer');
        $this->hasColumn('cod_subfuncao'    	, 'integer');
        $this->hasColumn('cod_programa' 		, 'integer');
        $this->hasColumn('cod_subprograma' 		, 'integer');
        $this->hasColumn('cod_projeto' 	    	, 'integer');
        $this->hasColumn('cod_rubrica' 	    	, 'bigint'  );
        $this->hasColumn('cod_recurso' 	    	, 'integer');
        $this->hasColumn('contrapartida_recurso', 'integer');
        $this->hasColumn('numero_empenho' 	    , 'float'  );
        $this->hasColumn('data_empenho' 	    , 'date'   );
        $this->hasColumn('valor_empenho' 	    , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('sinal_valor' 	 	    , 'string' , 1);
        $this->hasColumn('cod_credor' 	    	, 'integer');
        $this->hasColumn('historico_empenho' 	, 'string' , 165);
        $this->hasColumn('modalidade_licitacao' , 'string' , 30);
        $this->hasColumn('numero_licitacao' 	, 'varchar', 10);
        $this->hasColumn('ano_licitacao' 	    , 'integer');
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao'           , array('local' => 'importacao_id'  , 'foreign' => 'id'));
		$this->hasOne('Entidade'             , array('local' => 'cod_entidade'   , 'foreign' => 'cod_entidade'));
		$this->hasOne('Orgao'                , array('local' => 'cod_orgao'      , 'foreign' => 'cod_orgao'));
		$this->hasOne('Unidade'              , array('local' => 'cod_unidade'    , 'foreign' => 'cod_unidade'));
		$this->hasOne('Funcao'               , array('local' => 'cod_funcao'     , 'foreign' => 'cod_funcao'));
		$this->hasOne('Subfuncao'            , array('local' => 'cod_subfuncao'  , 'foreign' => 'cod_subfuncao'));
		$this->hasOne('Programa'             , array('local' => 'cod_programa'   , 'foreign' => 'cod_programa'));

		$this->hasOne('Acao'                 , array('local' => 'cod_projeto' 	 , 'foreign' => 'cod_projeto'));
		$this->hasOne('Rubrica'              , array('local' => 'cod_rubrica' 	 , 'foreign' => 'cod_rubrica_despesa'));
		$this->hasOne('Recurso'              , array('local' => 'cod_recurso' 	 , 'foreign' => 'cod_recurso'));
        $this->hasOne('Credor'               , array('local' => 'cod_credor' 	 , 'foreign' => 'cod_credor'));
        $this->hasOne('Liquidacao'           , array('local' => 'numero_empenho' , 'foreign' => 'cod_empenho'));
        $this->hasOne('Pagamento'            , array('local' => 'numero_empenho' , 'foreign' => 'cod_empenho'));
		$this->hasOne('Compra'               , array('local' => 'numero_empenho' , 'foreign' => 'cod_empenho'));
		$this->hasOne('Licitacao'            , array('local' => 'numero_empenho' , 'foreign' => 'cod_empenho'));
		$this->hasOne('ConfiguracaoEntidade' , array('local' => 'cod_entidade'   , 'foreign' => 'entidade_id'));
    }
}
