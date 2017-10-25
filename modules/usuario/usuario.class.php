<?php

class Usuario extends BaseUsuario {

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
	
    public function getGrupoId(){
        return $this->grupo_id;
    }

    public function setGrupoId($grupo_id){
        $this->grupo_id = $grupo_id;
    }
	
    public function getMunicipioId(){
        return $this->municipio_id;
    }

    public function setMunicipioId($municipio_id){
        $this->municipio_id = $municipio_id;
    }	

    public function getStatus(){
            return $this->status;
    }

    public function setStatus($status){
            $this->status = $status;
    }
    
    public function getSenha(){
            return $this->senha;
    }

    public function setSenha($senha){
            $this->senha = md5($senha);
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
	
    public function getPessoa(){
        return $this->Pessoa;
    }

    public function setPessoa(Pessoa $pessoa){
        $this->Pessoa = $pessoa;
    }
	
    public function getGrupo(){
        return $this->Grupo;
    }

    public function setGrupo(Grupo $grupo){
        $this->Grupo = $grupo;
    }
	
    public function getMunicipio(){
        return $this->Municipio;
    }

    public function setMunicipio(Municipio $municipio){
        $this->Municipio = $municipio;
    }	
}
