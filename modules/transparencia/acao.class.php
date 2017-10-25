<?php

class Acao extends BaseAcao {

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
	
	public function getCodProjeto(){
		return $this->cod_projeto;
	}

	public function setCodProjeto($cod_projeto){
		$this->cod_projeto = $cod_projeto;
	}
	
	public function getNomeProjeto(){
		return $this->nome_projeto;
	}

	public function setNomeProjeto($nome_projeto){
		$this->nome_projeto = $nome_projeto;
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