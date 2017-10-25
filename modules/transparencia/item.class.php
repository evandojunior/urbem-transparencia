<?php

class Item extends BaseItem {

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
	
	public function getNumeroEmpenho(){
		return $this->numero_empenho;
	}

	public function setNumeroEmpenho($numero_empenho){
		$this->numero_empenho = $numero_empenho;
	}

	public function getCodEntidade(){
		return $this->cod_entidade;
	}

	public function setCodEntidade($cod_entidade){
		$this->cod_entidade = $cod_entidade;
	}	

	public function getExercicio(){
		return $this->exercicio;
	}

	public function setExercicio($exercicio){
		$this->exercicio = $exercicio;
	}	

	public function getData(){
		return $this->data;
	}

	public function setData($data){
		$this->data = $data;
	}
	
	public function getNumeroItem(){
		return $this->numero_item;
	}

	public function setNumeroItem($numero_item){
		$this->numero_item = $numero_item;
	}
	
	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getUnidade(){
		return $this->unidade;
	}

	public function setUnidade($unidade){
		$this->unidade = $unidade;
	}	

	public function getQuantidade(){
		return $this->quantidade;
	}

	public function setQuantidade($quantidade){
		$this->quantidade = $quantidade;
	}	

	public function getValor(){
		return $this->valor;
	}

	public function setValor($valor){
		$this->valor = $valor;
	}	

	public function getSinalValor(){
		return $this->sinal_valor;
	}

	public function setSinalValor($sinal_valor){
		$this->sinal_valor = $sinal_valor;
	}

	public function getComplemento(){
		return $this->complemento;
	}

	public function setComplemento($complemento){
		$this->complemento = $complemento;
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