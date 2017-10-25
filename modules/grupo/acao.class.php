<?php

class _Acao extends _BaseAcao{

    public function getId(){
    	return $this->id;
    }
    
    public function setId($id){
    	$this->id = $id;
    }
    
    public function getAlias(){
    	return $this->alias;
    }
    
    public function setAlias($alias){
    	$this->alias = $alias;
    }
    
    public function getDescricao(){
    	return $this->descricao;
    }
    
    public function setDescricao($descricao){
    	$this->descricao = $descricao;
    }
    
    /*
     * Relacionamentos
     */
    
    public function getMenu(){
    	return $this->Menu;
    }
    
    public function setMenu(Menu $menu){
    	$this->Menu = $menu;
    }
    
    public function getGrupoAcao(){
    	return $this->GrupoAcao;
    }
    
    public function setGrupoAcao(GrupoAcao $grupoAcao){
    	$this->GrupoAcao = $grupoAcao;
    } 
}