<?php

class CredorBO{

	public function create(Credor $credor, $conn=null){
    	CredorBO::validate($credor);
    	$credor->save($conn);
		$credor->free();

    	return $credor;
	}

	public function get($credorId){
        return Doctrine::getTable('Credor')->find($credorId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'c.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('c.*')
              ->from('Credor c');

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
		$credor = $pager->execute();
		
		$search->setPager($pager);
		
		$credorDTO = new DTO();
		$credorDTO->setObj($credor);
		$credorDTO->setSearch($search);

		return $credorDTO;
	}
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $credor = new Credor();
            $credor->setImportacaoId($importacaoId);
            $credor->setCodCredor(mb_substr($line,0,10));
            $credor->setNomeCredor(mb_substr($line,10,60));
            $credor->setCnpjCpfCredor(trim(mb_substr($line,70,14)));
            
		    CredorBO::create($credor, $conn);
		}
        
        fclose($data);
    }       
		
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Credor c');
        $query->where('c.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }         
        
	public function validate(Credor $credor){}
}