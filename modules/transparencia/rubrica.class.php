<?php

class Rubrica extends BaseRubrica {

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
    
	public function getCodRubricaDespesa(){
		return $this->cod_rubrica_despesa;
	}

	public function setCodRubricaDespesa($cod_rubrica_despesa){
		$this->cod_rubrica_despesa = $cod_rubrica_despesa;
	}	
	
	public function getEspecificacaoRubricaDespesa(){
		return $this->especificacao_rubrica_despesa;
	}

	public function setEspecificacaoRubricaDespesa($especificacao_rubrica_despesa){
		$this->especificacao_rubrica_despesa = $especificacao_rubrica_despesa;
	}

	public function getTipoNivelConta(){
		return $this->tipo_nivel_conta;
	}

	public function setTipoNivelConta($tipo_nivel_conta){
		$this->tipo_nivel_conta = $tipo_nivel_conta;
	}
	
	public function getNumeroNivelConta(){
		return $this->numero_nivel_conta;
	}

	public function setNumeroNivelConta($numero_nivel_conta){
		$this->numero_nivel_conta = $numero_nivel_conta;
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