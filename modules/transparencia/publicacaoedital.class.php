<?php

class PublicacaoEdital extends BasePublicacaoEdital {

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
	    
	public function getExercicioEdital(){
		return $this->exercicio_edital;
	}

	public function setExercicioEdital($exercicio_edital){
		$this->exercicio_edital = $exercicio_edital;
	}
	    
	public function getNumEdital(){
		return $this->num_edital;
	}

	public function setNumEdital($num_edital){
		$this->num_edital = $num_edital;
	}
	
	public function getExercicioLicitacao(){
		return $this->exercicio_licitacao;
	}

	public function setExercicioLicitacao($exercicio_licitacao){
		$this->exercicio_licitacao = $exercicio_licitacao;
	}	
    
	public function getCodLicitacao(){
		return $this->cod_licitacao;
	}

	public function setCodLicitacao($cod_licitacao){
		$this->cod_licitacao = $cod_licitacao;
	}	
	
	public function getCodEntidade(){
		return $this->cod_entidade;
	}

	public function setCodEntidade($cod_entidade){
		$this->cod_entidade = $cod_entidade;
	}
		
	public function getModalidade(){
		return $this->modalidade;
	}

	public function setModalidade($modalidade){
		$this->modalidade = $modalidade;
	}

	public function getVeiculoPublicacao(){
		return $this->veiculo_publicacao;
	}

	public function setVeiculoPublicacao($veiculo_publicacao){
		$this->veiculo_publicacao = $veiculo_publicacao;
	}

	public function getDataPublicacao(){
		return $this->data_publicacao;
	}

	public function setDataPublicacao($data_publicacao){
		$this->data_publicacao = $data_publicacao;
	}
	
	public function getObservacao(){
		return $this->observacao;
	}

	public function setObservacao($observacao){
		$this->observacao = $observacao;
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