<?php

class SubfuncaoBO{

	public function create(Subfuncao $subfuncao, $conn=null){
    	SubfuncaoBO::validate($subfuncao);
    	$subfuncao->save($conn);
		$subfuncao->free();
		
    	return $subfuncao;
	}

	public function get($subfuncaoId){
        return Doctrine::getTable('Subfuncao')->find($subfuncaoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 's.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('s.*')
              ->from('Subfuncao s');

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
		$subfuncao = $pager->execute();
		
		$search->setPager($pager);
		
		$subfuncaoDTO = new DTO();
		$subfuncaoDTO->setObj($subfuncao);
		$subfuncaoDTO->setSearch($search);

		return $subfuncaoDTO;
	}

    public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $subfuncao = new Subfuncao();
            $subfuncao->setImportacaoId($importacaoId);
            $subfuncao->setExercicio(mb_substr($line, 0,4));
            $subfuncao->setCodSubfuncao(mb_substr($line, 4,3));
			$subfuncao->setNomeSubfuncao(mb_substr($line, 7,80));

            SubfuncaoBO::create($subfuncao, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Subfuncao s');
        $query->where('s.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     

	public function validate(Subfuncao $subfuncao){}
}