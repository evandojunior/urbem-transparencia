<?php

class Orgao extends BaseOrgao {

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
	
	public function getCodOrgao(){
		return $this->cod_orgao;
	}

	public function setCodOrgao($cod_orgao){
		$this->cod_orgao = $cod_orgao;
	}
		
	public function getNomeOrgao(){
		return $this->nome_orgao;
	}

	public function setNomeOrgao($nome_orgao){
		$this->nome_orgao = $nome_orgao;
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