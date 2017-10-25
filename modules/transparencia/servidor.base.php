<?php

abstract class BaseServidor extends Doctrine_Record{
	
    public function setTableDefinition(){
        $this->setTableName('transparencia.servidor');
        
        $this->hasColumn('id'     		 	  				 , 'integer', 11, array('primary' => true, 'autoincrement' => true));
        $this->hasColumn('importacao_id' 	  				 , 'integer', 11);
		
        $this->hasColumn('cod_entidade'	  				 	 , 'integer');
        $this->hasColumn('mes_ano'   						 , 'varchar', 7);
        $this->hasColumn('matricula'      					 , 'string' , 8);
        $this->hasColumn('nome'      						 , 'string' , 60);
        $this->hasColumn('situacao'      					 , 'string' , 40);
        $this->hasColumn('dt_admissao'      				 , 'date'   );
        $this->hasColumn('ato_nomeacao'      				 , 'string' , 10);
        $this->hasColumn('dt_rescisao'      				 , 'date'   );
        $this->hasColumn('descricao_causa_rescisao'      	 , 'string' , 60);
        $this->hasColumn('descricao_regime_funcao'      	 , 'string' , 3);
        $this->hasColumn('descricao_regime_subdivisao_funcao', 'string' , 40);
        $this->hasColumn('descricao_funcao'      			 , 'string' , 60);
        $this->hasColumn('descricao_especialidade_funcao'    , 'string' , 60);
        $this->hasColumn('descricao_padrao'      			 , 'string' , 60);
        $this->hasColumn('horas_mensais'      			 	 , 'decimal', 14, array('scale' => 2));
        $this->hasColumn('lotacao'      					 , 'string' , 20);
        $this->hasColumn('descricao_lotacao'      			 , 'string' , 60);
        $this->hasColumn('descricao_local'      			 , 'string' , 60);
    }

    public function setUp(){
        parent::setUp();
        
        $this->hasOne('Importacao' , array('local' => 'importacao_id' , 'foreign' => 'id'));
    }
}
