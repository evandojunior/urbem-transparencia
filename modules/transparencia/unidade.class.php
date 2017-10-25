<?php

class Unidade extends BaseUnidade {

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
	
	public function getCodUnidade(){
		return $this->cod_unidade;
	}

	public function setCodUnidade($cod_unidade){
		$this->cod_unidade = $cod_unidade;
	}	
	
	public function getNomeUnidade(){
		return $this->nome_unidade;
	}

	public function setNomeUnidade($nome_unidade){
		$this->nome_unidade = $nome_unidade;
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