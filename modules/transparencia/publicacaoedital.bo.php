<?php

class PublicacaoEditalBO{

	public function create(PublicacaoEdital $publicacaoEdital, $conn=null){
    	PublicacaoEditalBO::validate($publicacaoEdital);
    	$publicacaoEdital->save($conn);
		$publicacaoEdital->free();
    	return $publicacaoEdital;
	}

	public function get($publicacaoEditalId){
        return Doctrine::getTable('PublicacaoEdital')->find($publicacaoEditalId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'p.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('p.*')
              ->from('PublicacaoEdital p');

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
		$publicacaoEdital = $pager->execute();
		
		$search->setPager($pager);
		
		$publicacaoEditalDTO = new DTO();
		$publicacaoEditalDTO->setObj($publicacaoEdital);
		$publicacaoEditalDTO->setSearch($search);

		return $publicacaoEditalDTO;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $publicacaoEdital = new PublicacaoEdital();
            $publicacaoEdital->setImportacaoId($importacaoId);

			$publicacaoEdital->setExercicioEdital(mb_substr($line, 0, 4));
			$publicacaoEdital->setNumEdital(mb_substr($line, 4, 8));
			$publicacaoEdital->setExercicioLicitacao(mb_substr($line, 12, 4));
			$publicacaoEdital->setCodLicitacao(mb_substr($line, 16, 8));
			$publicacaoEdital->setCodEntidade(mb_substr($line, 24, 2));
			$publicacaoEdital->setModalidade(mb_substr($line, 26, 50));
			$publicacaoEdital->setVeiculoPublicacao(mb_substr($line, 76, 80));
            $date = mb_substr($line,156,8);
            $publicacaoEdital->setDataPublicacao((mb_substr($date, 4, 4).'-'.mb_substr($date, 2, 2).'-'.mb_substr($date, 0, 2)));
			$publicacaoEdital->setObservacao(mb_substr($line, 164, 50));
            
            PublicacaoEditalBO::create($publicacaoEdital, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('PublicacaoEdital p');
        $query->where('p.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }       
		
	public function validate(PublicacaoEdital $publicacaoEdital){}
}