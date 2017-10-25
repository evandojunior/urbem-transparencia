<?php

class Grupo extends BaseGrupo{

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}	

	public function getGrupo(){
	    return $this->grupo;
	}

	public function setGrupo($grupo){
	    $this->grupo = $grupo;
	}

	public function getAlias(){
	    return $this->alias;
	}

	public function setAlias($alias){
	    $this->alias = $alias;
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
	
	public function getUsuario(){
		return $this->Usuario;
	}
	
	public function setUsuario(Usuario $usuario){
		$this->Usuario = $usuario;
	}	
	
	public function getGrupoAcao(){
		return $this->GrupoAcao;
	}
	
	public function setGrupoAcao(GrupoAcao $grupoAcao){
		$this->GrupoAcao = $grupoAcao;
	}
	
	public function getContatoGrupo(){
		return $this->ContatoGrupo;
	}
	
	public function setContatoGrupo(ContatoGrupo $contatoGrupo){
		$this->ContatoGrupo = $contatoGrupo;
	}
}

class GrupoAcao extends BaseGrupoAcao {
    
    public function getId(){
    	return $this->id;
    }
    
    public function setId($id){
    	$this->id = $id;
    }
    
    public function getGrupoId(){
    	return $this->grupo_id;
    }
    
    public function setGrupoId($grupo_id){
    	$this->grupo_id = $grupo_id;
    }
    
    public function getAcaoId(){
    	return $this->acao_id;
    }
    
    public function setAcaoId($acao_id){
    	$this->acao_id = $acao_id;
    }
    
    /*
     * Relacionamentos
    */
    
    public function getGrupo(){
    	return $this->Grupo;
    }
    
    public function setGrupo(Grupo $grupo){
    	$this->Grupo = $grupo;
    }
    
    public function getAcao(){
    	return $this->_Acao;
    }
    
    public function setAcao(_Acao $acao){
    	$this->_Acao = $acao;
    }
}