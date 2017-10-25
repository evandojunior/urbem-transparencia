<?php

class Log extends BaseLog {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
    public function getMunicipioId(){
            return $this->municipio_id;
    }

    public function setMunicipioId($municipio_id){
            $this->municipio_id = $municipio_id;
    }	
	
	public function getRemessa(){
		return $this->remessa;
	}

	public function setRemessa($remessa){
		$this->remessa = $remessa;
	}	
	
	public function getArquivo(){
		return $this->arquivo;
	}

	public function setArquivo($arquivo){
		$this->arquivo = $arquivo;
	}
	
	public function getMensagem(){
		return $this->mensagem;
	}

	public function setMensagem($mensagem){
		$this->mensagem = $mensagem;
	}	

	public function getExcecao(){
		return $this->excecao;
	}

	public function setExcecao($excecao){
		$this->excecao = $excecao;
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
	
    /*
     * Relacionamentos
     */

    public function getMunicipio(){
        return $this->Municipio;
    }

    public function setMunicipio(Municipio $municipio){
        $this->Municipio = $municipio;
    }	
}