<?php

class BalanceteReceita extends BaseBalanceteReceita {

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

	public function getCodConta(){
		return $this->cod_conta;
	}

	public function setCodConta($cod_conta){
		$this->cod_conta = $cod_conta;
	}
	
	public function getCodOrgaoUnidade(){
		return $this->cod_orgao_unidade;
	}

	public function setCodOrgaoUnidade($cod_orgao_unidade){
		$this->cod_orgao_unidade = $cod_orgao_unidade;
	}	
	
	public function getReceitaOrcada(){
		return $this->receita_orcada;
	}

	public function setReceitaOrcada($receita_orcada){
		$this->receita_orcada = $receita_orcada;
	}
	
	public function getReceitaJaneiro(){
		return $this->receita_janeiro;
	}

	public function setReceitaJaneiro($receita_janeiro){
		$this->receita_janeiro = $receita_janeiro;
	}

	public function getReceitaFevereiro(){
		return $this->receita_fevereiro;
	}

	public function setReceitaFevereiro($receita_fevereiro){
		$this->receita_fevereiro = $receita_fevereiro;
	}
	
	public function getReceitaMarco(){
		return $this->receita_marco;
	}

	public function setReceitaMarco($receita_marco){
		$this->receita_marco = $receita_marco;
	}
	
	public function getReceitaAbril(){
		return $this->receita_abril;
	}

	public function setReceitaAbril($receita_abril){
		$this->receita_abril = $receita_abril;
	}
	
	public function getReceitaMaio(){
		return $this->receita_maio;
	}

	public function setReceitaMaio($receita_maio){
		$this->receita_maio = $receita_maio;
	}

	public function getReceitaJunho(){
		return $this->receita_junho;
	}

	public function setReceitaJunho($receita_junho){
		$this->receita_junho = $receita_junho;
	}
	
	public function getReceitaJulho(){
		return $this->receita_julho;
	}

	public function setReceitaJulho($receita_julho){
		$this->receita_julho = $receita_julho;
	}

	
	public function getReceitaAgosto(){
		return $this->receita_agosto;
	}

	public function setReceitaAgosto($receita_agosto){
		$this->receita_agosto = $receita_agosto;
	}
	
	public function getReceitaSetembro(){
		return $this->receita_setembro;
	}

	public function setReceitaSetembro($receita_setembro){
		$this->receita_setembro = $receita_setembro;
	}
	
	public function getReceitaOutubro(){
		return $this->receita_outubro;
	}

	public function setReceitaOutubro($receita_outubro){
		$this->receita_outubro = $receita_outubro;
	}
	
	public function getReceitaNovembro(){
		return $this->receita_novembro;
	}

	public function setReceitaNovembro($receita_novembro){
		$this->receita_novembro = $receita_novembro;
	}

	public function getReceitaDezembro(){
		return $this->receita_dezembro;
	}

	public function setReceitaDezembro($receita_dezembro){
		$this->receita_dezembro = $receita_dezembro;
	}

	public function getEspecificacaoConta(){
		return $this->especificacao_conta;
	}

	public function setEspecificacaoConta($especificacao_conta){
		$this->especificacao_conta = $especificacao_conta;
	}

	public function getTipoNivel(){
		return $this->tipo_nivel;
	}

	public function setTipoNivel($tipo_nivel){
		$this->tipo_nivel = $tipo_nivel;
	}

	public function getNumeroNivel(){
		return $this->numero_nivel;
	}

	public function setNumeroNivel($numero_nivel){
		$this->numero_nivel = $numero_nivel;
	}
	
	public function getCodRecurso(){
		return $this->cod_recurso;
	}

	public function setCodRecurso($cod_recurso){
		$this->cod_recurso = $cod_recurso;
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
