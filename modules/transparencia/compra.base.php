<?php

abstract class BaseCompra extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.compra');
        
        $this->hasColumn('id'     		 		   , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 		   , 'integer', 11);
                                                   
        $this->hasColumn('exercicio_entidade'	   , 'integer');
        $this->hasColumn('cod_entidade' 		   , 'integer');
        $this->hasColumn('cod_compra_direta'	   , 'integer');
		$this->hasColumn('modalidade'			   , 'string' , 50);
		$this->hasColumn('exercicio_empenho'	   , 'integer');
		$this->hasColumn('cod_empenho' 			   , 'integer');
		$this->hasColumn('descricao_tipo_licitacao', 'string' , 15);
		$this->hasColumn('descricao_tipo_objeto'   , 'string' , 50);
		$this->hasColumn('descricao_objeto'		   , 'string' , 500);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
		$this->hasOne('Empenho'    , array('local' => 'cod_empenho' , 'foreign' => 'numero_empenho'));
    }
}
