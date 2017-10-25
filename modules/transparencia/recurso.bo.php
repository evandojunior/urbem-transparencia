<?php

class RecursoBO{

	public function create(Recurso $recurso, $conn=null){
    	RecursoBO::validate($recurso);
    	$recurso->save($conn);
		$recurso->free();
		
    	return $recurso;
	}

	public function get($recursoId){
        return Doctrine::getTable('Recurso')->find($recursoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'p.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('p.*')
              ->from('Recurso p');

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
		$recurso = $pager->execute();
		
		$search->setPager($pager);
		
		$recursoDTO = new DTO();
		$recursoDTO->setObj($recurso);
		$recursoDTO->setSearch($search);

		return $recursoDTO;
	}
	
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $recurso = new Recurso();
            $recurso->setImportacaoId($importacaoId);

			$recurso->setCodRecurso(mb_substr($line, 0, 4));
			$recurso->setNomeRecurso(mb_substr($line, 4, 80));

            RecursoBO::create($recurso, $conn);
        }
        
         fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Recurso r');
        $query->where('r.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }     
		
	public function validate(Recurso $recurso){}
}