<?php

class UnidadeBO{

	public function create(Unidade $unidade, $conn=null){
    	UnidadeBO::validate($unidade);
    	$unidade->save($conn);
		$unidade->free();
		
    	return $unidade;
	}

	public function get($unidadeId){
        return Doctrine::getTable('Unidade')->find($unidadeId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'u.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('u.*')
              ->from('Unidade u');

        if($search->getFilter() != null){
			$query->where($fields[$search->getFilter()].' LIKE ?', '%'.$search->getQ().'%');
		}
		
		if($search->getOrder() != null){
			$order = $fields[$search->getOrder()];
				
			if($search->getDirection() != null){
                $order.= ' '.$search->getDirection();
			}

            $query->orderBy($order);
		}

		$pager = new Doctrine_Pager($query, $search->getPage(), $search->getMax());
		$unidade = $pager->execute();
		
		$search->setPager($pager);
		
		$unidadeDTO = new DTO();
		$unidadeDTO->setObj($unidade);
		$unidadeDTO->setSearch($search);

		return $unidadeDTO;
	}

    public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $unidade = new Unidade();
            $unidade->setImportacaoId($importacaoId);
            $unidade->setExercicio(mb_substr($line, 0,4));
            $unidade->setCodOrgao(mb_substr($line, 4,5));
			$unidade->setCodUnidade(mb_substr($line, 9,5));
			$unidade->setNomeUnidade(mb_substr($line, 14,80));

            UnidadeBO::create($unidade, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Unidade u');
        $query->where('u.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }      

	public function validate(Unidade $unidade){}
}