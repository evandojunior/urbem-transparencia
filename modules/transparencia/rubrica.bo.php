<?php

class RubricaBO{

	public function create(Rubrica $rubrica, $conn=null){
    	RubricaBO::validate($rubrica);
    	$rubrica->save($conn);
		$rubrica->free();

    	return $rubrica;
	}

	public function get($rubricaId){
        return Doctrine::getTable('Rubrica')->find($rubricaId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'p.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('p.*')
              ->from('Rubrica p');

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
		$rubrica = $pager->execute();
		
		$search->setPager($pager);
		
		$rubricaDTO = new DTO();
		$rubricaDTO->setObj($rubrica);
		$rubricaDTO->setSearch($search);

		return $rubricaDTO;
	}

    public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');

        while(($line = fgets($data)) !== false) {
            $rubrica = new Rubrica();
            $rubrica->setImportacaoId($importacaoId);
            $rubrica->setExercicio(mb_substr($line, 0, 4));
			$rubrica->setCodRubricaDespesa(mb_substr($line, 4, 15));
			$rubrica->setEspecificacaoRubricaDespesa(mb_substr($line, 19, 110));
			$rubrica->setTipoNivelConta(mb_substr($line, 129, 1));
            $rubrica->setNumeroNivelConta(mb_substr($line, 130, 2));

            RubricaBO::create($rubrica, $conn);
        }

        fclose($data);        
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Rubrica r');
        $query->where('r.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
		
	public function validate(Rubrica $rubrica){}
}