<?php

class Pagamento extends BasePagamento {

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
	
	public function getNumeroPagamento(){
		return $this->numero_pagamento;
	}

	public function setNumeroPagamento($numero_pagamento){
		$this->numero_pagamento = $numero_pagamento;
	}
		
	public function getDataPagamento(){
		return $this->data_pagamento;
	}

	public function setDataPagamento($data_pagamento){
		$this->data_pagamento = $data_pagamento;
	}

	public function getValorPagamento(){
		return $this->valor_pagamento;
	}

	public function setValorPagamento($valor_pagamento){
		$this->valor_pagamento = $valor_pagamento;
	}

	public function getSinalValor(){
		return $this->sinal_valor;
	}

	public function setSinalValor($sinal_valor){
		$this->sinal_valor = $sinal_valor;
	}
	
	public function getHistoricoPagamento(){
		return $this->historico_pagamento;
	}

	public function setHistoricoPagamento($historico_pagamento){
		$this->historico_pagamento = $historico_pagamento;
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