<?php

abstract class BaseBalanceteDespesa extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.balancete_despesa');
        
        $this->hasColumn('id'     		   		   , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id'   		   , 'integer', 11);

        $this->hasColumn('cod_entidade' 		   , 'integer');
		$this->hasColumn('cod_orgao'    		   , 'integer');
        $this->hasColumn('cod_unidade' 			   , 'integer');
        $this->hasColumn('cod_funcao'			   , 'integer');
        $this->hasColumn('cod_subfuncao'		   , 'integer');
        $this->hasColumn('cod_programa'			   , 'integer');
        $this->hasColumn('cod_projeto'			   , 'integer');
        $this->hasColumn('cod_elemento'			   , 'bigint');
        $this->hasColumn('cod_recurso'			   , 'integer');
        $this->hasColumn('dotacao_inicial'		   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('atualizacao_monetaria'   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('creditos_suplementares'  , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('creditos_especiais'	   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('creditos_extraordinarios', 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('reducao_dotacoes'	   	   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('suplementacao_recurso'   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('reducao_recurso'	   	   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('valor_empenhado'	   	   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('valor_liquidado'	   	   , 'decimal', 14,  array('scale' => 2));
        $this->hasColumn('valor_pago'	   		   , 'decimal', 14,  array('scale' => 2));
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao'           , array('local' => 'importacao_id' , 'foreign' => 'id'));
		$this->hasOne('Orgao'                , array('local' => 'cod_orgao'     , 'foreign' => 'cod_orgao'));
		$this->hasOne('Funcao'               , array('local' => 'cod_funcao'    , 'foreign' => 'cod_funcao'));
		$this->hasOne('Programa'             , array('local' => 'cod_programa'  , 'foreign' => 'cod_programa'));
		$this->hasOne('Acao'                 , array('local' => 'cod_projeto'   , 'foreign' => 'cod_projeto'));
		$this->hasOne('Recurso'              , array('local' => 'cod_recurso'   , 'foreign' => 'cod_recurso'));
		$this->hasOne('ConfiguracaoEntidade' , array('local' => 'cod_entidade'  , 'foreign' => 'entidade_id'));
    }
}