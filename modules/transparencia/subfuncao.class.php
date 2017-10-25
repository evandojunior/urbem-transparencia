<?php

class Subfuncao extends BaseSubfuncao {

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
    
	public function getCodSubfuncao(){
		return $this->cod_subfuncao;
	}

	public function setCodSubfuncao($cod_subfuncao){
		$this->cod_subfuncao = $cod_subfuncao;
	}	
	
	public function getNomeSubfuncao(){
		return $this->nome_subfuncao;
	}

	public function setNomeSubfuncao($nome_subfuncao){
		$this->nome_subfuncao = $nome_subfuncao;
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