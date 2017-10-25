<?php

class Licitacao extends BaseLicitacao {

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
	
	public function setExercicioEntidade($exercicio_entidade){
		$this->exercicio_entidade = $exercicio_entidade;
	}

	public function getExercicioEntidade(){
		return $this->exercicio_entidade;
	}
		
	public function getCodLicitacao(){
		return $this->cod_licitacao;
	}

	public function setCodLicitacao($cod_licitacao){
		$this->cod_licitacao = $cod_licitacao;
	}

	public function getModalidade(){
		return $this->modalidade;
	}

	public function setModalidade($modalidade){
		$this->modalidade = $modalidade;
	}

	public function getExercicioEmpenho(){
		return $this->exercicio_empenho;
	}

	public function setExercicioEmpenho($exercicio_empenho){
		$this->exercicio_empenho = $exercicio_empenho;
	}	

	public function getCodEmpenho(){
		return $this->cod_empenho;
	}

	public function setCodEmpenho($cod_empenho){
		$this->cod_empenho = $cod_empenho;
	}
	
	public function getDescricaoTipoLicitacao(){
		return $this->descricao_tipo_licitacao;
	}

	public function setDescricaoTipoLicitacao($descricao_tipo_licitacao){
		$this->descricao_tipo_licitacao = $descricao_tipo_licitacao;
	}
	
	public function getDescricaoTipoObjeto(){
		return $this->descricao_tipo_objeto;
	}

	public function setDescricaoTipoObjeto($descricao_tipo_objeto){
		$this->descricao_tipo_objeto = $descricao_tipo_objeto;
	}	
		
	public function getDescricaoObjeto(){
		return $this->descricao_objeto;
	}

	public function setDescricaoObjeto($descricao_objeto){
		$this->descricao_objeto = $descricao_objeto;
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