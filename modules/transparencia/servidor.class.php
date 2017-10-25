<?php

class Servidor extends BaseServidor {

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
	
	public function getMatricula(){
		return $this->matricula;
	}

	public function setMatricula($matricula){
		$this->matricula = $matricula;
	}
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getSituacao(){
		return $this->matricula;
	}

	public function setSituacao($situacao){
		$this->situacao = $situacao;
	}
	
	public function getDtAdmissao(){
		return $this->dt_admissao;
	}

	public function setDtAdmissao($dt_admissao){
		$this->dt_admissao = $dt_admissao;
	}	
	
	public function getAtoNomeacao(){
		return $this->ato_nomeacao;
	}

	public function setAtoNomeacao($ato_nomeacao){
		$this->ato_nomeacao = $ato_nomeacao;
	}

	public function getDtRescisao(){
		return $this->dt_rescisao;
	}

	public function setDtRescisao($dt_rescisao){
		$this->dt_rescisao = $dt_rescisao;
	}	
	
	public function getDescricaoCausaRescisao(){
		return $this->descricao_causa_rescisao;
	}

	public function setDescricaoCausaRescisao($descricao_causa_rescisao){
		$this->descricao_causa_rescisao = $descricao_causa_rescisao;
	}	
			
	public function getDescricaoRegimeFuncao(){
		return $this->descricao_regime_funcao;
	}

	public function setDescricaoRegimeFuncao($descricao_regime_funcao){
		$this->descricao_regime_funcao = $descricao_regime_funcao;
	}

	public function getDescricaoRegimeSubdivisaoFuncao(){
		return $this->descricao_regime_subdivisao_funcao;
	}

	public function setDescricaoRegimeSubdivisaoFuncao($descricao_regime_subdivisao_funcao){
		$this->descricao_regime_subdivisao_funcao = $descricao_regime_subdivisao_funcao;
	}	
	
	public function getDescricaoFuncao(){
		return $this->descricao_funcao;
	}

	public function setDescricaoFuncao($descricao_funcao){
		$this->descricao_funcao = $descricao_funcao;
	}	
	 
	public function getDescricaoEspecialidadeFuncao(){
		return $this->descricao_especialidade_funcao;
	}

	public function setDescricaoEspecialidadeFuncao($descricao_especialidade_funcao){
		$this->descricao_especialidade_funcao = $descricao_especialidade_funcao;
	}	
	
	public function getDescricaoPadrao(){
		return $this->descricao_padrao;
	}

	public function setDescricaoPadrao($descricao_padrao){
		$this->descricao_padrao = $descricao_padrao;
	}	
	
	public function getHorasMensais(){
		return $this->horas_mensais;
	}

	public function setHorasMensais($horas_mensais){
		$this->horas_mensais = $horas_mensais;
	}	
	
	public function getLotacao(){
		return $this->lotacao;
	}

	public function setLotacao($lotacao){
		$this->lotacao = $lotacao;
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