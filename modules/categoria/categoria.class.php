<?php

class Categoria extends BaseCategoria {

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
	
	public function getCategoria(){
		return $this->categoria;
	}

	public function setCategoria($categoria){
		$this->categoria = $categoria;
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
	
	public function getConteudo(){
		return $this->Conteudo;
	}

	public function setConteudo(Conteudo $conteudo){
		$this->Conteudo = $conteudo;
	}	
	
	public function getBanner(){
		return $this->Banner;
	}

	public function setBanner(Banner $banner){
		$this->Banner = $banner;
	}	
	
	public function getPessoa(){
		return $this->Pessoa;
	}
	
	public function setPessoa(Pessoa $pessoa){
		$this->Pessoa = $pessoa;
	}

	public function getContato(){
		return $this->Contato;
	}
	
	public function setContato(Contato $contato){
		$this->Contato = $contato;
	}	
}
