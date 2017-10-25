<?php

class Recurso extends BaseRecurso {

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
    
	public function getCodRecurso(){
		return $this->cod_recurso;
	}

	public function setCodRecurso($cod_recurso){
		$this->cod_recurso = $cod_recurso;
	}    
    
	public function getNomeRecurso(){
		return $this->nome_recurso;
	}

	public function setNomeRecurso($nome_recurso){
		$this->nome_recurso = $nome_recurso;
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