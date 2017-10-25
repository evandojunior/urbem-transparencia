<?php

class Remuneracao extends BaseRemuneracao {

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
	
	public function getRemuneracaoBruta(){
		return $this->remuneracao_bruta;
	}

	public function setRemuneracaoBruta($remuneracao_bruta){
		$this->remuneracao_bruta = $remuneracao_bruta;
	}
	
	public function getRemuneracaoTeto(){
		return $this->remuneracao_teto;
	}

	public function setRemuneracaoTeto($remuneracao_teto){
		$this->remuneracao_teto = $remuneracao_teto;
	}
	
	public function getRemuneracaoEventualNatalina(){
		return $this->remuneracao_eventual_natalina;
	}

	public function setRemuneracaoEventualNatalina($remuneracao_eventual_natalina){
		$this->remuneracao_eventual_natalina = $remuneracao_eventual_natalina;
	}
	
	public function getRemuneracaoEventualFerias(){
		return $this->remuneracao_eventual_ferias;
	}

	public function setRemuneracaoEventualFerias($remuneracao_eventual_ferias){
		$this->remuneracao_eventual_ferias = $remuneracao_eventual_ferias;
	}	
	
	public function getRemuneracaoEventualOutras(){
		return $this->remuneracao_eventual_outras;
	}

	public function setRemuneracaoEventualOutras($remuneracao_eventual_outras){
		$this->remuneracao_eventual_outras = $remuneracao_eventual_outras;
	}	
	
	public function getDeducoesObrigatoriasIrrf(){
		return $this->deducoes_obrigatorias_irrf;
	}

	public function setDeducoesObrigatoriasIrrf($deducoes_obrigatorias_irrf){
		$this->deducoes_obrigatorias_irrf = $deducoes_obrigatorias_irrf;
	}	
	
	public function getDeducoesObrigatoriasPrev(){
		return $this->deducoes_obrigatorias_prev;
	}

	public function setDeducoesObrigatoriasPrev($deducoes_obrigatorias_prev){
		$this->deducoes_obrigatorias_prev = $deducoes_obrigatorias_prev;
	}	
			
	public function getDemaisDeducoes(){
		return $this->demais_deducoes;
	}

	public function setDemaisDeducoes($demais_deducoes){
		$this->demais_deducoes = $demais_deducoes;
	}	
			
	public function getRemuneracaoAposDeducoes(){
		return $this->remuneracao_apos_deducoes;
	}

	public function setRemuneracaoAposDeducoes($remuneracao_apos_deducoes){
		$this->remuneracao_apos_deducoes = $remuneracao_apos_deducoes;
	}	
			
	public function getVerbasSalarioFamilia(){
		return $this->verbas_salario_familia;
	}

	public function setVerbasSalarioFamilia($verbas_salario_familia){
		$this->verbas_salario_familia = $verbas_salario_familia;
	}	
		
	public function getVerbasJetons(){
		return $this->verbas_jetons;
	}

	public function setVerbasJetons($verbas_jetons){
		$this->verbas_jetons = $verbas_jetons;
	}	

	public function getDemaisVerbas(){
		return $this->demais_verbas;
	}

	public function setDemaisVerbas($demais_verbas){
		$this->demais_verbas = $demais_verbas;
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