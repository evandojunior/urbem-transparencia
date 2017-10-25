<?php

class Sessao{
	
	/*
	 * Métodos de uso geral magic_methods
	 */
	
	public function set($chave, $valor){
		$_SESSION[$chave] = $valor;
	}
	
	public function get($chave){
		if(isset($_SESSION[$chave])){
			return $_SESSION[$chave];
		} else {
			return false;	
		}
	}	
	
	public function setPermissoes($acoes){
        foreach($acoes as $acao){
            $_SESSION['usuario_acao'][] = $acao->alias;
        }
	}
	
	public function getPermissoes(){
		return $_SESSION['usuario_acao'];
	}
	
	/*
	 * Dados referentes ao site
	 */
	
	public function clean(){
		//Preserva variável site OBS: necessita aperfeiçoar o código
		$site = $_SESSION['site'];
		session_destroy();
		if (!isset($_SESSION)) { session_start(); } ;
		$_SESSION['site'] = $site;
	}
	
	public function validate(){
	    if(isset($_SESSION['usuario_id'])){
       		return true;
       	} else {
       		return false;
       	}
	}	
}
