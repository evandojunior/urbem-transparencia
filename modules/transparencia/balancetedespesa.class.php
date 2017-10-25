<?php

class BalanceteDespesa extends BaseBalanceteDespesa {

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
	
	public function getCodFuncao(){
		return $this->cod_funcao;
	}

	public function setCodFuncao($cod_funcao){
		$this->cod_funcao = $cod_funcao;
	}
	
	public function getCodSubfuncao(){
		return $this->cod_subfuncao;
	}

	public function setCodSubfuncao($cod_subfuncao){
		$this->cod_subfuncao = $cod_subfuncao;
	}
	
	public function getCodPrograma(){
		return $this->cod_programa;
	}

	public function setCodPrograma($cod_programa){
		$this->cod_programa = $cod_programa;
	}

	public function getCodProjeto(){
		return $this->cod_projeto;
	}

	public function setCodProjeto($cod_projeto){
		$this->cod_projeto = $cod_projeto;
	}

	public function getCodElemento(){
		return $this->cod_elemento;
	}

	public function setCodElemento($cod_elemento){
		$this->cod_elemento = $cod_elemento;
	}	
	
	public function getCodRecurso(){
		return $this->cod_recurso;
	}

	public function setCodRecurso($cod_recurso){
		$this->cod_recurso = $cod_recurso;
	}	
	
	public function getDotacaoInicial(){
		return $this->dotacao_inicial;
	}

	public function setDotacaoInicial($dotacao_inicial){
		$this->dotacao_inicial = $dotacao_inicial;
	}	
		
	public function getAtualizacaoMonetaria(){
		return $this->atualizacao_monetaria;
	}

	public function setAtualizacaoMonetaria($atualizacao_monetaria){
		$this->atualizacao_monetaria = $atualizacao_monetaria;
	}	
	
	public function getCreditosSuplementares(){
		return $this->creditos_suplementares;
	}

	public function setCreditosSuplementares($creditos_suplementares){
		$this->creditos_suplementares = $creditos_suplementares;
	}
		
	public function getCreditosEspeciais(){
		return $this->creditos_especiais;
	}

	public function setCreditosEspeciais($creditos_especiais){
		$this->creditos_especiais = $creditos_especiais;
	}

	public function getCreditosExtraordinarios(){
		return $this->creditos_extraordinarios;
	}

	public function setCreditosExtraordinarios($creditos_extraordinarios){
		$this->creditos_extraordinarios = $creditos_extraordinarios;
	}	

	public function getReducaoDotacoes(){
		return $this->reducao_dotacoes;
	}

	public function setReducaoDotacoes($reducao_dotacoes){
		$this->reducao_dotacoes = $reducao_dotacoes;
	}	
	
	public function getSuplementacaoRecurso(){
		return $this->suplementacao_recurso;
	}

	public function setSuplementacaoRecurso($suplementacao_recurso){
		$this->suplementacao_recurso = $suplementacao_recurso;
	}		
	
	public function getReducaoRecurso(){
		return $this->reducao_recurso;
	}

	public function setReducaoRecurso($reducao_recurso){
		$this->reducao_recurso = $reducao_recurso;
	}	
	
	public function getValorEmpenhado(){
		return $this->valor_empenhado;
	}

	public function setValorEmpenhado($valor_empenhado){
		$this->valor_empenhado = $valor_empenhado;
	}	
	
	public function getValorLiquidado(){
		return $this->valor_liquidado;
	}

	public function setValorLiquidado($valor_liquidado){
		$this->valor_liquidado = $valor_liquidado;
	}
	
	public function getValorPago(){
		return $this->valor_pago;
	}

	public function setValorPago($valor_pago){
		$this->valor_pago = $valor_pago;
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
