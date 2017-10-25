<?php

class Pessoa extends BasePessoa {

    public function getId(){
            return $this->id;
    }

    public function setId($id){
            $this->id = $id;
    }

    public function getCategoriaId(){
            return $this->categoria_id;
    }

    public function setCategoriaId($categoria_id){
            $this->categoria_id = $categoria_id;
    }

    public function getNome(){
            return $this->nome;
    }

    public function setNome($nome){
            $this->nome = $nome;
    }

    public function getEmail(){
            return $this->email;
    }

    public function setEmail($email){
            $this->email = $email;
    }

    public function getDDDCelular(){
            return $this->ddd_celular;
    }

    public function setDDDCelular($ddd_celular){
            $this->ddd_celular = $ddd_celular;
    }

    public function getTelefoneCelular(){
            return $this->telefone_celular;
    }

    public function setTelefoneCelular($telefone_celular){
            $this->telefone_celular = $telefone_celular;
    }

    public function getDDDComercial(){
            return $this->telefone_comercial;
    }

    public function setDDDComercial($ddd_comercial){
            $this->ddd_comercial = $ddd_comercial;
    }

    public function getTelefoneComercial(){
            return $this->telefone_comercial;
    }

    public function setTelefoneComercial($telefone_comercial){
            $this->telefone_comercial = $telefone_comercial;
    }

    public function getFacebook(){
            return $this->facebook;
    }

    public function setFacebook($facebook){
            $this->facebook = $facebook;
    }
    
    public function getTwitter(){
            return $this->twitter;
    }

    public function setTwitter($twitter){
            $this->twitter = $twitter;
    }

    public function getObservacao(){
            return $this->observacao;
    }

    public function setObservacao($observacao){
            $this->observacao = $observacao;
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

    public function getCategoria(){
        return $this->Categoria;
    }

    public function setCategoria(Categoria $categoria){
        $this->Categoria = $categoria;
    }

    public function getUsuario(){
        return $this->Usuario;
    }

    public function setUsuario(Usuario $usuario){
        $this->Usuario = $usuario;
    }
}