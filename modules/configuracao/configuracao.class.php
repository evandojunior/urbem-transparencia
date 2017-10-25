<?php

class Configuracao extends BaseConfiguracao {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}

	public function getModuloId(){
		return $this->modulo_id;
	}

	public function setModuloId($modulo_id){
		$this->modulo_id = $modulo_id;
	}

	public function getMunicipioId(){
		return $this->municipio_id;
	}

	public function setMunicipioId($municipio_id){
		$this->municipio_id = $municipio_id;
	}	

	public function getAlias(){
		return $this->alias;
	}

	public function setAlias($alias){
		$this->alias = $alias;
	}	
	
	public function getParametro(){
		return $this->parametro;
	}

	public function setParametro($parametro){
		$this->parametro = $parametro;
	}
	
	public function getValor(){
		return $this->valor;
	}

	public function setValor($valor){
		$this->valor = $valor;
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
	
	public function getModulo(){
		return $this->Modulo;
	}

	public function setModulo(Modulo $modulo){
		$this->Modulo = $modulo;
	}
	
	public function getMunicipio(){
		return $this->Municipio;
	}

	public function setMunicipio(Municipio $municipio){
		$this->Municipio = $municipio;
	}
}