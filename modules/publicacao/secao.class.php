<?php

class Secao extends BaseSecao {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getParentId(){
		return $this->parent_id;
	}
	
	public function setParentId($parent_id){
		$this->parent_id = $parent_id;
	}	
	
	public function getAlias(){
		return $this->alias;
	}
	
	public function setAlias($alias){
		$this->alias = $alias;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function setType($type){
		$this->alias = $type;
	}	
	
	public function getSecao(){
		return $this->secao;
	}

	public function setSecao($secao){
		$this->secao = $secao;
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
	
	public function getPublicacao(){
		return $this->Publicacao;
	}

	public function setPublicacao(Publicacao $publicacao){
		$this->Publicacao = $publicacao;
	}	
}
