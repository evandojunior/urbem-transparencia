<?php

class Liquidacao extends BaseLiquidacao {

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
    
	public function getCodEmpenho(){
		return $this->cod_empenho;
	}

	public function setCodEmpenho($cod_empenho){
		$this->cod_empenho = $cod_empenho;
	}	    
	   
	public function getCodEntidade(){
		return $this->cod_entidade;
	}

	public function setCodEntidade($cod_entidade){
		$this->cod_entidade = $cod_entidade;
	}	
	
	public function getCodLiquidacao(){
		return $this->cod_liquidacao;
	}

	public function setCodLiquidacao($cod_liquidacao){
		$this->cod_liquidacao = $cod_liquidacao;
	}
		
	public function getDataLiquidacao(){
		return $this->data_liquidacao;
	}

	public function setDataLiquidacao($data_liquidacao){
		$this->data_liquidacao = $data_liquidacao;
	}

	public function getValorLiquidacao(){
		return $this->valor_liquidacao;
	}

	public function setValorLiquidacao($valor_liquidacao){
		$this->valor_liquidacao = $valor_liquidacao;
	}

	public function getExercicioEmpenho(){
		return $this->exercicio_empenho;
	}

	public function setExercicioEmpenho($exercicio_empenho){
		$this->exercicio_empenho = $exercicio_empenho;
	}	

	public function getSinalValor(){
		return $this->sinal_valor;
	}

	public function setSinalValor($sinal_valor){
		$this->sinal_valor = $sinal_valor;
	}
	
	public function getHistoricoLiquidacao(){
		return $this->historico_liquidacao;
	}

	public function setHistoricoLiquidacao($historico_liquidacao){
		$this->historico_liquidacao = $historico_liquidacao;
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