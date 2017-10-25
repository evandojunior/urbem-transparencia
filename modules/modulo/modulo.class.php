<?php

class Modulo extends BaseModulo {

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
		
	public function getModulo(){
		return $this->modulo;
	}

	public function setModulo($modulo){
		$this->modulo = $modulo;
	}
	
    /*
     * Relacionamentos
     */	

    public function getConfiguracao(){
		return $this->Configuracao;
	}

	public function setConfiguracao(Configuracao $configuracao){
		$this->configuracao = $configuracao;
	}	

    public function getHistorico(){
		return $this->Historico;
	}

	public function setHistorico(Historico $historico){
		$this->historico = $historico;
	}	
}
