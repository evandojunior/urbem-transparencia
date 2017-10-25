<?php

class Estagiario extends BaseEstagiario {

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
    
	public function getCodEntidade(){
		return $this->cod_entidade;
	}

	public function setCodEntidade($cod_entidade){
		$this->cod_entidade = $cod_entidade;
	}	
	
	public function getMesAno(){
		return $this->mes_ano;
	}

	public function setMesAno($mes_ano){
		$this->mes_ano = $mes_ano;
	}	

	public function getNumeroEstagio(){
		return $this->numero_estagio;
	}

	public function setNumeroEstagio($numero_estagio){
		$this->numero_estagio = $numero_estagio;
	}

	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}

	public function getDataInicio(){
		return $this->data_inicio;
	}

	public function setDataInicio($data_inicio){
		$this->data_inicio = $data_inicio;
	}	

	public function getDataFim(){
		return $this->data_fim;
	}

	public function setDataFim($data_fim){
		$this->data_fim = $data_fim;
	}
	
	public function getDataRenovacao(){
		return $this->data_renovacao;
	}

	public function setDataRenovacao($data_renovacao){
		$this->data_renovacao = $data_renovacao;
	}	
		
	public function getDescricaoLotacao(){
		return $this->descricao_lotacao;
	}

	public function setDescricaoLotacao($descricao_lotacao){
		$this->descricao_lotacao = $descricao_lotacao;
	}

	public function getDescricaoLocal(){
		return $this->descricao_local;
	}

	public function setDescricaoLocal($descricao_local){
		$this->descricao_local = $descricao_local;
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