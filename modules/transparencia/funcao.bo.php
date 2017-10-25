<?php

class FuncaoBO{

	public function create(Funcao $funcao, $conn=null){
    	FuncaoBO::validate($funcao);
    	$funcao->save($conn);
    	$funcao->free();
		
    	return $funcao;
	}

	public function get($funcaoId){
        return Doctrine::getTable('Funcao')->find($funcaoId);
	}
	
	public function filter(Search $search){
		$fields = array(
			'id' => 'e.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('e.*')
              ->from('Funcao e');

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
		$funcao = $pager->execute();
		
		$search->setPager($pager);
		
		$funcaoDTO = new DTO();
		$funcaoDTO->setObj($funcao);
		$funcaoDTO->setSearch($search);

		return $funcaoDTO;
	}
    
	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */  
            $funcao = new Funcao();
            $funcao->setImportacaoId($importacaoId);
            $funcao->setExercicio(mb_substr($line,0,4));
            $funcao->setCodFuncao(mb_substr($line,4,2));
            $funcao->setNomeFuncao(mb_substr($line,6,80));
            
		    FuncaoBO::create($funcao, $conn);
		}
        
        fclose($data);
    }
    
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Funcao f');
        $query->where('f.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }      
		
	public function validate(Funcao $funcao){}
}