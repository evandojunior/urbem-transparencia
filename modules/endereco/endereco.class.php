<?php

class Endereco extends BaseEndereco {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getCEPId(){
		return $this->cep_id;
	}

	public function setCEPId($cep_id){
		$this->cep_id = $cep_id;
	}

	public function getCEP(){
		return $this->CEP;
	}

	public function setCEP($cep){
		$this->CEP = $cep;
	}

	public function getNumero(){
		return $this->numero;
    }

	public function setNumero($numero){
		$this->numero = $numero;
	}
	
	public function getComplemento(){
		return $this->complemento;
	}

	public function setComplemento($complemento){
		$this->complemento = $complemento;
	}	

	public function getTipo(){
		return $this->tipo;
	}

	public function setTipo($tipo){
		$this->tipo = $tipo;
	}	
}

class Municipio extends BaseMunicipio{

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	
	public function getAlias(){
		return $this->alias;
	}

	public function setAlias($alias){
		$this->alias = $alias;
	}
	
	public function getUFId(){
		return $this->uf_id;
	}

	public function setUFId(UF $uf_id){
		$this->uf_id = $uf_id;
	}	
	
	public function getUF(){
		return $this->UF;
	}

	public function setUF(UF $uf){
		$this->UF = $uf;
	}	

	public function getDisponivel(){
		return $this->disponivel;
	}

	public function setDisponivel($disponivel){
		$this->disponivel = $disponivel;
	}
	
	public function getHash(){
		return $this->hash;
	}

	public function setHash($hash){
		$this->hash = $hash;
	}
	
	public function getDB(){
		return $this->db;
	}

	public function setDB($db){
		$this->db = $db;
	}
}

class UF extends BaseUF{

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getSigla(){
		return $this->sigla;
	}

	public function setSigla($sigla){
		$this->sigla = $sigla;
	}
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}	

	public function getDisponivel(){
		return $this->disponivel;
	}

	public function setDisponivel($disponivel){
		$this->disponivel = $disponivel;
	}
}

class CEP extends BaseCEP{

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getMunicipioId(){
		return $this->importacao_id;
	}

	public function setMunicipioId($municipio_id){
		$this->municipio_id = $municipio_id;
	}
	
	public function getMunicipio(){
		return $this->Municipio;
	}

	public function setMunicipio(Municipio $municipio){
		$this->Municipio = $municipio;
	}	

	public function getBairro(){
		return $this->bairro;
	}

	public function setBairro($bairro){
		$this->bairro = $bairro;
	}		
	
	public function getLogradouro(){
		return $this->logradouro;
	}

	public function setLogradouro($logradouro){
		$this->logradouro = $logradouro;
	}	
	
	public function getNumeroCEP(){
		return substr($this->numero_cep, 0, 2).".".substr($this->numero_cep, 2, 3)."-".substr($this->numero_cep, 5);
	}

	public function setNumeroCEP($numero_cep){
		$this->numero_cep = str_replace('-', '', str_replace('.', '', $numero_cep));
    }
}

