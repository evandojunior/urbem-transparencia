<?php

class Contato extends BaseContato {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
	public function getConfiguracaoId(){
		return $this->configuracao_id;
	}

	public function setConfiguracaoId($configuracao_id){
		$this->configuracao_id = $configuracao_id;
	}	
	
	public function getAssunto(){
		return $this->assunto;
	}

	public function setAssunto($assunto){
		$this->assunto = $assunto;
	}	
	
	public function getNome(){
		return $this->nome;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}	
	
	public function getDDD(){
		return $this->ddd;
	}

	public function setDDD($ddd){
		$this->ddd = $ddd;
	}
	
	public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}	

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getMensagem(){
		return $this->mensagem;
	}

	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}	
	
	public function getStatus(){
		return $this->status;
	}

	public function setStatus($status){
		$this->status = $status;
	}

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
	
	public function getConfiguracao(){
		return $this->Configuracao;
	}

	public function setConfiguracao(Configuracao $configuracao){
		$this->Configuracao = $configuracao;
	}	
}