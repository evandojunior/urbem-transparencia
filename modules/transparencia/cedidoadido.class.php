<?php

class CedidoAdido extends BaseCedidoAdido {

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
	
	public function getMatricula(){
		return $this->matricula;
	}

	public function setMatricula($matricula){
		$this->matricula = $matricula;
	}	
	
	public function getNomCgm(){
		return $this->nom_cgm;
	}

	public function setNomCgm($nom_cgm){
		$this->nom_cgm = $nom_cgm;
	}
	
	public function getSituacao(){
		return $this->situacao;
	}

	public function setSituacao($situacao){
		$this->situacao = $situacao;
	}
	
	public function getAtoCedencia(){
		return $this->ato_cedencia;
	}

	public function setAtoCedencia($ato_cedencia){
		$this->ato_cedencia = $ato_cedencia;
	}	
		
	public function getDtInicial(){
		return $this->dt_inicial;
	}

	public function setDtInicial($dt_inicial){
		$this->dt_inicial = $dt_inicial;
	}	
	
	public function getDtFinal(){
		return $this->dt_final;
	}

	public function setDtFinal($dt_final){
		$this->dt_final = $dt_final;
	}		
		
	public function getTipoCedencia(){
		return $this->tipo_cedencia;
	}

	public function setTipoCedencia($tipo_cedencia){
		$this->tipo_cedencia = $tipo_cedencia;
	}	
		
	public function getIndicativoOnus(){
		return $this->indicativo_onus;
	}

	public function setIndicativoOnus($indicativo_onus){
		$this->indicativo_onus = $indicativo_onus;
	}	
		
	public function getOrgaoCedenteCessionario(){
		return $this->orgao_cedente_cessionario;
	}

	public function setOrgaoCedenteCessionario($orgao_cedente_cessionario){
		$this->orgao_cedente_cessionario = $orgao_cedente_cessionario;
	}	
	
	public function getNumConvenio(){
		return $this->num_convenio;
	}	
	
	public function setNumConvenio($num_convenio){
		$this->num_convenio = $num_convenio;
	}	
			
	public function getLocal(){
		return $this->local;
	}	
	
	public function setLocal($local){
		$this->local = $local;
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