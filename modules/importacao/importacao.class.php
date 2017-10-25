<?php

class Importacao extends BaseImportacao {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	    
	public function getTimestamp(){
		return $this->timestamp;
	}

	public function setTimestamp($timestamp){
		$this->timestamp = $timestamp;
	}
    
	public function getExercicio(){
		return $this->exercicio;
	}

	public function setExercicio($exercicio){
		$this->exercicio = $exercicio;
	}
	    
	public function getTimestampGeracao(){
		return $this->timestamp_geracao;
	}

	public function setTimestampGeracao($timestamp_geracao){
		$this->timestamp_geracao = $timestamp_geracao;
	}
    
	public function getDataLimiteDado(){
		return $this->data_limite_dado;
	}

	public function setDataLimiteDado($data_limite_dado){
		$this->data_limite_dado = $data_limite_dado;
	}     
    
	public function getUsuario(){
		return $this->usuario;
	}

	public function setUsuario($usuario){
		$this->usuario = $usuario;
	}    
}
