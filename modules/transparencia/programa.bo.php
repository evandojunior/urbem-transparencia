<?php

class ProgramaBO{

	public function create(Programa $programa, $conn=null){
    	ProgramaBO::validate($programa);
    	$programa->save($conn);
		$programa->free();

    	return $programa;
	}

	public function get($programaId){
        return Doctrine::getTable('Programa')->find($programaId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'p.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('p.*')
              ->from('Programa p');

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
		$programa = $pager->execute();
		
		$search->setPager($pager);
		
		$programaDTO = new DTO();
		$programaDTO->setObj($programa);
		$programaDTO->setSearch($search);

		return $programaDTO;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);

            $programa = new Programa();
            $programa->setImportacaoId($importacaoId);

			$programa->setExercicio(mb_substr($line, 0, 4));
			$programa->setCodPrograma(mb_substr($line, 4, 4));
			$programa->setNomePrograma(mb_substr($line, 8, 80));
			
            ProgramaBO::create($programa, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Programa p');
        $query->where('p.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }       
		
	public function validate(Programa $programa){}
}