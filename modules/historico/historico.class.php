<?php

class Historico extends BaseHistorico {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getPessoaId(){
		return $this->pessoa_id;
	}

	public function setPessoaId($pessoa_id){
		$this->pessoa_id = $pessoa_id;
	}

	public function getModuloId(){
		return $this->modulo_id;
	}

	public function setModuloId($modulo_id){
		$this->modulo_id = $modulo_id;
	}

	public function getEntidadeId(){
		return $this->entidade_id;
	}

	public function setEntidadeId($entidade_id){
		$this->entidade_id = $entidade_id;
	}
	
	public function getDescricao(){
		return $this->descricao;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}	
	
	/*
	 * Timestamp
	 */	
	
	public function getCreated(){
		return formatTimestampToPHP($this->created);
	}

	public function setCreated($created_at){
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
	
	public function getPessoa(){
		return $this->Pessoa;
	}
	
	public function setPessoa(Pessoa $pessoa){
		$this->Pessoa = $pessoa;
	}	
	
	public function getModulo(){
		return $this->Modulo;
	}
	
	public function setModulo(Modulo $modulo){
		$this->Modulo = $modulo;
	}	
}
