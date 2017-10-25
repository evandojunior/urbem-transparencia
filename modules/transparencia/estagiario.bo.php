<?php

class EstagiarioBO{

	public function create(Estagiario $estagiario, $conn=null){
    	EstagiarioBO::validate($estagiario);
    	$estagiario->save($conn);
		$estagiario->free();

    	return $estagiario;
	}

	public function get($estagiarioId){
        return Doctrine::getTable('Estagiario')->find($estagiarioId);
	}

	public function getEstagiario(){
        $query = new Doctrine_Query();
        $query->select("e.id, e.nome, TO_CHAR(e.data_inicio, 'dd/mm/yyyy') as data_inicio, TO_CHAR(e.data_fim, 'dd/mm/yyyy') as data_fim, TO_CHAR(e.data_renovacao, 'dd/mm/yyyy') as data_renovacao")
              ->from('Estagiario e')
              ->groupBy('e.id, e.nome, e.data_inicio, e.data_fim, e.data_renovacao')
			  ->orderBy('e.nome');

        return $query;
	}
	
	public function filter(Search $search, $method = ''){
	
		$query = EstagiarioBO::$method();

		if(isset($request['competencia'])){
			$query->where("e.mes_ano like '".$request['competencia']."'");
		}
		
		$query->orderBy("e.importacao_id DESC, e.mes_ano DESC, e.nome ASC");
		
		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$estagiario = $pager->execute();
		
		$search->setPager($pager);
		
		$estagiarioDTO = new DTO();
		$estagiarioDTO->setObj($estagiario);
		$estagiarioDTO->setSearch($search);

		return $estagiarioDTO;
	}
	
	public function geraRelatorio($request, $method){
		$query = EstagiarioBO::$method();
		
		if(isset($request['competencia'])){
			$query->where("e.mes_ano like '".$request['competencia']."'");
		}
		
		return $query;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $estagiario = new Estagiario();
            $estagiario->setImportacaoId($importacaoId);
            $estagiario->setCodEntidade(mb_substr($line,0,2));
            $estagiario->setMesAno(mb_substr($line,2,7));
            $estagiario->setNumeroEstagio(mb_substr($line,9,8));
            $estagiario->setNome(mb_substr($line,17,60));
            
            $date = mb_substr($line,77,8);
            $estagiario->setDataInicio((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            $date = trim(mb_substr($line,85,8));
            if($date != ''){
                $estagiario->setDataFim((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            $date = trim(mb_substr($line,93,8));
            if($date != ''){
                $estagiario->setDataRenovacao((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
            }
            
            $estagiario->setDescricaoLotacao(mb_substr($line,101,60));
            $estagiario->setDescricaoLocal(mb_substr($line,161,60));
            
		    EstagiarioBO::create($estagiario, $conn);
		}
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Estagiario e');
        $query->where('e.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }      
		
	public function validate(Estagiario $estagiario){}
}