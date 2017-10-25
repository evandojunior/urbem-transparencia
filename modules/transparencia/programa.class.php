<?php

class Programa extends BasePrograma {

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
    	
	public function getExercicio(){
		return $this->exercicio;
	}

	public function setExercicio($exercicio){
		$this->exercicio = $exercicio;
	}    
    
	public function getCodPrograma(){
		return $this->cod_programa;
	}

	public function setCodPrograma($cod_programa){
		$this->cod_programa = $cod_programa;
	}	
	
	public function getNomePrograma(){
		return $this->nome_programa;
	}

	public function setNomePrograma($nome_programa){
		$this->nome_programa = $nome_programa;
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