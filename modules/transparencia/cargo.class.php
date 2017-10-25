<?php

class Cargo extends BaseCargo {

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
    
	public function getCodEntidade(){
		return $this->cod_entidade;
	}

	public function setCodEntidade($cod_entidade){
		$this->cod_entidade = $cod_entidade;
	}	
	
	public function getMesAno(){
		return $this->mes_ano;
	}

	public function setMesAno($mes_ano){
		$this->mes_ano = $mes_ano;
	}
	
	public function getCodigo(){
		return $this->codigo;
	}

	public function setCodigo($codigo){
		$this->codigo = $codigo;
	}	
	
	public function getDescricaoCargo(){
		return $this->descricao_cargo;
	}

	public function setDescricaoCargo($descricao_cargo){
		$this->descricao_cargo = $descricao_cargo;
	}
	
	public function getTipoCargo(){
		return $this->tipo_cargo;
	}

	public function setTipoCargo($tipo_cargo){
		$this->tipo_cargo = $tipo_cargo;
	}
	
	public function getLei(){
		return $this->lei;
	}

	public function setLei($lei){
		$this->lei = $lei;
	}	
		
	public function getDescricaoPadrao(){
		return $this->descricao_padrao;
	}

	public function setDescricaoPadrao($descricao_padrao){
		$this->descricao_padrao = $descricao_padrao;
	}	
		
	public function getCargaHorariaMensal(){
		return $this->carga_horaria_mensal;
	}

	public function setCargaHorariaMensal($carga_horaria_mensal){
		$this->carga_horaria_mensal = $carga_horaria_mensal;
	}	
		
	public function getCargaHorariaSemanal(){
		return $this->carga_horaria_semanal;
	}

	public function setCargaHorariaSemanal($carga_horaria_semanal){
		$this->carga_horaria_semanal = $carga_horaria_semanal;
	}	
		
	public function getValor(){
		return $this->valor;
	}

	public function setValor($valor){
		$this->valor = $valor;
	}	
	
	public function getVigencia(){
		return $this->vigencia;
	}	
	
	public function setVigencia($vigencia){
		$this->vigencia = $vigencia;
	}	
			
	public function getRegimeSubdivisao(){
		return $this->regime_subdivisao;
	}	
	
	public function setRegimeSubdivisao($regime_subdivisao){
		$this->regime_subdivisao = $regime_subdivisao;
	}	
	
	public function getVagasCriadas(){
		return $this->vagas_criadas;
	}	
	
	public function setVagasCriadas($vagas_criadas){
		$this->vagas_criadas = $vagas_criadas;
	}

	public function getVagasOcupadas(){
		return $this->vagas_ocupadas;
	}	
	
	public function setVagasOcupadas($vagas_ocupadas){
		$this->vagas_ocupadas = $vagas_ocupadas;
	}
	
	public function getVagasDisponiveis(){
		return $this->vagas_disponiveis;
	}	
	
	public function setVagasDisponiveis($vagas_disponiveis){
		$this->vagas_disponiveis = $vagas_disponiveis;
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