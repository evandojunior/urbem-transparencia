<?php

class Funcao extends BaseFuncao {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
    public function getImportacaoId(){
    	return $this->importacao_id;
    }

    public function setImportacaoId($importacao_id){
        $this->importacao_id = $importacao_id;
    }	
    
	public function getExercicio(){
		return $this->exercicio;
	}

	public function setExercicio($exercicio){
		$this->exercicio = $exercicio;
	}	
	
	public function getCodFuncao(){
		return $this->cod_funcao;
	}

	public function setCodFuncao($cod_funcao){
		$this->cod_funcao = $cod_funcao;
	}	

	public function getNomeFuncao(){
		return $this->nome_funcao;
	}

	public function setNomeFuncao($nome_funcao){
		$this->nome_funcao = $nome_funcao;
	}
	
    /*
     * Relacionamentos
     */	
	
	public function getImportacao(){
		return $this->Importacao;
	}

	public function setImportacao(Importacao $importacao){
		$this->Importacao = $importacao;
	}	
}