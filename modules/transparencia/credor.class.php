<?php

class Credor extends BaseCredor {

	public function getId(){
		return $this->id;
	}

	public function setId($id){
		$this->id = $id;
	}
	
    public function getImportacaoId(){
            return $this->importacao_id;
    }

    public function setImportacaoId($importacao_id){
            $this->importacao_id = $importacao_id;
    }
    
	public function getCodCredor(){
		return $this->cod_credor;
	}

	public function setCodCredor($cod_credor){
		$this->cod_credor = $cod_credor;
	}	
	
	public function getNomeCredor(){
		return $this->nome_credor;
	}

	public function setNomeCredor($nome_credor){
		$this->nome_credor = $nome_credor;
	}		
	
	public function getCnpjCpfCredor(){
		return $this->cnpj_cpf_credor;
	}

	public function setCnpjCpfCredor($cnpj_cpf_credor){
		$this->cnpj_cpf_credor = $cnpj_cpf_credor;
	}
	
    /*
     * Relacionamentos
     */	
	
	public function getImportacao(){
		return $this->Importacao;
	}

	public function setImportacao(Importacao $importacao){
		$this->Importacao = $importacao;
	}	
}