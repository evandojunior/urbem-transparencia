<?php

class Publicacao extends BasePublicacao {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}

	public function getSecaoId(){
		return $this->secao_id;
	}
	
	public function setSecaoId($secao_id){
		$this->secao_id = $secao_id;
	}
	
	public function getDescricao(){
		return $this->descricao;
	}
	
	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}	

	public function getDetalhamento(){
		return $this->detalhamento;
	}
	
	public function setDetalhamento($detalhamento){
		$this->detalhamento = $detalhamento;
	}

	public function getStatus(){
		return $this->status;
	}
	
	public function setStatus($status){
		$this->status = $status;
	}

	public function getArquivo(){
		return $this->arquivo;
	}
	
	public function setArquivo($arquivo){
		$this->arquivo = $arquivo;
	}
	
	/*
	 * Timestamp
	 */
	
	public function getCreated(){
		return formatTimestampToPHP($this->created);
	}
	
	public function setCreated($created){
		$this->created = $created;
	}
	
	public function getUpdated(){
		return formatTimestampToPHP($this->updated);
	}
	
	public function setUpdated($updated){
		$this->updated = $updated;
	}
		
	
	/*
	 * Relacionamentos
	 */
	
	public function getSecao(){
		return $this->Secao;
	}

	public function setSecao(Secao $secao){
		$this->Secao = $secao;
	}	
}
