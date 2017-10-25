<?php

class OrgaoBO{

	public function create(Orgao $orgao, $conn=null){
    	OrgaoBO::validate($orgao);
    	$orgao->save($conn);
		$orgao->free();

    	return $orgao;
	}

	public function get($orgaoId){
        return Doctrine::getTable('Orgao')->find($orgaoId);
	}

	public function filter(Search $search){
		$fields = array(
			'id' => 'l.id',
		);
		
		$query = new Doctrine_Query();
        $query->select('l.*')
              ->from('Orgao l');

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
		$orgao = $pager->execute();

		$search->setPager($pager);

		$orgaoDTO = new DTO();
		$orgaoDTO->setObj($orgao);
		$orgaoDTO->setSearch($search);

		return $orgaoDTO;
	}

	public function import($file, $importacaoId, $conn){
        $data = fopen($file, 'r');
        
        while(!feof($data)) { 
        	$line = fgets($data,4096);
			/*
			 * Lembrar de calcular (-1) no 1° parâmetro do mb_substr, pois a documentação começa a contar os
			 * caracteres do 1 e o mb_substr começa a contagem pelo 0
			 */ 
            $orgao = new Orgao();
            $orgao->setImportacaoId($importacaoId);

			$orgao->setExercicio(mb_substr($line, 0, 4));
			$orgao->setCodOrgao(mb_substr($line, 4, 5));
			$orgao->setNomeOrgao(mb_substr($line, 9, 80));

            OrgaoBO::create($orgao, $conn);
        }
        
        fclose($data);
    }
    
    public function deleteByExercicio($exercicio, $conn){
        $query = Doctrine_Query::create($conn);
        $query->delete('Orgao o');
        $query->where('o.importacao_id IN (SELECT i.id
                                             FROM Importacao i
                                            WHERE i.exercicio = ? )', $exercicio);

        $query->execute();
    }       

	public function validate(Orgao $orgao){}
}